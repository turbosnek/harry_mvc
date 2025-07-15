<?php

Class Student extends Database
{
    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /**
     * Vytvoří nového studenta a přidá do databáze
     *
     * @param string $first_name - Křestní jméno studenta
     * @param string $second_name - Příjmení studenta
     * @param int $age - Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej Studenta
     * @param ?array $profileImage - Profilový obrázek studenta
     *
     * @return bool
     */
    public function create(string $first_name, string $second_name, int $age, string $life, string $college, ?array $profileImage = null): bool
    {
        try {
            $this->conn->beginTransaction();

            $defaultImage = '/assets/images/layout/hogwarts-logo.png';

            $sql = "INSERT INTO student (first_name, second_name, age, life, college, profile_image)
                    VALUES (:first_name, :second_name, :age, :life, :college, :profile_image)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':first_name' => $first_name,
                ':second_name' => $second_name,
                ':age' => $age,
                ':life' => $life,
                ':college' => $college,
                ':profile_image' => $defaultImage,
            ]);

            $studentId = (int) $this->conn->lastInsertId();

            if ($profileImage && $profileImage['tmp_name']) {
                $path = $this->processProfileImage($profileImage, $studentId);
                if ($path !== false) {
                    $upd = $this->conn->prepare("UPDATE student SET profile_image = :img WHERE id = :id");
                    $upd->execute([':img' => $path, ':id' => $studentId]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log(date('[d/m/y H:i] ') . "Chyba při vytváření studenta: " . $e->getMessage() . "\n", 3, __DIR__ . "/../../errors/error.log");
            return false;
        }
    }

    /**
     * Získá všechny studenty z databáze
     *
     * @param array $columns - Pole názvů sloupců, které chceme získat
     *
     * @return array|false - Pole studentů nebo false při chybě
     */
    public function getAllStudents(array $columns = ['id', 'first_name', 'second_name']): array|false
    {
        // Povolené sloupce – whitelist
        $allowedColumns = ['id', 'first_name', 'second_name'];

        // Filtrace vstupního pole podle whitelistu
        $filteredColumns = array_intersect($columns, $allowedColumns);

        // Pokud je výsledek prázdný, zamezíme SELECT bez sloupců
        if (empty($filteredColumns)) {
            return false;
        }

        // Sestavení dotazu
        $columnList = implode(", ", $filteredColumns);
        $sql = "SELECT $columnList FROM student";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Logování chyby
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získávání studentů: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }

    /**
     * Získá informace o jednom studentovi
     *
     * @param int $id - ID studenta
     * @param string $columns - Sloupce, které chceme získat (např. "id, first_name, age")
     *
     * @return array|null - Pole s daty studenta nebo null, pokud student neexistuje nebo dojde k chybě
     */
    public function getStudent(int $id, string $columns): ?array
    {
        // Povolené sloupce
        $allowedColumns = ['id', 'first_name', 'second_name', 'age', 'life', 'college', "profile_image"];

        // Zpracování požadovaných sloupců
        $columnsArray = array_map('trim', explode(',', $columns));
        $safeColumns = [];

        foreach ($columnsArray as $column) {
            if (in_array($column, $allowedColumns, true)) {
                $safeColumns[] = $column;
            } else {
                // Nepovolený sloupec – můžeš logovat nebo rovnou vyhodit výjimku
                throw new InvalidArgumentException("Nepovolený sloupec: $column");
            }
        }

        // Vytvoření bezpečného seznamu sloupců
        $safeColumnString = implode(', ', $safeColumns);

        $sql = "SELECT $safeColumnString FROM student WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false ? $result : null;
        } catch (PDOException $e) {
            // Logování chyby
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získávání studenta: " . $e->getMessage() . "\n", 3, $logPath);

            return null;
        }
    }

    /**
     * Smaže studenta z databáze podle jeho ID
     *
     * @param int $id - ID studenta
     * @return bool
     */
    public function deleteStudent(int $id): bool {
        // smažeme obrázek
        $this->deleteExistingImage($id);

        // pak originál
        $sql = "DELETE FROM student WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log(date('[d/m/y H:i] ') . "Chyba při mazání studenta (ID: $id): " . $e->getMessage() . "\n",3, __DIR__ . "/../../errors/error.log");
        }
        return false;
    }

    /**
     * Aktualizuje informace o studentovi
     *
     * @param string $first_name - Křestní jmeéno studenta
     * @param string $second_name - Příjmení studenta
     * @param int $age- Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej studenta
     * @param int $id - ID studenta
     * @param ?array $profileImage - Profilový obrázek studenta
     *
     * @return bool
     */
    public function updateStudent(string $first_name, string $second_name, int $age, string $life, string $college, int $id, ?array $profileImage = null): bool
    {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE student
                    SET first_name = :first_name, second_name = :second_name, age = :age, life = :life, college = :college
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':first_name' => $first_name,
                ':second_name' => $second_name,
                ':age' => $age,
                ':life' => $life,
                ':college' => $college,
                ':id' => $id
            ]);

            if ($profileImage && $profileImage['tmp_name']) {
                $this->deleteExistingImage($id);

                $path = $this->processProfileImage($profileImage, $id);
                if ($path !== false) {
                    $upd = $this->conn->prepare("UPDATE student SET profile_image = :img WHERE id = :id");
                    $upd->execute([':img' => $path, ':id' => $id]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log(date('[d/m/y H:i] ') . "Chyba při updatování studenta: " . $e->getMessage() . "\n", 3, __DIR__ . "/../../errors/error.log");
            return false;
        }
    }

    /**
     * Zprocesuje přípravu profilového obrázku na uložení
     *
     * @param array $image - Profilový obrázek
     * @param int $studentId - ID Studenta
     *
     * @return string|false
     */
    private function processProfileImage(array $image, int $studentId): string|false
    {
        // Povolené přípony
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        // Kontrola chyb a přípony
        if ($image['error'] !== UPLOAD_ERR_OK || !in_array($ext, $allowed)) {
            $this->errors[] = "Chyba při nahrávání nebo nepovolený formát obrázku.";
            return false;
        }

        // Kontrola velikosti (max 9 MB)
        if ($image['size'] > 9 * 1024 * 1024) {
            $this->errors[] = "Maximální velikost obrázku je 9 MB.";
            return false;
        }

        // Cesta pro uložení
        $uploadDir = __DIR__ . "/../../public/assets/images/student/profile/{$studentId}/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $dest = $uploadDir . "profile.{$ext}";

        // Rozměry a nové plátno
        [$w,$h] = getimagesize($image['tmp_name']);
        $newW = 150; $newH = 150;
        $dst = imagecreatetruecolor($newW, $newH);

        // Vytvoření zdroje obrázku
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($image['tmp_name']);
                break;
            case 'png':
                $src = imagecreatefrompng($image['tmp_name']);
                break;
            case 'gif':
                $src = imagecreatefromgif($image['tmp_name']);
                break;
            default:
                $this->errors[] = "Nepodařilo se zpracovat obrázek.";
                return false;
        }

        // Zmenšení a uložení
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($dst, $dest, 90);
                break;
            case 'png':
                imagepng($dst, $dest);
                break;
            case 'gif':
                imagegif($dst, $dest);
                break;
        }

        // Úklid
        imagedestroy($src);
        imagedestroy($dst);

        // Cesta pro uložení do DB
        return "/assets/images/student/profile/{$studentId}/profile.{$ext}";
    }

    /**
     * Smaže profilový obrázek
     *
     * @param int $studentId - ID Studenta
     *
     * @return void
     */
    private function deleteExistingImage(int $studentId): void
    {
        $sql = "SELECT profile_image FROM student WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $studentId]);
        $img = $stmt->fetchColumn();

        if ($img && !str_contains($img, 'hogwarts-logo.png')) {
            $file = __DIR__.'/../../public' . $img;
            if (file_exists($file)) unlink($file);
            $folder = dirname($file);
            if (is_dir($folder)) {
                array_map('unlink', glob("$folder/*"));
                rmdir($folder);
            }
        }
    }
}
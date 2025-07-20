Jednoduchý framework, který je vytvořený v OOP PHP na architektuře MVC.

Je to přetvořený web do MVC architektury od Davida Šetka

https://www.youtube.com/playlist?list=PLQ8x_VWW6AktaGgUDBMm_3to4bLDdu8HI

https://www.youtube.com/playlist?list=PLQ8x_VWW6Aku5QppZ2yQCoSxZoZXv6FRY

Aby jste mohli spustit projekt na vašem localhostu, musíte vytvořit pro web subdoménu, aby jste mohli na projekt přes url http://vášprojekt

Můžete použít tento návod

https://www.itnetwork.cz/php/zaklady/tutorial-konfigurace-php-xampp-subdomeny-emaily-limity

Co je jiného oproti verzi Davida.

Tato verze je napsaná v MVC architektuře. Tudíž je rozdělená část pro práci s databází a pro práci s obsluho třeba formulářů nebo vypisování dat.

Routování řeším v app/core/App.php, jelikož nepoužívám composer, tak je to řešené takto.

V této verzi nemám přidávání fotek, ale mám tu udělanou možnost profilového obrázku u studentů. Obrázek není povinný, proto, když ho při vytváření nezvolíte, nemělo by to vyhodit chybu, že není něco vyplněno ve formuláři. Když není obrázek vybrán, tak se přidělí automaticky základní logo Bradavic.

Profilový obrázek má kontrolu, jestli má správnou velikost souboru, po nahrání se automaticky zmenší na velikost 150x150px

Veřejné menu a administrátorské menu včetně JS je výplod AI, jelikož já nejsem žádný FE Dev :D


Mám tu celkem 3 admin role: ROLE_USER, ROLE_ADMIN, ROLE_SUPER_ADMIN
Nemám nijak rozlišeno, co kdo může dělat, ale určitě by se našlo uplatnění, že ROLE_SUPER_ADMIN bude moc přidělovat nové role uživatelům, mazat uživatele adt.

Dále už nevím, co napsat.

Soubor pro vytvoření databáze a tabulek včetně obsahu je ve složce sql.

každý uživatel má nastavené heslo admin123.

Nezapomeňte změnit údaje k databázi. Najdete to v app/core/Database.php
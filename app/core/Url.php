<?php

class Url {
    /**
     * Přesměruje uživatele na zadanou adresu
     *
     * @param string $path - Adresa k přesměrování
     *
     * @return void
     */
    public static function redirectUrl(string $path): void {
        if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != "off") {
            $url_protocol = "https";
        } else {
            $url_protocol = "http";
        }

        header("location: $url_protocol://" . $_SERVER['HTTP_HOST'] . $path);
    }
}
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 20. čec 2025, 20:52
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `school_mvc`
--
    CREATE DATABASE IF NOT EXISTS `school_mvc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `school_mvc`;

-- --------------------------------------------------------

--
-- Struktura tabulky `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `life` text NOT NULL,
  `college` varchar(50) NOT NULL,
  `profile_image` varchar(255) NOT NULL DEFAULT '/assets/images/layout/hogwarts-logo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `student`
--

INSERT INTO `student` (`id`, `first_name`, `second_name`, `age`, `life`, `college`, `profile_image`) VALUES
(8, 'Harry', 'Potter', 11, 'Test', 'Nebelvír', '/assets/images/layout/hogwarts-logo.png'),
(9, 'Ronald', 'Weasley', 11, 'Test', 'Nebelvír', '/assets/images/layout/hogwarts-logo.png'),
(10, 'Hermiona', 'Grangerová', 11, 'Test', 'Nebelvír', '/assets/images/layout/hogwarts-logo.png'),
(11, 'Draco', 'Malfoy', 11, 'Test', 'Zmijozel', '/assets/images/layout/hogwarts-logo.png'),
(12, 'Fred', 'Weasley', 17, 'Test', 'N', '/assets/images/layout/hogwarts-logo.png'),
(13, 'George', 'Weasley', 17, 'Test', 'Nebelvír', '/assets/images/layout/hogwarts-logo.png'),
(19, 'Ginny', 'Weasleyová', 11, 'Test', 'Nebelvír', '/assets/images/layout/hogwarts-logo.png');

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `second_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ROLE_USER','ROLE_ADMIN','ROLE_SUPER_ADMIN') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `first_name`, `second_name`, `email`, `password`, `role`) VALUES
(15, 'Minerva', 'McGonagallová', 'mcgonagallova@bradavice.com', '$2y$10$k8HXfUow0exhaR3klkTDf.mZDGkK3aVD.VpZKCA5Z3NRjQ7Dfmhg2', 'ROLE_ADMIN'),
(16, 'Severus', 'Snape', 'snape@bradavice.com', '$2y$10$jbRyYHfk70b5vSTgMPGxHO78H8LKqDPuJ8ENgLwh0vqYLgeepLVPq', 'ROLE_USER'),
(17, 'Remus', 'Lupin', 'lupin@bradavice.com', '$2y$10$9CpsniE1xaA6rA4DZH4VpOQUZN/7LhfaIvM7MMq5ICEXCw9I/FgmG', 'ROLE_USER'),
(18, 'Albus', 'Brumbál', 'brumbal@bradavice.com', '$2y$10$TfX85Nh7QUNMhgw2FhwM3.sRbm9B/AUwTWxqQ.E7SaNEXztLK2Bka', 'ROLE_SUPER_ADMIN'),
(20, 'Alastor', 'Moody', 'moody@bradavice.com', '$2y$10$VG0OCie8TENLGgkEHChMaesoMke5O4yULGQlPIFeLPtgXHMdn.mFe', 'ROLE_USER'),
(21, 'Rubeus', 'Hagrid', 'hagrid@bradavice.com', '$2y$10$5Zv1kQZZ.n/Od4LmIuP9G.8a27ae5JSELJ9vUtvEJdEh78/1WVtry', 'ROLE_USER'),
(36, 'Armando', 'Dippet', 'dippet@bradavice.com', '$2y$10$PERhqOKVVeO1UTt8D4GyOu3MyJ3KFtopTu4/vIe/IqUaZ2v3FJNB.', 'ROLE_USER');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2025 at 08:56 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mymusic`
--

-- --------------------------------------------------------

--
-- Table structure for table `chansons`
--

CREATE TABLE `chansons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(50) NOT NULL,
  `artiste` varchar(25) NOT NULL,
  `paroles` text NOT NULL,
  `album` text NOT NULL,
  `datePublication` date NOT NULL,
  `duree` int(11) NOT NULL,
  `nombreDeLectures` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chansons`
--

INSERT INTO `chansons` (`id`, `titre`, `artiste`, `paroles`, `album`, `datePublication`, `duree`, `nombreDeLectures`) VALUES
(1, 'Test Song', 'Test Artist', 'These are the song lyrics', 'Test Album', '2025-01-25', 200, 10),
(5, 'Test Song 2', 'Test Artist 2', 'These are the song lyrics for the second', 'Test Album 2', '2025-01-25', 20000, 110),
(16, 'ijnjin', 'g', 'g', 'g', '2025-01-30', 3, 2),
(17, 'Test Song', 'Test Artist', 'These are the song lyrics', 'Test Album', '2025-01-25', 200, 10),
(19, 'Test Song', 'Test Artist', 'These are the song lyrics', 'Test Album', '2025-01-25', 200, 10),
(20, 'add to list1', 'artieaza', 'azza', 'zdadazdaz', '2025-01-25', 200, 10);

-- --------------------------------------------------------

--
-- Table structure for table `listes`
--

CREATE TABLE `listes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(50) NOT NULL,
  `soustitre` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(25) NOT NULL,
  `verifie` tinyint(1) NOT NULL,
  `datePublication` date NOT NULL,
  `visibilite` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listes`
--

INSERT INTO `listes` (`id`, `titre`, `soustitre`, `image`, `description`, `type`, `verifie`, `datePublication`, `visibilite`) VALUES
(1, 'Classiques français', 'Les incontournables de la chanson française', 'https://fastly.picsum.photos/id/382/200/300.jpg?hmac=ql7Jj1WJu3zhhAn2p18Oxdn-JE1qZBR-lDF-MOVXCUA', 'classiques', 'musique', 1, '2023-09-15', 'publique'),
(3, 'Hip-Hop Essentials', 'Les meilleurs morceaux de hip-hop', 'https://fastly.picsum.photos/id/732/200/300.jpg?hmac=mBueuWVJ8LlL-R7Yt9w1ONAFVayQPH5DzVSO-lPyI9w', 'Explorez les classiques et les nouveautés du hip-hop.', 'musique', 1, '2023-07-10', 'publique'),
(4, 'Electro Dance', 'Les hits pour danser toute la nuit', 'https://fastly.picsum.photos/id/444/200/300.jpg?hmac=xTzo_bbWzDyYSD5pNCUYw552_qtHzg0tQUKn50R6FOM', 'Une playlist énergique pour vos soirées dansantes.', 'musique', 1, '2023-06-05', 'publique'),
(5, 'Souvenirs dété', 'Les chansons de mon été 2023', 'https://fastly.picsum.photos/id/343/200/300.jpg?hmac=_7ttvLezG-XONDvp0ILwQCv50ivQa_oewm7m6xV2uZA', 'Les titres qui ont marqué mon été cette année.', 'musique', 0, '2023-09-20', 'prive'),
(6, 'Workout Motivation', 'Ma playlist pour le sport', 'https://fastly.picsum.photos/id/658/200/300.jpg?hmac=K1TI0jSVU6uQZCZkkCMzBiau45UABMHNIqoaB9icB_0', 'Des morceaux énergisants pour mes séances de sport.', 'musique', 0, '2023-08-25', 'prive'),
(7, 'Nostalgie années 2000', 'Les hits de mon adolescence', 'https://fastly.picsum.photos/id/87/200/300.jpg?hmac=YgijkxA35wxtPYqEsxGObDtNAlK3MVmNNb8ZH8IX1Rs', 'Une plongée dans les chansons des années 2000.', 'musique', 0, '2023-07-15', 'prive'),
(8, 'Soirée entre amis', 'Playlist pour nos soirées', 'https://fastly.picsum.photos/id/1067/200/300.jpg?hmac=9UpH9GvB6swkUWpIG1N53lIk9vdO4YcIwlH59M8er18', 'Une sélection de titres pour passer de bons moments entre amis.', 'musique', 0, '2023-06-10', 'prive');

-- --------------------------------------------------------

--
-- Table structure for table `listes_chansons`
--

CREATE TABLE `listes_chansons` (
  `liste_id` bigint(20) UNSIGNED NOT NULL,
  `chanson_id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listes_chansons`
--

INSERT INTO `listes_chansons` (`liste_id`, `chanson_id`, `ordre`) VALUES
(1, 2, 23),
(1, 0, 28),
(4, 19, 31),
(1, 1, 71),
(3, 5, 82),
(1, 3, 86),
(1, 4, 88),
(1, 7, 105),
(1, 9, 109),
(3, 8, 112),
(8, 67, 118),
(8, 45, 119),
(8, 44, 121),
(8, 43, 123),
(8, 21, 127),
(8, 17, 132),
(8, 24, 133),
(8, 16, 140);

-- --------------------------------------------------------

--
-- Table structure for table `liste_utilisateur`
--

CREATE TABLE `liste_utilisateur` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `liste_id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `liste_utilisateur`
--

INSERT INTO `liste_utilisateur` (`id`, `utilisateur_id`, `liste_id`, `ordre`) VALUES
(1, 1, 1, 1),
(2, 2, 3, 2),
(4, 3, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `courriel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `courriel`) VALUES
(1, 'user1', 'prenom1', 'courriel1'),
(2, 'user2', 'prenom2', 'courriel2'),
(3, 'user3', 'prenom3', 'courriel3'),
(4, 'user4', 'prenom4', 'courriel4'),
(5, 'user5', 'prenom5', 'courriel5'),
(6, 'user6', 'prenom6', 'courriel6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chansons`
--
ALTER TABLE `chansons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `listes`
--
ALTER TABLE `listes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `listes_chansons`
--
ALTER TABLE `listes_chansons`
  ADD PRIMARY KEY (`ordre`),
  ADD KEY `chanson_id` (`chanson_id`),
  ADD KEY `fk_liste_chansons_liste` (`liste_id`);

--
-- Indexes for table `liste_utilisateur`
--
ALTER TABLE `liste_utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_utilisateur_id_utilisateur` (`utilisateur_id`),
  ADD KEY `fk_listes_id_listes` (`liste_id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chansons`
--
ALTER TABLE `chansons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `listes`
--
ALTER TABLE `listes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `listes_chansons`
--
ALTER TABLE `listes_chansons`
  MODIFY `ordre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `liste_utilisateur`
--
ALTER TABLE `liste_utilisateur`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listes_chansons`
--
ALTER TABLE `listes_chansons`
  ADD CONSTRAINT `fk_liste_chansons_liste` FOREIGN KEY (`liste_id`) REFERENCES `listes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `liste_utilisateur`
--
ALTER TABLE `liste_utilisateur`
  ADD CONSTRAINT `fk_listes_id_listes` FOREIGN KEY (`liste_id`) REFERENCES `listes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_id_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

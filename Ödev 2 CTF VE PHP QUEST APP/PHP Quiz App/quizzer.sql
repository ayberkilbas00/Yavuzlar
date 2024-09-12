-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 Eyl 2024, 13:23:16
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `quizzer`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `choices`
--

CREATE TABLE `choices` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `choices`
--

INSERT INTO `choices` (`id`, `question_id`, `is_correct`, `text`) VALUES
(112, 28, 1, 'a'),
(113, 28, 0, 'b'),
(114, 28, 0, 'b'),
(115, 28, 0, 'b');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `text` text NOT NULL,
  `score` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `questions`
--

INSERT INTO `questions` (`id`, `question_number`, `text`, `score`) VALUES
(28, 1, 'a', 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_choice_id` int(11) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'ayberk', '$2y$10$55PrBkTJo2EXKydzMcgS4.SS29nkd6xr3eFB.zzoy.HyL0zyjwuc.', 'Student'),
(2, 'admin', '$2y$10$yWkKTZCOTn2EzuEFQMLKAujj908vnXRqkQswxVhEWFvbCvVKQuKdi', 'Admin');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Tablo için indeksler `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_number` (`question_number`);

--
-- Tablo için indeksler `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `selected_choice_id` (`selected_choice_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- Tablo için AUTO_INCREMENT değeri `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Tablo için AUTO_INCREMENT değeri `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `choices`
--
ALTER TABLE `choices`
  ADD CONSTRAINT `choices_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `submissions_ibfk_3` FOREIGN KEY (`selected_choice_id`) REFERENCES `choices` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

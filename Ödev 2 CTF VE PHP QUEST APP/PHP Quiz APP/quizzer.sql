-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 06 Eyl 2024, 22:43:06
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
(36, 9, 0, 'Isletim Sistemi'),
(37, 9, 0, '?saretleme Dili'),
(38, 9, 0, 'Programlama Dili'),
(39, 9, 0, 'Veritabani Dili'),
(40, 10, 0, '{}'),
(41, 10, 0, '()'),
(42, 10, 1, '[]'),
(43, 10, 0, '<>'),
(44, 11, 0, '.htm'),
(45, 11, 1, '.html'),
(46, 11, 0, '.txt'),
(47, 11, 0, '.doc'),
(48, 12, 0, 'Sunucu tarafl?'),
(49, 12, 1, '?stemci tarafl?'),
(50, 12, 0, 'Veritaban? yönetimi'),
(51, 12, 0, 'A? yönetimi'),
(52, 13, 1, 'INSERT'),
(53, 13, 0, 'UPDATE'),
(54, 13, 0, 'DELETE'),
(55, 13, 0, 'SELECT'),
(56, 14, 0, 'Veritaban? i?lemleri'),
(57, 14, 1, 'HTML sayfalar?n? biçimlendirme'),
(58, 14, 0, 'JavaScript i?lemleri'),
(59, 14, 0, 'Sunucu yap?land?rma'),
(60, 15, 0, '123abc'),
(61, 15, 1, '_my_var'),
(62, 15, 0, 'my-var'),
(63, 15, 0, 'my var'),
(64, 16, 0, '<h>'),
(65, 16, 0, '<p>'),
(66, 16, 1, '<h1> - <h6>'),
(67, 16, 0, '<head>'),
(68, 17, 1, '//'),
(69, 17, 0, '\\'),
(70, 17, 0, '#'),
(71, 17, 0, '--'),
(72, 18, 0, 'exit'),
(73, 18, 1, 'break'),
(74, 18, 0, 'continue'),
(75, 18, 0, 'end');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `questions`
--

INSERT INTO `questions` (`id`, `question_number`, `text`) VALUES
(9, 1, 'HTML hangi programlama dili türüdür?'),
(10, 2, 'Python\'da bir liste nas?l tan?mlan?r?'),
(11, 3, 'Bir web taray?c?s?nda görüntülenmek üzere HTML dosyas? hangi uzant?yla kaydedilir?'),
(12, 4, 'JavaScript hangi alanda yayg?n olarak kullan?l?r?'),
(13, 5, 'SQL’de veritaban?na yeni kay?t eklemek için hangi komut kullan?l?r?'),
(14, 6, 'CSS ne için kullan?l?r?'),
(15, 7, 'Hangisi Python’da bir de?i?ken ismi olabilir?'),
(16, 8, 'Bir HTML sayfas?ndaki ba?l?klar hangi etiketle olu?turulur?'),
(17, 9, 'JavaScript\'te hangi sembol bir yorum sat?r? ba?lat?r?'),
(18, 10, 'Python’da bir döngüyü sonland?rmak için hangi komut kullan?l?r?');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_choice_id` int(11) NOT NULL,
  `correct` tinyint(1) NOT NULL
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Tablo için AUTO_INCREMENT değeri `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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

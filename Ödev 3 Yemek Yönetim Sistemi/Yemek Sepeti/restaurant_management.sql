-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 21 Eyl 2024, 16:49:22
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
-- Veritabanı: `restaurant_management`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) DEFAULT 'active',
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount`, `restaurant_id`, `expires_at`, `created_at`, `status`, `type`) VALUES
(3, 'admin10', 10.00, 1, '2024-09-13 18:36:00', '2024-09-19 18:38:33', 'active', NULL),
(4, 'ayberk10', 10.00, 1, '2024-09-14 18:40:00', '2024-09-19 18:40:33', 'active', NULL),
(5, 'mcdonald12', 12.00, 2, '2024-09-07 12:35:00', '2024-09-21 12:35:10', 'active', NULL),
(6, 'admin', 50.00, 1, '2024-10-06 14:00:00', '2024-09-21 14:00:47', 'active', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dishes`
--

CREATE TABLE `dishes` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `dishes`
--

INSERT INTO `dishes` (`id`, `restaurant_id`, `name`, `price`, `description`, `image`, `created_at`) VALUES
(2, 1, 'Whopper Menü', 200.00, 'köfte domates ve turşunun mükemmel uyumu !', 'uploads/whopper-menu.png', '2024-09-19 19:26:16'),
(3, 1, 'mantı', 120.00, 'en güzel mantı', 'uploads/whopper-menu.png', '2024-09-20 07:34:05'),
(5, 2, 'King Chicken Menü', 220.00, 'tavukla marulun muhteşem uyumu', 'uploads/kingchicken.jpeg', '2024-09-20 20:23:24'),
(6, 3, 'Mantı', 100.00, 'ev yapımı mantı', 'uploads/mantı.jpeg', '2024-09-20 20:27:58'),
(7, 3, 'Çıtır Mantı', 120.00, 'Mis gibi çıtır mantı', 'uploads/çıtır mantı.jpg', '2024-09-20 20:28:41'),
(8, 4, 'Tavuk Döner', 60.00, 'İçerisinde muhtesem malzemeler var ', 'uploads/döner.jpeg', '2024-09-20 20:37:45'),
(9, 4, 'Pilav Üstü Tavuk Döner', 90.00, 'pilav üstü tavuk döner yanında domates marul turşu ile birlikte', 'uploads/pilavdoner.jpg', '2024-09-20 20:40:02');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `restaurant_id`, `total_price`, `status`, `created_at`) VALUES
(1, 3, 1, 320.00, 'pending', '2024-09-20 08:11:17'),
(2, 3, 1, 320.00, 'pending', '2024-09-20 19:13:39'),
(3, 3, 2, 220.00, 'pending', '2024-09-20 20:46:13'),
(4, 3, 1, 320.00, 'pending', '2024-09-21 12:34:32'),
(5, 3, 1, 200.00, 'pending', '2024-09-21 14:00:11'),
(6, 3, 1, 200.00, 'pending', '2024-09-21 14:02:48'),
(7, 3, 1, 350.00, 'pending', '2024-09-21 14:06:16'),
(8, 3, 3, 50.00, 'pending', '2024-09-21 14:07:03'),
(9, 3, 1, 120.00, 'pending', '2024-09-21 14:30:23'),
(10, 3, 1, 120.00, 'pending', '2024-09-21 14:32:48'),
(11, 3, 1, 120.00, 'pending', '2024-09-21 14:35:24'),
(12, 3, 1, 200.00, 'pending', '2024-09-21 14:43:55'),
(13, 3, 4, 60.00, 'pending', '2024-09-21 14:44:27');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `dish_id`, `quantity`, `price`) VALUES
(1, 1, 3, 1, 120.00),
(2, 1, 2, 1, 200.00),
(3, 2, 2, 1, 200.00),
(4, 2, 3, 1, 120.00),
(5, 3, 5, 1, 220.00),
(6, 4, 2, 1, 200.00),
(7, 4, 3, 1, 120.00),
(8, 5, 2, 1, 200.00),
(9, 6, 2, 1, 200.00),
(10, 7, 2, 2, 200.00),
(11, 8, 6, 1, 100.00),
(12, 9, 3, 1, 120.00),
(13, 10, 3, 1, 120.00),
(14, 11, 3, 1, 120.00),
(15, 12, 2, 1, 200.00),
(16, 13, 8, 1, 60.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `restaurants`
--

INSERT INTO `restaurants` (`id`, `owner_id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Burger King', 'En güzel hamburgerler bizde mevcut', '2024-09-19 18:21:42', '2024-09-19 18:22:39', NULL),
(2, 4, 'Mc Donald', 'Dünyanın 1 numaralı fast food zinciri', '2024-09-20 20:15:47', '2024-09-20 20:16:17', NULL),
(3, 5, 'Mantıcı Kardelen Usta', 'Ev yapımı şahane mantı', '2024-09-20 20:25:58', '2024-09-20 20:29:29', NULL),
(4, 6, 'Dönerci İbo', 'nerde o dönerine yağ katüyür diyen', '2024-09-20 20:35:39', '2024-09-20 20:40:25', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `dish_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `restaurant_id`, `dish_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 3, NULL, 5, 'mantılar çok güzel', '2024-09-21 14:27:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','firma','müşteri') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `profile_image` varchar(255) DEFAULT 'default_profile.jpg',
  `profile_pic` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `deleted_at`, `balance`, `profile_image`, `profile_pic`) VALUES
(1, 'admin', 'admin@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eUUvY0VxZnlzUjVSanFJVw$t6KdA4LrwRX+V0VrmBdD2z7P1OTqHd/+yP0SdvVlKJk', 'admin', '2024-09-19 18:17:20', NULL, 0.00, 'default_profile.jpg', 'default.jpg'),
(2, 'firma', 'firma@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$NGthV1QxOFJEdERwd1hFRw$VgPHTxMjmDML0GBYkkqTa/i0BjLOAhEZMm7j6TlHNW4', 'firma', '2024-09-19 18:21:42', NULL, 0.00, 'default_profile.jpg', 'default.jpg'),
(3, 'Ayberk İlbaş', 'musteri@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ODludDk4Mm9VSy9xTXhUYw$gYCvmf7mLY/Fw5XlMp2pVhb3AhdVlSIKrQJ3xw6lWTA', 'müşteri', '2024-09-19 18:22:10', NULL, 0.00, 'default_profile.jpg', 'IMG_20230520_113711 (1).jpg'),
(4, 'Mc Donald', 'mcdonald@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RlBZU241amI1OThrLzRmVA$0LaIV/H2y4yu8thwgKwqy7YVddYFL6xkKQz7hVjUXQk', 'firma', '2024-09-20 20:15:47', NULL, 0.00, 'default_profile.jpg', 'default.jpg'),
(5, 'Mantıcı Kardelen', 'manti@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Sy5DQ3lKL2RhbmgwR3BEdQ$noyF4fPAMZO/gpLQmNV7vmrR6wUZCbR0a8LMc+P5AQ0', 'firma', '2024-09-20 20:25:58', NULL, 0.00, 'default_profile.jpg', 'default.jpg'),
(6, 'dönerci ibo', 'doner@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RG9XdXVpSWN2d0tQZkdCNg$jVlmPglCrEAHBK9aJGucQXQ+YLYsdmXZdcSe7o4iiHY', 'firma', '2024-09-20 20:35:39', NULL, 0.00, 'default_profile.jpg', 'default.jpg');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Tablo için indeksler `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Tablo için indeksler `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Tablo için indeksler `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Tablo için indeksler `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `dishes_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

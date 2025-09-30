CREATE DATABASE IF NOT EXISTS `LaptopShop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `LaptopShop`;

-- Create table for laptop data
CREATE TABLE IF NOT EXISTS `laptops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `laptops` (`brand`, `model`, `price`, `stock`) VALUES
('Dell', 'Inspiron 15', 650.00, 10),
('HP', 'Pavilion x360', 720.00, 8),
('Apple', 'MacBook Air M1', 999.00, 5),
('Asus', 'ZenBook 14', 850.00, 12);


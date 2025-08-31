SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/* old client/result/collation settings */
SET NAMES utf8mb4;

-- Database: `ecommerce_site`
CREATE DATABASE IF NOT EXISTS `ecommerce_site`;
USE `ecommerce_site`;

-- -------------------------
-- Table structure for `admin`
-- -------------------------
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `is_active` ENUM('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `is_active`) VALUES
(1, 'Admin', 'admin@mail.com', '$2y$10$YKSDtra7v2wH6ORYfry8Ue9t49pk1AvQvdJGuq4lDvFLEcx.kP6Mq', '1');

-- -------------------------
-- Table structure for `categories`
-- -------------------------
CREATE TABLE `categories` (
  `cat_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cat_title` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(1, 'All Products'),
(2, 'Veg Achaar'),
(3, 'Non Veg Achaar');

-- -------------------------
-- Table structure for `products`
-- -------------------------
CREATE TABLE `products` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_cat` INT(11) NOT NULL,
  `product_title` VARCHAR(255) NOT NULL,
  `product_price` DECIMAL(10,2) NOT NULL,
  `product_qty` INT(11) NOT NULL,
  `product_desc` TEXT NOT NULL,
  `product_image` VARCHAR(255) NOT NULL,
  `product_keywords` TEXT NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `fk_product_cat` (`product_cat`),
  CONSTRAINT `fk_product_cat` FOREIGN KEY (`product_cat`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example pickle products
INSERT INTO `products` (`product_cat`, `product_title`, `product_price`, `product_qty`, `product_desc`, `product_image`, `product_keywords`) VALUES
(2, 'Mango Achaar', 250.00, 50, 'Traditional Nepali mango pickle with mustard oil and spices.', 'mango.jpg', 'mango, achar, pickle'),
(2, 'Mixed Veg Achaar', 300.00, 40, 'Homemade mixed vegetable pickle.', 'mixed.jpg', 'mixed, achar, pickle'),
(3, 'Fish Achaar', 400.00, 30, 'Spicy fish pickle from Terai region.', 'fish.jpg', 'fish, achar, nonveg'),
(2, 'Chili Achaar', 200.00, 60, 'Hot and spicy green chili pickle.', 'chili.jpg', 'chili, achar, spicy');

-- -------------------------
-- Table structure for `user_info`
-- -------------------------
CREATE TABLE `user_info` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(300) NOT NULL UNIQUE,
  `password` VARCHAR(300) NOT NULL,
  `mobile` VARCHAR(15) NOT NULL,
  `address1` VARCHAR(300) NOT NULL,
  `address2` VARCHAR(300) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert user with hashed password ('123456')
INSERT INTO `user_info` (`first_name`, `last_name`, `email`, `password`, `mobile`, `address1`, `address2`) VALUES
('Suman', 'Shrestha', 'suman@mail.com', '$2y$10$Z9kJkKJozNnF2WQHq3qMxe/tXoVbkTCXG01N1qC6G/RuUkm6n9fPe', '9800000000', 'Kathmandu', 'Bagmati');

-- -------------------------
-- Table structure for `cart`
-- -------------------------
CREATE TABLE `cart` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `p_id` INT(11) NOT NULL,
  `ip_add` VARCHAR(250) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `qty` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_product` (`p_id`),
  KEY `fk_cart_user` (`user_id`),
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`p_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cart` (`p_id`, `ip_add`, `user_id`, `qty`) VALUES
(1, '127.0.0.1', 1, 2),
(3, '::1', NULL, 1);

-- -------------------------
-- Table structure for `orders`
-- -------------------------
CREATE TABLE `orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `qty` INT(11) NOT NULL,
  `trx_id` VARCHAR(255) NOT NULL,
  `p_status` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `fk_order_user` (`user_id`),
  KEY `fk_order_product` (`product_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`user_id`, `product_id`, `qty`, `trx_id`, `p_status`) VALUES
(1, 1, 1, '9L434522M7706801A', 'Completed'),
(1, 2, 2, '8AT7125245323433N', 'Pending');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/* old client/result/collation reset */

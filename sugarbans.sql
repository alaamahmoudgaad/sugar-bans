-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 08:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sugarbans`
--

-- --------------------------------------------------------

--
-- Table structure for table `boxes`
--

CREATE TABLE `boxes` (
  `box_id` int(11) NOT NULL,
  `box_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `box_price` decimal(10,2) NOT NULL,
  `box_image_url` varchar(255) DEFAULT NULL,
  `products_included` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boxes`
--

INSERT INTO `boxes` (`box_id`, `box_name`, `description`, `box_price`, `box_image_url`, `products_included`, `is_available`) VALUES
(1, 'Besties Box', '2 Donuts (Chocolate + Caramel) + 2 Cold Drinks (Iced Mocha / Iced Coffee)', 250.00, 'images/besties box.jpg', 'Chocolate donut, Caramel donut, Iced Mocha, Iced Coffee', 1),
(2, 'Sweet Heaven', 'Cheesecake slice + Tiramisu slice + 1 Cold Drink', 250.00, 'images/sweet heaven.jpg', 'Cheesecake, Tiramisu, Iced Latte', 1),
(3, 'Sugar Rush Duo', '2 Donuts + 2 Cupcakes + 2 Cold Drinks', 400.00, 'images/sugar rush duo.jpg', 'Chocolate donut, Caramel donut, Vanilla cupcake, Caramel cupcake, Iced Mocha, Iced Coffee', 1),
(4, 'Honey Mood', 'Honey Cake slice + Iced Latte', 150.00, 'images/honey mood.jpg', 'Honey cake, Iced Latte', 1),
(5, 'Chill Combo', 'Cupcake + Tiramisu slice + Cold Drink', 200.00, 'images/chill combo.jpg', 'Vanilla cupcake, Tiramisu, Iced Latte', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `level` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `parent_id`, `level`, `display_order`) VALUES
(1, 'Drinks', NULL, 0, 1),
(2, 'Desserts', NULL, 0, 2),
(3, 'Cakes', NULL, 0, 3),
(4, 'Hot Drinks', 1, 1, 1),
(5, 'Cold Drinks', 1, 1, 2),
(6, 'Flavored Coffee', 1, 1, 3),
(7, 'Western Desserts', 2, 1, 1),
(8, 'Eastern Desserts', 2, 1, 2),
(9, 'Cupcakes', 7, 2, 1),
(10, 'Donuts', 7, 2, 2),
(11, 'Cheesecakes', 7, 2, 3),
(12, 'Cinnamon Rolls', 7, 2, 4),
(13, 'Kunafa', 8, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_type` enum('delivery','pickup') NOT NULL,
  `note` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_boxes`
--

CREATE TABLE `order_boxes` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `box_price` decimal(10,2) NOT NULL,
  `box_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `product_price`, `product_image_url`, `category_id`, `is_available`) VALUES
(1, 'Tea', 'Traditional black tea', 15.00, 'images/tea.jpg', 4, 1),
(2, 'Green tea', 'Fresh green tea', 20.00, 'images/green tea.jpg', 4, 1),
(3, 'Espresso', 'Strong espresso shot', 50.00, 'images/Espresso.jpg', 4, 1),
(4, 'Americano', 'Espresso with hot water', 55.00, 'images/americano.jpg', 4, 1),
(5, 'Cappuccino', 'Espresso with steamed milk foam', 60.00, 'images/Cappuccino.jpg', 4, 1),
(6, 'Latte', 'Espresso with steamed milk', 65.00, 'images/Latte.jpg', 4, 1),
(7, 'Matcha Latte', 'Green tea matcha latte', 60.00, 'images/matcha latte.jpg', 4, 1),
(8, 'Mocha', 'Chocolate flavored latte', 55.00, 'images/Mocha.jpg', 4, 1),
(9, 'Turkish Coffee', 'Traditional Turkish coffee', 25.00, 'images/Turkish Coffee.jpg', 4, 1),
(10, 'French Coffee', 'French style coffee with cream', 35.00, 'images/french coffee.jpg', 4, 1),
(16, 'Iced Coffee', 'Cold brewed coffee', 45.00, 'images/iced coffee.jpg', 5, 1),
(17, 'Iced Latte', 'Cold latte with milk', 50.00, 'images/Iced Latte.jpg', 5, 1),
(18, 'Iced Mocha', 'Cold mocha with chocolate', 60.00, 'images/iced mocha.jpg', 5, 1),
(19, 'Frappé', 'Blended iced coffee', 60.00, 'images/Frappé.jpg', 5, 1),
(20, 'Iced Matcha', 'Cold green tea matcha', 65.00, 'images/iced matcha !.jpg', 5, 1),
(23, 'Vanilla Latte', 'Latte with vanilla syrup', 70.00, 'images/vanilla latte.jpg', 6, 1),
(24, 'Caramel Latte', 'Latte with caramel syrup', 65.00, 'images/Caramel latte.jpg', 6, 1),
(25, 'Chocolate Coffee', 'Coffee with chocolate flavor', 60.00, 'images/chocolate coffee.jpg', 6, 1),
(26, 'Tiramisu', 'ladyfinger biscuits, heavy cream, espresso coffee, cocoa powder, vanilla extract', 100.00, 'images/Tiramisu.jpg', 7, 1),
(27, 'Macaron', 'Almond flour, powdered sugar, eggs, granulated sugar, food coloring, buttercream filling', 20.00, 'images/Macaron.jpg', 7, 1),
(28, 'Honey cake', 'Flour, eggs, sugar, honey, butter, milk, vanilla extract, cream filling', 100.00, 'images/Honey cake.jpg', 7, 1),
(29, 'Eclairs', 'Flour, butter, eggs, milk, sugar, vanilla extract, pastry cream, chocolate ganache', 50.00, 'images/Eclairs.jpg', 7, 1),
(30, 'Creme caramel', 'Milk, Sugar, Eggs, Vanilla extract, Caramel', 80.00, 'images/Creme pana cotta.jpg', 7, 1),
(31, 'Classic cookies', 'Flour, Butter, Brown sugar, Vanilla extract, Chocolate chips', 20.00, 'images/Classic cookies.jpg', 7, 1),
(32, 'Chocolate cookies', 'Flour, Butter, Brown sugar, Cocoa powder, Chocolate chips', 30.00, 'images/Chocolate cookies.jpg', 7, 1),
(33, 'Blueberry Cheesecake', 'Digestive biscuits, Creme cheese, blueberry syrup', 70.00, 'images/blueberry Cheesecake.jpg', 11, 1),
(34, 'Strawberry Cheesecake', 'Digestive biscuits, Creme cheese, strawberry syrup', 70.00, 'images/strawberry Cheesecake.jpg', 11, 1),
(35, 'Oreo cheesecake', 'Digestive oreo biscuits, Creme cheese', 80.00, 'images/oreo cheesecake.jpg', 11, 1),
(36, 'Lemon Cheesecake', 'Digestive biscuits, Creme cheese, Lemon extract', 85.00, 'images/Lemon Cheesecake.jpg', 11, 1),
(37, 'Red velvet cheesecake', 'Digestive Red velvet cake, Creme cheese', 90.00, 'images/Red velvet cheesecake.jpg', 11, 1),
(40, 'Chocolate donut', 'Flour, sugar, eggs, butter, cocoa, chocolate', 50.00, 'images/chocolate donut.jpg', 10, 1),
(41, 'Caramel donut', 'Flour, sugar, eggs, butter, caramel, milk', 65.00, 'images/caramel donut.jpg', 10, 1),
(42, 'Oreo donut', 'Flour, sugar, eggs, butter, white chocolate, Oreo cookies', 70.00, 'images/oreo donut.jpg', 10, 1),
(43, 'Raspberry donut', 'Flour, sugar, eggs, butter, white chocolate, raspberry jam', 70.00, 'images/raspberry donut.jpg', 10, 1),
(44, 'Lotus donut', 'Flour, sugar, eggs, butter, Lotus spread, Lotus biscuits', 80.00, 'images/lotus donut.jpg', 10, 1),
(45, 'Kinder donut', 'Flour, sugar, eggs, butter, cream, kinder spread', 85.00, 'images/kinder donut.jpg', 10, 1),
(46, 'Pistachio donut', 'Flour, sugar, eggs, butter, cream, pistachio spread', 100.00, 'images/pistachio donut.jpg', 10, 1),
(47, 'Vanilla cupcake', 'cake, creme', 50.00, 'images/vanilla cupcake.jpg', 9, 1),
(48, 'Caramel cupcake', 'cake, caramel spread, creme', 60.00, 'images/caramel cupcake.jpg', 9, 1),
(49, 'Oreo cupcake', 'chocolate cake, oreo cookies, oreo creme', 65.00, 'images/oreo cupcake.jpg', 9, 1),
(50, 'Red velvet cupcake', 'red velvet cake, creme', 70.00, 'images/red velvet cupcake.jpg', 9, 1),
(51, 'Pistachio cupcake', 'cake, pistachio spread, creme', 80.00, 'images/pistachio cupcake.jpg', 9, 1),
(54, 'Classic cinnamon rolls', 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, cream cheese icing', 70.00, 'images/classic cinnamon.jpg', 12, 1),
(55, 'Caramel cinnamon rolls', 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, caramel syrup', 70.00, 'images/caramel cinnamon rolls.jpg', 12, 1),
(56, 'Oreo cinnamon rolls', 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, cream cheese icing, Oreo cookies', 80.00, 'images/oreo cinnamon.jpg', 12, 1),
(57, 'Lotus cinnamon rolls', 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, Lotus biscuits, Lotus spread', 90.00, 'images/lotus cinnamon.jpg', 12, 1),
(58, 'Red velvet cinnamon', 'red velvet cake, cream cheese icing', 90.00, 'images/red_velvet_cinnamon_rolls.jpg', 12, 1),
(59, 'Pistachio cinnamon rolls', 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, cream cheese icing, pistachio spread', 120.00, 'images/pistachio cinnamon.jpg', 12, 1),
(61, 'Vanilla Cake', 'Flour, sugar, eggs, butter, milk, vanilla, cream', 450.00, 'images/Vanilla Cake.jpg', 3, 1),
(62, 'Oreo Cake', 'Flour, sugar, eggs, butter, milk, Oreo, cream', 550.00, 'images/Oreo Cake.jpg', 3, 1),
(63, 'Chocolate Cake', 'Flour, sugar, eggs, butter, milk, chocolate, cream', 500.00, 'images/Chocolate Cake.jpg', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_comment`
--

CREATE TABLE `user_comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` enum('problem','review','complaint','question') NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boxes`
--
ALTER TABLE `boxes`
  ADD PRIMARY KEY (`box_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_boxes`
--
ALTER TABLE `order_boxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `box_id` (`box_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_name` (`product_name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boxes`
--
ALTER TABLE `boxes`
  MODIFY `box_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_boxes`
--
ALTER TABLE `order_boxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_comment`
--
ALTER TABLE `user_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_boxes`
--
ALTER TABLE `order_boxes`
  ADD CONSTRAINT `order_boxes_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_boxes_ibfk_2` FOREIGN KEY (`box_id`) REFERENCES `boxes` (`box_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD CONSTRAINT `user_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

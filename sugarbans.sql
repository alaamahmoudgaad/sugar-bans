-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 11:50 PM
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
  `box_price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `box_image_url` varchar(255) DEFAULT NULL,
  `products_included` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boxes`
--

INSERT INTO `boxes` (`box_id`, `box_name`, `box_price`, `description`, `box_image_url`, `products_included`, `is_available`) VALUES
(1, 'Besties Box', 250.00, '2 Donuts (Chocolate + Caramel) + 2 Cold Drinks (Iced Mocha / Iced Coffee)', 'images/besties box.jpg', 'Chocolate donut, Caramel donut, Iced Mocha, Iced Coffee', 1),
(2, 'Sweet Heaven', 250.00, 'Cheesecake slice + Tiramisu slice + 1 Cold Drink', 'images/sweet heaven.jpg', 'Cheesecake, Tiramisu, Iced Latte', 1),
(3, 'Sugar Rush Duo', 400.00, '2 Donuts + 2 Cupcakes + 2 Cold Drinks', 'images/sugar rush duo.jpg', 'Chocolate donut, Caramel donut, Vanilla cupcake, Caramel cupcake, Iced Mocha, Iced Coffee', 1),
(4, 'Honey Mood', 150.00, 'Honey Cake slice + Iced Latte', 'images/honey mood.jpg', 'Honey cake, Iced Latte', 1),
(5, 'Chill Combo', 200.00, 'Cupcake + Tiramisu slice + Cold Drink', 'images/chill combo.jpg', 'Vanilla cupcake, Tiramisu, Iced Latte', 1);

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
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_type` enum('delivery','pickup') NOT NULL,
  `note` text DEFAULT NULL
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
  `product_price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `product_image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `description`, `product_image_url`, `category_id`, `is_available`) VALUES
(2, 'Green tea', 20.00, 'Fresh green tea', 'images/green tea.jpg', 4, 1),
(3, 'Espresso', 50.00, 'Strong espresso shot', 'images/Espresso.jpg', 4, 1),
(4, 'Americano', 55.00, 'Espresso with hot water', 'images/americano.png', 4, 1),
(5, 'Cappuccino', 60.00, 'Espresso with steamed milk foam', 'images/Cappuccino.jpg', 4, 1),
(6, 'Latte', 65.00, 'Espresso with steamed milk', 'images/Latte.jpg', 4, 1),
(7, 'Matcha Latte', 60.00, 'Green tea matcha latte', 'images/matcha latte.jpg', 4, 1),
(8, 'Mocha', 55.00, 'Chocolate flavored latte', 'images/Mocha.jpg', 4, 1),
(9, 'Turkish Coffee', 25.00, 'Traditional Turkish coffee', 'images/Turkish Coffee.jpg', 4, 1),
(10, 'French Coffee', 35.00, 'French style coffee with cream', 'images/french coffee.jpg', 4, 1),
(16, 'Iced Coffee', 45.00, 'Cold brewed coffee', 'images/iced coffee.jpg', 5, 1),
(17, 'Iced Latte', 50.00, 'Cold latte with milk', 'images/Iced Latte.jpg', 5, 1),
(18, 'Iced Mocha', 60.00, 'Cold mocha with chocolate', 'images/iced mocha.jpg', 5, 1),
(19, 'Frappé', 60.00, 'Blended iced coffee', 'images/Frappé.png', 5, 1),
(20, 'Iced Matcha', 65.00, 'Cold green tea matcha', 'images/iced matcha !.jpg', 5, 1),
(23, 'Vanilla Latte', 70.00, 'Latte with vanilla syrup', 'images/vanilla latte.jpg', 6, 1),
(24, 'Caramel Latte', 65.00, 'Latte with caramel syrup', 'images/Caramel Latte.jpg', 6, 1),
(25, 'Chocolate Coffee', 60.00, 'Coffee with chocolate flavor', 'images/chocolate coffee.jpg', 6, 1),
(26, 'Tiramisu', 100.00, 'ladyfinger biscuits, heavy cream, espresso coffee, cocoa powder, vanilla extract', 'images/Tiramisu.jpg', 7, 1),
(27, 'Macaron', 20.00, 'Almond flour, powdered sugar, eggs, granulated sugar, food coloring, buttercream filling', 'images/Macaron.jpg', 7, 1),
(28, 'Honey cake', 100.00, 'Flour, eggs, sugar, honey, butter, milk, vanilla extract, cream filling', 'images/Honey cake.jpg', 7, 1),
(29, 'Eclairs', 50.00, 'Flour, butter, eggs, milk, sugar, vanilla extract, pastry cream, chocolate ganache', 'images/Eclairs.jpg', 7, 1),
(30, 'Creme caramel', 80.00, 'Milk, Sugar, Eggs, Vanilla extract, Caramel', 'images/Creme pana cotta.png', 7, 1),
(31, 'Classic cookies', 20.00, 'Flour, Butter, Brown sugar, Vanilla extract, Chocolate chips', 'images/Classic cookies.jpg', 7, 1),
(32, 'Chocolate cookies', 30.00, 'Flour, Butter, Brown sugar, Cocoa powder, Chocolate chips', 'images/Chocolate cookies.jpg', 7, 1),
(33, 'Blueberry Cheesecake', 70.00, 'Digestive biscuits, Creme cheese, blueberry syrup', 'images/blueberry Cheesecake.jpg', 11, 1),
(34, 'Strawberry Cheesecake', 70.00, 'Digestive biscuits, Creme cheese, strawberry syrup', 'images/strawberry Cheesecake.jpg', 11, 1),
(35, 'Oreo cheesecake', 80.00, 'Digestive oreo biscuits, Creme cheese', 'images/oreo cheesecake.jpg', 11, 1),
(36, 'Lemon Cheesecake', 85.00, 'Digestive biscuits, Creme cheese, Lemon extract', 'images/Lemon Cheesecake.jpg', 11, 1),
(37, 'Red velvet cheesecake', 90.00, 'Digestive Red velvet cake, Creme cheese', 'images/Red velvet cheesecake.jpg', 11, 1),
(40, 'Chocolate donut', 50.00, 'Flour, sugar, eggs, butter, cocoa, chocolate', 'images/chocolate donut.jpg', 10, 1),
(41, 'Caramel donut', 65.00, 'Flour, sugar, eggs, butter, caramel, milk', 'images/caramel donut.jpg', 10, 1),
(42, 'Oreo donut', 70.00, 'Flour, sugar, eggs, butter, white chocolate, Oreo cookies', 'images/oreo donut.jpg', 10, 1),
(43, 'Raspberry donut', 70.00, 'Flour, sugar, eggs, butter, white chocolate, raspberry jam', 'images/raspberry donut.jpg', 10, 1),
(44, 'Lotus donut', 80.00, 'Flour, sugar, eggs, butter, Lotus spread, Lotus biscuits', 'images/lotus donut.jpg', 10, 1),
(45, 'Kinder donut', 85.00, 'Flour, sugar, eggs, butter, cream, kinder spread', 'images/kinder donut.jpg', 10, 1),
(46, 'Pistachio donut', 100.00, 'Flour, sugar, eggs, butter, cream, pistachio spread', 'images/pistachio donut.jpg', 10, 1),
(47, 'Vanilla cupcake', 50.00, 'cake, creme', 'images/vanilla cupcake.jpg', 9, 1),
(48, 'Caramel cupcake', 60.00, 'cake, caramel spread, creme', 'images/caramel cupcake.jpg', 9, 1),
(49, 'Oreo cupcake', 65.00, 'chocolate cake, oreo cookies, oreo creme', 'images/oreo cupcake.jpg', 9, 1),
(50, 'Red velvet cupcake', 70.00, 'red velvet cake, creme', 'images/red velvet cupcake.jpg', 9, 1),
(51, 'Pistachio cupcake', 80.00, 'cake, pistachio spread, creme', 'images/pistachio cupcake.jpg', 9, 1),
(54, 'Classic cinnamon rolls', 70.00, 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, cream cheese icing', 'images/classic cinnamon.jpg', 12, 1),
(55, 'Caramel cinnamon rolls', 70.00, 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, caramel syrup', 'images/caramel cinnamon rolls.jpg', 12, 1),
(56, 'Oreo cinnamon rolls', 80.00, 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, cream cheese icing, Oreo cookies', 'images/oreo cinnamon.jpg', 12, 1),
(57, 'Lotus cinnamon rolls', 90.00, 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, Lotus biscuits, Lotus spread', 'images/lotus cinnamon.jpg', 12, 1),
(58, 'Red velvet cinnamon', 90.00, 'red velvet cake, cream cheese icing', 'images/red_velvet_ cinnamon_rolls.jpg', 12, 1),
(59, 'Pistachio cinnamon rolls', 120.00, 'Flour, butter, brown sugar, eggs, milk, cinnamon powder, vanilla extract, cream cheese icing, pistachio spread', 'images/pistachio cinnamon.jpg', 12, 1),
(61, 'Vanilla Cake', 450.00, 'Flour, sugar, eggs, butter, milk, vanilla, cream', 'images/Vanilla Cake.jpg', 3, 1),
(62, 'Oreo Cake', 550.00, 'Flour, sugar, eggs, butter, milk, Oreo, cream', 'images/Oreo Cake.jpg', 3, 1),
(63, 'Chocolate Cake', 500.00, 'Flour, sugar, eggs, butter, milk, chocolate, cream', 'images/Chocolate Cake.jpg', 3, 1);

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `role`) VALUES
(1, 'Alaa', 'Mahmoud', 'alaa@gmail.com', '$2y$10$gMsZvhz5iP.DaZ2I0JaGnORux18hQNc/tnrsvzTFS3bQggYNnlRdi', '01022233346', 'suez', 'admin'),
(2, 'Nourhan', 'Hany', 'nourhan@gmail.com', '$2y$10$i3nE.NZN2p.a7va84P6ZTOlaFWJjBqo63ZB1OGo5EMXGe.9FXF282', '8697024709768', 'suez', 'admin'),
(4, 'Bassant', 'Hamdy', 'bassant@gmail.com', '$2y$10$WPIYRHnE.O907UA6gGPq.OglOHtUDsl9gCSxQuH6qKqM3FJBwe0Pq', '8697024709768', 'suez', 'admin'),
(5, 'Sama', 'Ahmed', 'sama@gmail.com', '$2y$10$Cqhe8bd0DrJcNLJ7VUYb.u/o6.DoxPexFqgiIL/nZ.pZF5cvOE.I6', '8697024709768', 'suez', 'admin'),
(6, 'Ali', 'Hany', 'Ali@gmail.com', '$2y$10$WLzPVKtzrtUVILHh33Izt.s4J1JPI4p5Kj8zDVKoMLAO3GsX6l/JG', '8697024709768', 'suez', 'customer'),
(7, 'youssef', 'Mohamed', 'youssef@gmail.com', '$2y$10$1zKBfdMvzepK5i2RUb5SKODT3WZFPWChqaeddGAnTVgE4NGwGiT0S', '8697024709768', 'suez', 'customer'),
(8, 'Ahmed', 'Mansour', 'ahmed8@gmail.com', '$2y$10$zWoijsCP4xoXPBfDHoG6WuxsyAufzj1H5Aj5MPwzJpqExsPb12Soi', '01012345678', 'Suez', 'customer'),
(9, 'Mona', 'Zaki', 'mona9@gmail.com', '$2y$10$k6slQT/lGQqHY9U2ZqB0hORK4bcW0n9JJxdV9/H4BhzkmObohmlWe', '01122334455', 'Cairo', 'customer'),
(10, 'Hassan', 'Farid', 'hassan10@gmail.com', '$2y$10$GdRIIoJ9Bq84Yn3Oyo2qLu9C.SgSHYf3fEH.BFm4xt/H5KWiYQEDS', '01233445566', 'Alexandria', 'customer'),
(11, 'Laila', 'Ezzat', 'laila11@gmail.com', '$2y$10$/.HC3KKUUBlri6055YLn7uiCgWDMINChEzoEh.LdoIFj9j3AjdoLW', '01555667788', 'Giza', 'customer'),
(12, 'Youssef', 'Salem', 'youssef12@gmail.com', '$2y$10$KWQA5ontHo6MKVb56F7ps.jPzh4JS5Igjldu3pUIVi/8kszYEYjdy', '01099887766', 'Mansoura', 'customer'),
(13, 'Amira', 'Kamal', 'amira13@gmail.com', '$2y$10$D/MPfc.bZIutzuyMeVzkTObmOkTVuJn3qhXW2kMdMLuW4MDodD8pm', '01144556677', 'Tanta', 'customer'),
(14, 'Khaled', 'Anwar', 'khaled14@gmail.com', '$2y$10$ayFItznPvkHCEv8trRqJ6uGOOfbnc58WssOf44odUyOUaAHZ0/pfS', '01200998877', 'Suez', 'customer'),
(15, 'Sama', 'Khalil', 'sama15@gmail.com', '$2y$10$ixxfw4L5nNhmAXxfWtK1uuIOVSVvCulKG2/YIDm9UPbMKFg6EXyQe', '01011223344', 'Cairo', 'customer'),
(16, 'Omar', 'Nagi', 'omar16@gmail.com', '$2y$10$6ZNwIKXksg/1RDGBF3Dfdet2JfpK27xFhg.l9tlaEuIy6zXUKlgJO', '01155667744', 'Port Said', 'customer'),
(17, 'Fatma', 'Ali', 'fatma17@gmail.com', '$2y$10$lZUbfLs6vhe9LLERM9aWMezPISlbfZCGM.3r.rDey8f1Tl0.6Ywda', '01288776655', 'Ismailia', 'customer'),
(18, 'Ziad', 'Hamed', 'ziad18@gmail.com', '$2y$10$zZeevCnhzdfbcfQL9zmJk.J77PTiKhIIJNLYO3SUL1VWiuL2SJ2bO', '01033445566', 'Suez', 'customer'),
(19, 'Nour', 'Elsayed', 'nour19@gmail.com', '$2y$10$cpw.jjLUBoiLH9C29aHCYeiWVI9ce0aSQL5YzO3bnfG.35h66i15W', '01166778899', 'Alexandria', 'customer'),
(20, 'Mostafa', 'Badr', 'mostafa20@gmail.com', '$2y$10$haVAPT9u.RrrMyw7BFIGi.EBnJQgFLCJOc1fIECQ9eJpg3lGuA2pK', '01244556633', 'Cairo', 'customer'),
(21, 'Hany', 'Al-Sayed', 'hany.sayed@gmail.com', '$2y$10$2DOraU2Dn0KrEza8FYV8suWQsnQ4uNm2vCrLRRTOi1pspvyupHOqe', '01012344321', 'Suez', 'customer'),
(22, 'Rawan', 'Mahmoud', 'rawan.m@gmail.com', '$2y$10$9EA7Jn2sWX2O33RPypr5x.V/v1raNngjTJUKpXFkagbxHloydJNo2', '01122339988', 'Cairo', 'customer'),
(23, 'Ehab', 'Tawfik', 'ehab.t@gmail.com', '$2y$10$awDeeOMNav0I7q96sx2OTOQuOn2E8Z3JpnUQaQtcKzRSnpFnPLWtK', '01233448877', 'Alexandria', 'customer'),
(24, 'Sohair', 'Ramzy', 'sohair.r@gmail.com', '$2y$10$wuEskjUz1uyEWWqwVAze1eAdE6n/qtbYRmyA2uPbWq5tHA99R9TiK', '01555661122', 'Giza', 'customer'),
(25, 'Walid', 'Soliman', 'walid.s@gmail.com', '$2y$10$mxBdkPIV8hQo7vVvu0IVV.K.SfX6K/s9KbpPbz4QunOLKkqa7BoPy', '01099882233', 'Mansoura', 'customer'),
(26, 'Nada', 'Kamal', 'nada.k@gmail.com', '$2y$10$lTA3Swnp5.WuxQvX6y1cJu3vvPBWVTLCbisOekJCxCsTt86pjsbCe', '01144553344', 'Tanta', 'customer'),
(27, 'Bassem', 'Samra', 'bassem.s@gmail.com', '$2y$10$5VX/adWgoMNOkINO3.WbreI7y3b2A/qF1ZyXGB42CJfzbjKVwn2p6', '01200994455', 'Suez', 'customer'),
(28, 'Yasmin', 'Sabry', 'yasmin.s@gmail.com', '$2y$10$sVrP8QXVz/jJI4ykYXkUDeh7l8R7sUrchfSnKNoP3T2mmB3WXtl3O', '01011225566', 'Cairo', 'customer'),
(29, 'Sherif', 'Mounir', 'sherif.m@gmail.com', '$2y$10$i.clqaSqExgmluVMII5EDuZvtey4FUww2JiJwOuYDi3F.S62USZka', '01155666677', 'Port Said', 'customer'),
(30, 'Heba', 'Magdy', 'heba.m@gmail.com', '$2y$10$Z.Gu8Alkc4aUSxBqWSy3FenoPWlMyF0fZwpDC6Bk9j2CCeMEkX6KW', '01288777788', 'Ismailia', 'customer'),
(31, 'Karim', 'Abdelaziz', 'karim.a@gmail.com', '$2y$10$nkiG40lygJpnir0tTJqHcO89sgCVSy7X2gznO7YnF9/tWUQEpWeYe', '01033441199', 'Suez', 'customer'),
(32, 'Reem', 'Mostafa', 'reem.m@gmail.com', '$2y$10$X/eo8AQUecI9vxU30pMJBumVFIzb3RgGdorhFEp/jkfgkuQLv8WYq', '01166772200', 'Alexandria', 'customer'),
(33, 'Tamer', 'Hosny', 'tamer.h@gmail.com', '$2y$10$fnx51y.9wD9/taZo56Jje.Gnc7DS5dS3Vum/2NYvCnezNWHHhtlTC', '01244553311', 'Cairo', 'customer'),
(34, 'Aya', 'Ibrahim', 'aya.i@gmail.com', '$2y$10$ozW0LRBuVrgorgcm/JII7.N.aIkZ46.uEw40OlRdQ2zHi4NnmzZb.', '01055444422', 'Giza', 'customer'),
(35, 'Adel', 'Imam', 'adel.i@gmail.com', '$2y$10$kYZCKyg4eMXjoDSEzSUCPuUTYbkj1.kkGYWtVT1g.cQCqN7RzLwCq', '01144335533', 'Suez', 'customer'),
(36, 'Mona', 'Shalaby', 'mona.s@gmail.com', '$2y$10$4HTQ/7TnBCfG8E3QgcSD5.kPxBRFf9l3ACcmp3WbCgND1KsXdQsqW', '01222336644', 'Mansoura', 'customer'),
(37, 'Ahmed', 'Helmy', 'ahmed.h@gmail.com', '$2y$10$UTn5k39wotBcwm7934Twq.3ZtUccjDDzBucAVnoC6fCBOn6Zxvkiq', '01599887755', 'Cairo', 'customer'),
(38, 'Donia', 'Samir', 'donia.s@gmail.com', '$2y$10$wY/IzLw.eVkAnXr0UHiWxOz40kgMWRp3lMDVN3N2GfctqhBZd6UGK', '01066778866', 'Alexandria', 'customer'),
(39, 'Mahmoud', 'El-Leithy', 'mahmoud.l@gmail.com', '$2y$10$YNOpL7WOKGdjfAcc5NsBN.OwF6idxZJNpi7/LTvu57jFKOkQkksuq', '01122119977', 'Suez', 'customer'),
(40, 'Ghada', 'Adel', 'ghada.a@gmail.com', '$2y$10$FeVivATWnv0Do9AOeJ.RCeOa6vXWSt.e257icdyzIlhzhXQdmdDka', '01233441188', 'Port Said', 'customer'),
(41, 'Samy', 'Abdelhadi', 'samy.h@gmail.com', '$2y$10$Ffs8n7mkEkeEASHXn.PPQeS8J562mgeBomw2lkH3lCKb0DM4mg6/W', '01011122233', 'Suez', 'customer'),
(42, 'Naglaa', 'Badr', 'naglaa.b@gmail.com', '$2y$10$YIQEEunQAoXj2yRzr1GLiuq8pu9c0CinrJkgDf1ORcyeBeIKO/jCC', '01122233344', 'Cairo', 'customer'),
(43, 'Yasser', 'Galal', 'yasser.g@gmail.com', '$2y$10$85fJFXH66cfNIZwx/pzmZOo/apW/I4ctcEwRkYJRWyCogRupnWpc2', '01233344455', 'Alexandria', 'customer'),
(44, 'Inas', 'Gohar', 'inas.g@gmail.com', '$2y$10$odtl7dFOHH6eIRXHVnabLON8luQS0ov.BnUGZ78fou2LVw/.jleeW', '01544455566', 'Giza', 'customer'),
(45, 'Ashraf', 'Zaki', 'ashraf.z@gmail.com', '$2y$10$Q7DxvTSmraalSd.iXFWHuugdyc5sn6nKuuY6OPcCrZq9x0AgNTT/e', '01055566677', 'Mansoura', 'customer'),
(46, 'Salma', 'Abu-Deif', 'salma.a@gmail.com', '$2y$10$NJrS23PW.e8FWLyw5lyXl.w6Z1t83xRt4LPYkixFHzM1GH9VTc0/q', '01166677788', 'Tanta', 'customer'),
(47, 'Maged', 'El-Kedwany', 'maged.k@gmail.com', '$2y$10$nIxKqk4lA4.0rFADo/vRbuef2gaZCnGjxsWe2rydQUEleajfGvUBC', '01277788899', 'Suez', 'customer'),
(48, 'Dina', 'El-Sherbiny', 'dina.s@gmail.com', '$2y$10$i0MXNaNu5rilAXNKuwYfm.Pv8XPIEPvFh6BppuED6G6yShoHkrONC', '01088899900', 'Cairo', 'customer'),
(49, 'Ahmed', 'Dawood', 'ahmed.d@gmail.com', '$2y$10$/w6Bvse0VapxohBhK7BFTeWYayAk.J21DdgSXlK/Jh5fLx08O/FR2', '01199900011', 'Port Said', 'customer'),
(50, 'Tara', 'Emad', 'tara.e@gmail.com', '$2y$10$DxZylswhveCIZ8M992yx9.tNS8EX8W2r8ZmpuuNGMRYMLPGj7eD9G', '01200011122', 'Ismailia', 'customer'),
(51, 'Mohamed', 'Mamdouh', 'm.mamdouh@gmail.com', '$2y$10$kYa6SwyRU7Nz9tRBzFgwkOgpdijr1rz7HflBUcnUwEeLi8/vN2M7u', '01011133355', 'Suez', 'customer'),
(52, 'Asser', 'Yassin', 'asser.y@gmail.com', '$2y$10$UGY8DUVJ4HwpE3NcHzYi4OMjkPyC6Od9fvLEYCxpmiTKSwXn2EeSe', '01122244466', 'Alexandria', 'customer'),
(53, 'Nelly', 'Karim', 'nelly.k@gmail.com', '$2y$10$QIS/1ulvOLxGJ6ntWvh/dezmjJ3s9hbCDnUvdutoTgzDqGzspF8m.', '01233355577', 'Cairo', 'customer'),
(54, 'Amir', 'Karara', 'amir.k@gmail.com', '$2y$10$MUpECixuA5ozh/.50eOeiO8GB.qmMmD/2cyvfSIWIm2w5s16rGcU6', '01044466688', 'Giza', 'customer'),
(55, 'Ruby', 'Ahmed', 'ruby.a@gmail.com', '$2y$10$Ko3yWlTYLiRss7zC4Wc1weBiUzZILfHp8563eKvP9aLAtlVTSF2Cq', '01155577799', 'Suez', 'customer'),
(56, 'Akram', 'Hosny', 'akram.h@gmail.com', '$2y$10$02w6iq85tVuRsEJAb5lijueczIP8ZV9J5tVKokA9jwSoblPm3x0Ae', '01266688800', 'Mansoura', 'customer'),
(57, 'Eyad', 'Nassar', 'eyad.n@gmail.com', '$2y$10$Tq022pbfOT8/rdrV1qWom.aS/fA4CIaZeuZu5RBGxqhArjidpBNpu', '01577799911', 'Cairo', 'customer'),
(59, 'Rogena', 'Amin', 'rogena.a@gmail.com', '$2y$10$BCH5HueWpTm1Go5rUpOAZugAoJ8Xv7uc/x.Xe8jUNuYx32hhXv9wy', '01199911133', 'Suez', 'customer'),
(60, 'Bassem', 'Youssef', 'bassem.y@gmail.com', '$2y$10$J8IW4mg2wCUqJH3bEeysu.7U7ySWYZ8zenJgFsqy3ZVyEEYFCzEUa', '01200022244', 'Port Said', 'customer'),
(81, 'Nermin', 'El-Feki', 'nermin.feki@gmail.com', '$2y$10$P2LASMM5VUyD6I/I4aWdkOyInvaN1VxaC.5K0bConq2GHNXila52K', '01011155599', 'Suez', 'customer'),
(82, 'Hazem', 'Emam', 'hazem.e@gmail.com', '$2y$10$Ii6VT8Izrm.yH7PevBgcXO63W5a3QAJ8zmhcQgYN63Dmk96EZXxa6', '01122266600', 'Cairo', 'customer'),
(83, 'Lina', 'Shamamy', 'lina.s@gmail.com', '$2y$10$VV3i3UYvSjaIbVd1OvJ3ieKgBDqLoKCUZh0gm6HcdOxd36f2ZMg4W', '01233377711', 'Alexandria', 'customer'),
(84, 'Karim', 'Fahmy', 'karim.f@gmail.com', '$2y$10$S/lt3jV/tH.a7lh4ajIhJuCvUO6iyM9ZQTi9EuSdyePMp2jx5ipQG', '01544488822', 'Giza', 'customer'),
(85, 'Sohad', 'Al-Alfy', 'sohad.a@gmail.com', '$2y$10$41dhPmXj2soALI4SjRTGAOXLS1.eJ/acRVWOnwM52tfZBvf08k0RK', '01055599933', 'Mansoura', 'customer'),
(86, 'Ramy', 'Radwan', 'ramy.r@gmail.com', '$2y$10$VRYEgf0GnR6DfSmS3oa1Ne0IWu1dA/bWs/rqymJvgofZDsW7jm0ca', '01166600044', 'Tanta', 'customer'),
(88, 'Ahmed', 'Sakka', 'ahmed.s@gmail.com', '$2y$10$grxizedRGcoLYwtdRBqX1uyII0.6CpEoEWceW0TlPAv191dHlgHqW', '01088822266', 'Cairo', 'customer'),
(90, 'Hany', 'Adel', 'hany.a@gmail.com', '$2y$10$LJPLvZWA9aS0.b6yykpmre8dan6x4N9JBz8lR9o9WJ2zviWaHlz4C', '01200044488', 'Ismailia', 'customer'),
(91, 'Bushra', 'Ahmed', 'bushra.a@gmail.com', '$2y$10$M1RKPV5HYJGYXznqnDGH1utkKrnfYq5PCPbVHOarjuD8XaPJYU36W', '01011166600', 'Suez', 'customer'),
(92, 'Amir', 'Eid', 'amir.e@gmail.com', '$2y$10$aVgJyFku5PS0WmY8XrkpUuyDsarrkCSrVmr2mbX1.ExoWxc6JxJkC', '01122277711', 'Alexandria', 'customer'),
(93, 'Mirna', 'Nour', 'mirna.n@gmail.com', '$2y$10$GMcwi292YRu8eJpOhMN/MOklOyT2k3I4FoaTafxXWkuxP554JvMHy', '01233388822', 'Cairo', 'customer'),
(94, 'Basma', 'Hassan', 'basma.h@gmail.com', '$2y$10$TrFwegX3ccGw8vDuAr7mOu06Dq53CjieGOgi7FFrSZqCzzPqdVzou', '01044499933', 'Giza', 'customer'),
(95, 'Eyad', 'Badr', 'eyad.b@gmail.com', '$2y$10$9YHFUntfic.aBHGMwvY8tOQFXR0pSE31Sb3cDH3tE3NLQ5JKHnsAa', '01155500044', 'Suez', 'customer'),
(96, 'Yara', 'Naoum', 'yara.n@gmail.com', '$2y$10$aRXk60pr5psxseQRWc1Qcuwm3G428Yl0LltdsEHoVALxGW2oQBfGu', '01266611155', 'Mansoura', 'customer');

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

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

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 04:14 PM
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
-- Database: `the_overcooked_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentId` int(11) NOT NULL,
  `commentContent` varchar(100) NOT NULL,
  `commentDatetime` datetime NOT NULL DEFAULT current_timestamp(),
  `userId` int(11) NOT NULL,
  `recipeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentId`, `commentContent`, `commentDatetime`, `userId`, `recipeId`) VALUES
(3, 'Sedapnya! Saya cuba buat tadi, memang terbaik!', '2024-02-10 10:15:00', 5, 21),
(4, 'Absolutely delicious! My family loved it.', '2024-02-10 11:30:00', 6, 21),
(5, 'Resipi ini sangat mudah diikuti. Terima kasih!', '2024-02-09 09:45:00', 7, 22),
(6, 'Tried this today, and it turned out amazing!', '2024-02-09 10:20:00', 8, 22),
(7, 'Saya suka bagaimana rasa rempahnya seimbang.', '2024-02-08 14:00:00', 9, 23),
(8, 'Great recipe! Will definitely make this again.', '2024-02-08 15:10:00', 10, 23),
(9, 'Memang sedap! Patut cuba dengan keluarga.', '2024-02-07 17:25:00', 11, 24),
(10, 'Fantastic dish! So full of flavor.', '2024-02-07 18:40:00', 12, 24),
(11, 'Senang dan cepat untuk dibuat. Syabas!', '2024-02-06 19:55:00', 13, 25),
(12, 'I followed the steps, and it turned out perfect.', '2024-02-06 20:30:00', 14, 25),
(13, 'Resipi ini benar-benar mengingatkan saya kepada zaman kecil.', '2024-02-05 08:10:00', 15, 26),
(14, 'Amazing! The taste was just like in the restaurant.', '2024-02-05 09:20:00', 16, 26),
(15, 'Wow, ini sangat sedap! Terima kasih kongsi resipi!', '2024-02-04 12:45:00', 17, 27),
(16, 'This was so easy to make and super tasty!', '2024-02-04 13:30:00', 18, 27),
(17, 'Mudah dan menyelerakan! Saya suka!', '2024-02-03 16:05:00', 19, 28),
(18, 'Best recipe ever! Will cook again for sure.', '2024-02-03 17:15:00', 5, 28),
(19, 'Saya buat untuk keluarga, semua suka!', '2024-02-02 10:50:00', 6, 29),
(20, 'Love how simple and delicious this is.', '2024-02-02 11:30:00', 7, 29),
(21, 'Resipi ini memang kena dengan selera saya.', '2024-02-01 08:20:00', 8, 30),
(22, 'Turned out fantastic! So rich in flavor.', '2024-02-01 09:10:00', 9, 30),
(23, 'Tak sangka semudah ini! Terima kasih!', '2024-01-31 14:50:00', 10, 31),
(24, 'Loved every bite of this dish.', '2024-01-31 15:35:00', 11, 31),
(25, 'Resipi yang hebat! Sangat disyorkan.', '2024-01-30 12:15:00', 12, 32),
(26, 'So good! Thank you for sharing this.', '2024-01-30 13:00:00', 13, 32),
(27, 'Saya akan buat ini lagi. Rasa terbaik!', '2024-01-29 17:25:00', 14, 33),
(28, 'One of the best recipes I have tried!', '2024-01-29 18:40:00', 15, 33),
(29, 'Makan ini memang menyelerakan! Terima kasih.', '2024-01-28 19:30:00', 16, 34),
(30, 'Loved the flavors! A must-try.', '2024-01-28 20:10:00', 17, 34),
(31, 'Ini adalah resipi yang saya akan buat berulang kali.', '2024-01-27 08:10:00', 18, 35),
(32, 'Simple yet so flavorful. Thanks for sharing!', '2024-01-27 09:00:00', 19, 35);

-- --------------------------------------------------------

--
-- Table structure for table `pending_recipe`
--

CREATE TABLE `pending_recipe` (
  `pendingId` int(11) NOT NULL,
  `originalId` int(11) NOT NULL,
  `recipeName` varchar(255) NOT NULL,
  `chefId` int(11) NOT NULL,
  `category` enum('Breakfast','Lunch','Dinner','Dessert','Beverages') NOT NULL,
  `tag` text DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `instruction` text DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `rejection_reason` text DEFAULT NULL,
  `status` enum('pending','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_recipe`
--

INSERT INTO `pending_recipe` (`pendingId`, `originalId`, `recipeName`, `chefId`, `category`, `tag`, `picture`, `note`, `details`, `ingredients`, `instruction`, `submitted_at`, `rejection_reason`, `status`) VALUES
(116, 1, 'Nasi Lemak', 27, 'Breakfast', 'Malaysian, Traditional', 'uploads/nasi_lemak.jpg', 'A classic Malaysian dish.', 'Prep time: 15 min, Cook time: 30 min', 'Rice, Coconut Milk, Pandan Leaves, Anchovies, Peanuts, Egg, Sambal', '1. Cook rice with coconut milk. 2. Fry anchovies and peanuts. 3. Serve with sambal and egg.', '2024-02-10 08:15:00', NULL, 'pending'),
(117, 2, 'Roti Canai', 28, 'Breakfast', 'Malaysian, Street Food', 'uploads/roti_canai.jpg', 'Popular flaky flatbread.', 'Prep time: 20 min, Cook time: 10 min', 'Flour, Water, Ghee, Salt', '1. Knead dough and let it rest. 2. Stretch and fold. 3. Cook on a hot pan.', '2024-02-09 07:30:00', NULL, 'pending'),
(118, 3, 'Ayam Percik', 29, 'Lunch', 'Malaysian, Grilled', 'uploads/ayam_percik.jpg', 'Spiced grilled chicken with coconut sauce.', 'Prep time: 10 min, Cook time: 40 min', 'Chicken, Coconut Milk, Lemongrass, Chili', '1. Marinate chicken with spices. 2. Grill while basting with coconut sauce.', '2024-02-08 12:45:00', NULL, 'pending'),
(119, 4, 'Laksa Johor', 30, 'Lunch', 'Malaysian, Noodle Soup', 'uploads/laksa_johor.jpg', 'A Johorean take on laksa with spaghetti.', 'Prep time: 30 min, Cook time: 45 min', 'Spaghetti, Fish, Coconut Milk, Curry Powder', '1. Cook fish broth. 2. Mix with coconut milk and spices. 3. Serve with spaghetti.', '2024-02-07 13:10:00', 'Ingredients missing in the description.', 'rejected'),
(120, 5, 'Ikan Bakar', 31, 'Dinner', 'Malaysian, Grilled', 'uploads/ikan_bakar.jpg', 'Charcoal-grilled fish with spices.', 'Prep time: 15 min, Cook time: 25 min', 'Fish, Turmeric, Salt, Banana Leaves', '1. Marinate fish with turmeric and salt. 2. Wrap in banana leaves and grill.', '2024-02-06 19:20:00', NULL, 'pending'),
(121, 6, 'Sambal Udang', 27, 'Dinner', 'Malaysian, Spicy', 'uploads/sambal_udang.jpg', 'Prawns cooked in spicy sambal.', 'Prep time: 10 min, Cook time: 15 min', 'Prawns, Chili Paste, Tamarind, Onion', '1. Sauté onion and chili paste. 2. Add prawns and cook until done.', '2024-02-05 20:10:00', 'Lacks cooking time details.', 'rejected'),
(122, 7, 'Kuih Lapis', 28, 'Dessert', 'Malaysian, Layered', 'uploads/kuih_lapis.jpg', 'Colorful layered steamed cake.', 'Prep time: 20 min, Cook time: 40 min', 'Rice Flour, Coconut Milk, Sugar, Food Coloring', '1. Mix rice flour and coconut milk. 2. Layer and steam in stages.', '2024-02-04 15:30:00', NULL, 'pending'),
(123, 8, 'Cendol', 29, 'Dessert', 'Malaysian, Sweet', 'uploads/cendol.jpg', 'Refreshing dessert with shaved ice.', 'Prep time: 15 min, Cook time: 5 min', 'Cendol, Coconut Milk, Palm Sugar, Ice', '1. Boil palm sugar syrup. 2. Serve with cendol and coconut milk over ice.', '2024-02-03 16:05:00', NULL, 'pending'),
(124, 9, 'Teh Tarik', 30, 'Beverages', 'Malaysian, Tea', 'uploads/teh_tarik.jpg', 'Famous pulled milk tea.', 'Prep time: 5 min, Cook time: 5 min', 'Black Tea, Condensed Milk, Hot Water', '1. Brew black tea. 2. Mix with condensed milk. 3. Pull to create foam.', '2024-02-02 10:50:00', NULL, 'pending'),
(125, 10, 'Bandung Syrup', 31, 'Beverages', 'Malaysian, Sweet Drink', 'uploads/bandung.jpg', 'Rose-flavored milk drink.', 'Prep time: 5 min, Cook time: 0 min', 'Rose Syrup, Condensed Milk, Water, Ice', '1. Mix rose syrup with condensed milk. 2. Add ice and serve chilled.', '2024-02-01 11:15:00', 'Recipe does not specify milk type.', 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `posted_recipe`
--

CREATE TABLE `posted_recipe` (
  `recipeId` int(11) NOT NULL,
  `recipeName` varchar(255) NOT NULL,
  `chefId` int(11) NOT NULL,
  `category` enum('Breakfast','Lunch','Dinner','Dessert','Beverages') NOT NULL,
  `tag` text NOT NULL,
  `picture` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `ingredients` text NOT NULL,
  `instruction` text NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `datetime_posted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posted_recipe`
--

INSERT INTO `posted_recipe` (`recipeId`, `recipeName`, `chefId`, `category`, `tag`, `picture`, `note`, `details`, `ingredients`, `instruction`, `approved`, `datetime_posted`) VALUES
(21, 'Lempeng Kelapa', 27, 'Breakfast', 'Malaysian, Pancake', 'uploads/lempeng_kelapa.jpg', 'Traditional coconut pancake.', 'Prep time: 10 min, Cook time: 5 min', 'Flour, Coconut, Sugar, Water, Salt', '1. Mix all ingredients into a batter. 2. Cook on a flat pan until golden brown.', 1, '2024-02-10 07:45:00'),
(22, 'Bubur Lambuk', 28, 'Breakfast', 'Malaysian, Porridge', 'uploads/bubur_lambuk.jpg', 'Savory rice porridge popular during Ramadan.', 'Prep time: 20 min, Cook time: 40 min', 'Rice, Chicken, Coconut Milk, Spices', '1. Cook rice with water and spices. 2. Add chicken and coconut milk. 3. Simmer until porridge-like consistency.', 1, '2024-02-09 08:15:00'),
(23, 'Telur Goyang', 29, 'Breakfast', 'Malaysian, Egg Dish', 'uploads/telur_goyang.jpg', 'Soft-boiled eggs served with soy sauce.', 'Prep time: 5 min, Cook time: 5 min', 'Eggs, Soy Sauce, Pepper', '1. Boil eggs for 5 minutes. 2. Crack into a bowl and drizzle with soy sauce and pepper.', 1, '2024-02-08 08:30:00'),
(24, 'Nasi Dagang', 30, 'Lunch', 'Malaysian, Rice Dish', 'uploads/nasi_dagang.jpg', 'Traditional Terengganu-style rice dish.', 'Prep time: 30 min, Cook time: 45 min', 'Rice, Coconut Milk, Fish Curry, Cucumber Pickle', '1. Steam rice with coconut milk. 2. Cook fish curry and serve with rice.', 1, '2024-02-07 12:20:00'),
(25, 'Soto Ayam', 31, 'Lunch', 'Malaysian, Soup', 'uploads/soto_ayam.jpg', 'Chicken soup with rice cakes.', 'Prep time: 15 min, Cook time: 35 min', 'Chicken, Lemongrass, Vermicelli, Rice Cakes', '1. Boil chicken with spices. 2. Shred chicken and serve in soup with rice cakes.', 1, '2024-02-06 13:10:00'),
(26, 'Nasi Goreng Kampung', 27, 'Lunch', 'Malaysian, Fried Rice', 'uploads/nasi_goreng_kampung.jpg', 'Traditional village-style fried rice.', 'Prep time: 10 min, Cook time: 10 min', 'Rice, Anchovies, Eggs, Chili, Garlic', '1. Sauté garlic and chili. 2. Add rice, anchovies, and eggs. 3. Stir-fry until mixed.', 1, '2024-02-05 12:45:00'),
(27, 'Masak Lemak Cili Padi', 28, 'Dinner', 'Malaysian, Spicy', 'uploads/masak_lemak_cili_padi.jpg', 'Spicy coconut curry with chicken.', 'Prep time: 15 min, Cook time: 40 min', 'Chicken, Coconut Milk, Turmeric, Chili', '1. Sauté spices. 2. Add chicken and coconut milk. 3. Simmer until cooked.', 1, '2024-02-04 19:30:00'),
(28, 'Sup Tulang Merah', 29, 'Dinner', 'Malaysian, Soup', 'uploads/sup_tulang_merah.jpg', 'Spicy red bone soup.', 'Prep time: 20 min, Cook time: 1 hour', 'Beef Bones, Tomato Paste, Chili Sauce, Spices', '1. Boil beef bones with spices. 2. Add tomato paste and chili sauce. 3. Simmer until meat is tender.', 1, '2024-02-03 20:00:00'),
(29, 'Pajeri Nanas', 30, 'Dinner', 'Malaysian, Pineapple Dish', 'uploads/pajeri_nanas.jpg', 'Sweet and spicy pineapple dish.', 'Prep time: 10 min, Cook time: 30 min', 'Pineapple, Coconut Milk, Spices, Tamarind', '1. Cook pineapple with spices. 2. Add coconut milk and simmer.', 1, '2024-02-02 19:15:00'),
(30, 'Kuih Keria', 31, 'Dessert', 'Malaysian, Sweet', 'uploads/kuih_keria.jpg', 'Sweet potato donuts with caramelized sugar.', 'Prep time: 20 min, Cook time: 15 min', 'Sweet Potato, Flour, Sugar, Oil', '1. Mash sweet potato and mix with flour. 2. Shape into rings and fry. 3. Coat with caramelized sugar.', 1, '2024-02-01 15:45:00'),
(31, 'Seri Muka', 27, 'Dessert', 'Malaysian, Layered', 'uploads/seri_muka.jpg', 'Layered glutinous rice and pandan custard.', 'Prep time: 30 min, Cook time: 40 min', 'Glutinous Rice, Coconut Milk, Pandan, Sugar', '1. Steam glutinous rice with coconut milk. 2. Pour pandan custard over and steam again.', 1, '2024-01-31 16:15:00'),
(32, 'Pengat Pisang', 28, 'Dessert', 'Malaysian, Sweet Soup', 'uploads/pengat_pisang.jpg', 'Banana cooked in coconut milk.', 'Prep time: 10 min, Cook time: 20 min', 'Banana, Coconut Milk, Palm Sugar, Sago', '1. Boil coconut milk and palm sugar. 2. Add banana and sago. 3. Simmer until cooked.', 1, '2024-01-30 14:30:00'),
(33, 'Kopi O', 29, 'Beverages', 'Malaysian, Coffee', 'uploads/kopi_o.jpg', 'Traditional black coffee.', 'Prep time: 5 min, Cook time: 5 min', 'Coffee Beans, Hot Water, Sugar', '1. Brew coffee with hot water. 2. Add sugar to taste.', 1, '2024-01-29 10:30:00'),
(34, 'Air Katira', 30, 'Beverages', 'Malaysian, Herbal Drink', 'uploads/air_katira.jpg', 'Cooling drink with basil seeds and jelly.', 'Prep time: 10 min, Cook time: 0 min', 'Basil Seeds, Jelly, Milk, Rose Syrup', '1. Soak basil seeds. 2. Mix with jelly, milk, and syrup.', 1, '2024-01-28 11:00:00'),
(35, 'Coconut Shake', 31, 'Beverages', 'Malaysian, Smoothie', 'uploads/coconut_shake.jpg', 'Blended coconut drink.', 'Prep time: 5 min, Cook time: 0 min', 'Coconut Flesh, Ice Cream, Ice, Sugar', '1. Blend coconut flesh with ice and sugar. 2. Top with ice cream.', 1, '2024-01-27 09:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleId` int(11) NOT NULL,
  `roleName` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleId`, `roleName`, `description`) VALUES
(1, 'Registered User', 'A user who can browse, save, and comment on recipes.'),
(2, 'Chef', 'A content creator who can publish, edit, and delete their own recipes.'),
(3, 'Admin', 'A system moderator with full control over users, recipes, and content moderation.');

-- --------------------------------------------------------

--
-- Table structure for table `saved_recipe`
--

CREATE TABLE `saved_recipe` (
  `savedRecipeId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `recipeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_recipe`
--

INSERT INTO `saved_recipe` (`savedRecipeId`, `userId`, `recipeId`) VALUES
(3, 9, 22),
(4, 9, 26),
(5, 9, 31),
(6, 5, 34),
(7, 5, 31),
(8, 5, 32),
(9, 5, 21),
(10, 6, 25),
(11, 6, 24),
(12, 6, 34),
(13, 6, 21),
(14, 7, 22),
(15, 7, 28),
(16, 7, 31),
(17, 7, 25),
(18, 5, 23),
(19, 5, 27),
(20, 5, 29),
(21, 5, 30),
(22, 5, 33),
(23, 5, 35),
(24, 6, 22),
(25, 6, 23),
(26, 6, 25),
(27, 6, 26),
(28, 6, 30),
(29, 6, 32),
(30, 7, 21),
(31, 7, 23),
(32, 7, 26),
(33, 7, 27),
(34, 7, 33),
(35, 7, 34),
(36, 8, 22),
(37, 8, 24),
(38, 8, 25),
(39, 8, 26),
(40, 8, 28),
(41, 8, 32),
(42, 9, 23),
(43, 9, 24),
(44, 9, 27),
(45, 9, 28),
(46, 9, 29),
(47, 9, 35),
(48, 10, 21),
(49, 10, 23),
(50, 10, 26),
(51, 10, 29),
(52, 10, 30),
(53, 10, 34),
(54, 11, 22),
(55, 11, 24),
(56, 11, 27),
(57, 11, 28),
(58, 11, 31),
(59, 11, 35),
(60, 12, 21),
(61, 12, 23),
(62, 12, 25),
(63, 12, 28),
(64, 12, 32),
(65, 12, 33),
(66, 13, 22),
(67, 13, 24),
(68, 13, 26),
(69, 13, 27),
(70, 13, 29),
(71, 13, 35),
(72, 14, 21),
(73, 14, 25),
(74, 14, 26),
(75, 14, 28),
(76, 14, 30),
(77, 14, 33),
(78, 15, 23),
(79, 15, 24),
(80, 15, 27),
(81, 15, 29),
(82, 15, 31),
(83, 15, 32),
(84, 16, 21),
(85, 16, 22),
(86, 16, 25),
(87, 16, 26),
(88, 16, 28),
(89, 16, 34),
(90, 17, 23),
(91, 17, 24),
(92, 17, 27),
(93, 17, 29),
(94, 17, 30),
(95, 17, 31),
(96, 18, 21),
(97, 18, 22),
(98, 18, 24),
(99, 18, 25),
(100, 18, 28),
(101, 18, 32),
(102, 19, 23),
(103, 19, 25),
(104, 19, 26),
(105, 19, 29),
(106, 19, 31),
(107, 19, 35);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `sessionId` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `roleId` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `language` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `profile_pic` varchar(255) NOT NULL DEFAULT 'assets/defaulticon.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `name`, `username`, `password`, `email`, `roleId`, `gender`, `language`, `country`, `profile_pic`) VALUES
(5, 'Ahmad Hakim', 'ahmadhakim1', 'password123', 'ahmadhakim@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(6, 'Siti Aisyah', 'sitiaisyah1', 'password123', 'sitiaisyah@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(7, 'Muhammad Hafiz', 'muhammadhafiz1', 'password123', 'muhammadhafiz@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(8, 'Nurul Izzah', 'nurulizzah1', 'password123', 'nurulizzah@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(9, 'Mohd Faiz', 'mohdfaiz1', 'password123', 'mohdfaiz@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(10, 'Aina Farhana', 'ainafarhana1', 'password123', 'ainafarhana@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(11, 'Syed Amir', 'syedamir1', 'password123', 'syedamir@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(12, 'Hafizah Binti Ali', 'hafizahali1', 'password123', 'hafizahali@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(13, 'Rashid Ismail', 'rashidismail1', 'password123', 'rashidismail@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(14, 'Nadia Syuhada', 'nadiasyuhada1', 'password123', 'nadiasyuhada@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(15, 'Azman Hamzah', 'azmanhamzah1', 'password123', 'azmanhamzah@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(16, 'Faridah Bakar', 'faridahbakar1', 'password123', 'faridahbakar@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(17, 'Iskandar Zulkarnain', 'iskandarzul1', 'password123', 'iskandarzul@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(18, 'Aminah Rahman', 'aminahrahman1', 'password123', 'aminahrahman@email.com', 1, 'Female', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(19, 'Zainal Abidin', 'zainalabidin1', 'password123', 'zainalabidin@email.com', 1, 'Male', 'Malay', 'Malaysia', 'assets/defaulticon.jpg'),
(27, 'Gordon Ramsay', 'gordonramsay', 'password123', 'gordon@email.com', 2, 'Male', 'English', 'UK', 'assets/defaulticon.jpg'),
(28, 'Jamie Oliver', 'jamieoliver', 'password123', 'jamie@email.com', 2, 'Male', 'English', 'UK', 'assets/defaulticon.jpg'),
(29, 'Alice Waters', 'alicewaters', 'password123', 'alice@email.com', 2, 'Female', 'English', 'USA', 'assets/defaulticon.jpg'),
(30, 'Massimo Bottura', 'massimobottura', 'password123', 'massimo@email.com', 2, 'Male', 'Italian', 'Italy', 'assets/defaulticon.jpg'),
(31, 'Nobu Matsuhisa', 'nobumatsuhisa', 'password123', 'nobu@email.com', 2, 'Male', 'Japanese', 'Japan', 'assets/defaulticon.jpg'),
(32, 'Admin One', 'adminone', 'adminpassword', 'adminone@email.com', 3, 'Male', 'English', 'USA', 'assets/defaulticon.jpg'),
(33, 'Admin Two', 'admintwo', 'adminpassword', 'admintwo@email.com', 3, 'Female', 'English', 'Canada', 'assets/defaulticon.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `fk_comment.recipeId` (`recipeId`),
  ADD KEY `fk_comment.userId` (`userId`);

--
-- Indexes for table `pending_recipe`
--
ALTER TABLE `pending_recipe`
  ADD PRIMARY KEY (`pendingId`),
  ADD KEY `fk_pending_recipe.chefId` (`chefId`);

--
-- Indexes for table `posted_recipe`
--
ALTER TABLE `posted_recipe`
  ADD PRIMARY KEY (`recipeId`),
  ADD KEY `fk_approved_recipe.chefId` (`chefId`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `saved_recipe`
--
ALTER TABLE `saved_recipe`
  ADD PRIMARY KEY (`savedRecipeId`),
  ADD KEY `fk_saved_recipe.userId` (`userId`),
  ADD KEY `fk_saved_recipe.recipeId` (`recipeId`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`sessionId`),
  ADD KEY `userid` (`userid`),
  ADD KEY `roleId` (`roleId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `idx_unique_username` (`username`),
  ADD KEY `fk_users.roleId` (`roleId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pending_recipe`
--
ALTER TABLE `pending_recipe`
  MODIFY `pendingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `posted_recipe`
--
ALTER TABLE `posted_recipe`
  MODIFY `recipeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `saved_recipe`
--
ALTER TABLE `saved_recipe`
  MODIFY `savedRecipeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `session_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `role` (`roleId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

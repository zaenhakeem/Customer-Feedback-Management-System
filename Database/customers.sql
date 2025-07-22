-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 05:50 PM
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
-- Database: `customers`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Product Quality'),
(2, 'Customer Service'),
(3, 'Delivery/Shipping'),
(4, 'Website Experience'),
(5, 'Pricing'),
(6, 'Technical Issues'),
(7, 'Feature Request'),
(8, 'Complaint'),
(9, 'General Feedback'),
(10, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Salman', 'salman@gmail.com', 'Page reloads', 'Page reloads fast', '2025-07-18 14:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `customertbl`
--

CREATE TABLE `customertbl` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customertbl`
--

INSERT INTO `customertbl` (`id`, `name`, `email`, `password`, `status`, `created_at`) VALUES
(1, 'Salman', 'salman@gmail.com', '$2y$10$iU6UuYFe5uRLyk2T6AP6/.rdfR5OtmgYJhRzvJzG8KJpGnEbLcE9m', 'active', '2025-07-18 14:59:55'),
(2, 'Salvador', 'salvador@gmail.com', '$2y$10$gYoxHHiAd9qpStymbdems.9QPspesjjVQKC3XhAO1Zwt5eSw7B.nm', 'active', '2025-07-22 15:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `message` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('new','in_progress','resolved') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `category_id`, `rating`, `message`, `file_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Alan', 'alanjr@gmail.com', 2, 4, 'Good service', NULL, 'resolved', '2025-07-17 12:40:40', '2025-07-18 00:34:06'),
(2, 'Salman', 'salman@gmail.com', 2, 3, 'The Customer Service is Fair', NULL, 'in_progress', '2025-04-15 14:21:59', '2025-07-17 18:22:03'),
(3, 'Betty', 'betty@gmail.com', 1, 3, 'The Products are nice.', NULL, 'new', '2025-07-17 15:27:19', NULL),
(4, 'John', 'john@gmail.com', 1, 3, 'The Products are nice.', NULL, 'in_progress', '2025-07-17 15:30:02', '2025-07-18 00:15:52'),
(5, 'Hassan ', 'hassan@gmail.com', 1, 3, 'The Products are nice.', NULL, 'in_progress', '2025-06-17 15:32:34', '2025-07-18 00:19:16'),
(6, 'Everly Green', 'everyly@gmail.com', 4, 5, 'The web interface is 10/10', NULL, 'resolved', '2025-05-20 20:32:27', '2025-07-18 00:32:24'),
(7, 'Asad', 'Asad@gmail.com', 7, 3, 'I would like to see user accounts', NULL, 'resolved', '2025-03-18 21:42:38', '2025-07-18 00:45:21'),
(8, 'Henry ', 'henry@gmail.com', 3, 2, 'Provide more versatile shipping ', NULL, 'resolved', '2025-05-17 21:43:15', '2025-07-18 20:37:58'),
(9, 'Ford', 'ford@gmail.com', 5, 2, 'The pricing is not consistent', NULL, 'new', '2025-03-17 21:43:48', NULL),
(10, 'Salvador', 'salvador@gmail.com', 7, 4, 'Nice', 'uploads/1753198543_feedback docs.docx', 'new', '2025-07-22 15:35:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_forms`
--

CREATE TABLE `feedback_forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_forms`
--

INSERT INTO `feedback_forms` (`id`, `title`, `description`, `created_at`) VALUES
(1, 'Product feedback form', 'We value your input. Please review', '2025-07-18 11:16:17'),
(2, 'Design Form', 'Your input is higly appreciated', '2025-07-18 12:33:34'),
(3, 'User Experience Feedback', 'We’d love your input on this design prototype.', '2025-07-18 13:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_form_fields`
--

CREATE TABLE `feedback_form_fields` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_type` enum('text','textarea','radio','checkbox','select') NOT NULL,
  `options` text DEFAULT NULL,
  `required` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_form_fields`
--

INSERT INTO `feedback_form_fields` (`id`, `form_id`, `label`, `field_type`, `options`, `required`, `order`, `created_at`) VALUES
(1, 1, 'Name', 'textarea', '', 1, 0, '2025-07-18 11:17:10'),
(2, 1, 'Email', 'textarea', '', 1, 0, '2025-07-18 11:17:37'),
(3, 1, 'How satisfied are you?', 'radio', 'Very satisfied,Satisfied,Neutral,Dissatisfied', 1, 0, '2025-07-18 11:18:22'),
(4, 1, 'Favorite Features', 'checkbox', 'Design,Speed,Support', 0, 0, '2025-07-18 11:19:01'),
(5, 1, 'Additional Comments', 'textarea', '', 0, 0, '2025-07-18 11:19:59'),
(6, 2, 'How visually appealing is the design?', 'select', 'Very Appealing, Appealing, Neutral, Unappealing, Very Unappealing', 1, 0, '2025-07-18 12:36:15'),
(7, 2, 'How easy was it to navigate the design?', 'select', 'Very Easy, Easy, Neutral, Difficult, Very Difficult', 1, 0, '2025-07-18 12:36:53'),
(8, 2, 'What do you like most about the design?', 'textarea', '', 0, 0, '2025-07-18 12:38:24'),
(9, 2, 'What would you improve or change?', 'text', '', 0, 0, '2025-07-18 12:38:33'),
(10, 2, 'How well does the design match its purpose?', 'select', 'Perfectly, Well, Neutral, Poorly, Not at all', 1, 0, '2025-07-18 12:39:26'),
(11, 2, 'Would you recommend this design to others?', 'radio', 'Yes, No', 1, 0, '2025-07-18 12:39:53'),
(12, 3, 'How intuitive did you find the interface?', 'select', 'Extremely Intuitive, Somewhat Intuitive, Not Intuitive', 1, 0, '2025-07-18 13:23:24'),
(13, 3, 'How quickly could you complete your task?', 'select', 'Very Quickly, Quickly, Average, Slowly, Very Slowly', 1, 0, '2025-07-18 13:24:02'),
(14, 3, 'Were any elements confusing or unclear?', 'text', '', 0, 0, '2025-07-18 13:24:26'),
(15, 3, 'What feature did you find most useful?', 'text', '', 0, 0, '2025-07-18 13:24:43'),
(16, 3, 'How likely are you to use this product regularly?', 'radio', 'Very Likely, Likely, Neutral, Unlikely, Very Unlikely', 1, 0, '2025-07-18 13:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_form_responses`
--

CREATE TABLE `feedback_form_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_form_responses`
--

INSERT INTO `feedback_form_responses` (`id`, `form_id`, `name`, `email`, `submitted_at`) VALUES
(1, 1, 'Abubakar Ismail', 'abuisma@gmail.com', '2025-07-18 11:45:11'),
(2, 1, 'Samuel', 'Baraza', '2025-07-18 11:55:25'),
(3, 2, 'Salma Yunis', 'salma@gmail.com', '2025-07-18 12:41:10'),
(4, 2, 'Abdalla Dahir', 'abdalla@gmail.com', '2025-07-18 12:47:04');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_responses`
--

CREATE TABLE `feedback_responses` (
  `id` int(11) NOT NULL,
  `feedback_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_responses`
--

INSERT INTO `feedback_responses` (`id`, `feedback_id`, `user_id`, `message`, `created_at`) VALUES
(1, 2, 2, 'Under Consideration', '2025-07-17 14:29:41'),
(2, 1, 2, 'Appreciations', '2025-07-17 14:30:04'),
(3, 2, 3, 'Good', '2025-07-17 14:57:57'),
(4, 2, NULL, 'Thank you', '2025-07-17 15:17:31'),
(5, 2, 2, 'You are very Much welcome', '2025-07-17 15:22:03'),
(6, 4, 2, 'We appreciate', '2025-07-17 21:15:52'),
(7, 5, 2, 'Appreciated', '2025-07-17 21:19:16'),
(8, 6, 2, 'Much Appreciations', '2025-07-17 21:29:50'),
(9, 8, 3, 'We are working on that', '2025-07-18 17:34:45'),
(10, 8, NULL, 'I hope so', '2025-07-18 17:35:26'),
(11, 8, 3, 'Is there anything else you would like to add', '2025-07-18 17:35:59'),
(12, 8, 3, 'Team, Make sure you look into this ASAP', '2025-07-18 17:36:24');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_response_answers`
--

CREATE TABLE `feedback_response_answers` (
  `id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_response_answers`
--

INSERT INTO `feedback_response_answers` (`id`, `response_id`, `field_id`, `answer`) VALUES
(1, 1, 1, 'All above'),
(2, 1, 2, 'aisma@gmail.com'),
(3, 1, 3, 'Satisfied'),
(4, 1, 4, 'Speed'),
(5, 1, 4, 'Support'),
(6, 1, 5, 'Make your design more user friendly'),
(7, 2, 1, 'Esther Adubai'),
(8, 2, 2, 'esther@gmail.com'),
(9, 2, 3, 'Very satisfied'),
(10, 2, 4, 'Design'),
(11, 2, 4, 'Speed'),
(12, 2, 4, 'Support'),
(13, 2, 5, 'Love this so much'),
(14, 3, 6, 'Very Appealing'),
(15, 3, 7, 'Very Easy'),
(16, 3, 8, 'The Looks'),
(17, 3, 9, 'Nothing'),
(18, 3, 10, 'Perfectly'),
(19, 3, 11, 'Yes'),
(20, 4, 6, 'Appealing'),
(21, 4, 7, 'Easy'),
(22, 4, 8, 'Colors'),
(23, 4, 9, 'The navigations'),
(24, 4, 10, 'Well'),
(25, 4, 11, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `internal_notes`
--

CREATE TABLE `internal_notes` (
  `id` int(11) NOT NULL,
  `feedback_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internal_notes`
--

INSERT INTO `internal_notes` (`id`, `feedback_id`, `user_id`, `note`, `created_at`) VALUES
(1, 2, 3, 'Confirmed', '2025-07-17 14:56:48'),
(2, 2, 3, 'Same issue Trannsfer', '2025-07-17 14:58:30'),
(3, 2, 2, 'We need to look into this issue', '2025-07-17 14:59:09'),
(4, 4, 2, 'Look into this team', '2025-07-17 21:17:20'),
(5, 8, 3, 'Team, Make sure you look into this ASAP', '2025-07-18 17:37:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `title`, `link`, `is_read`, `created_at`) VALUES
(1, 'feedback', '📝 New feedback submitted by Hassan ', 'Admin/respond.php?id=5', 1, '2025-07-17 15:32:34'),
(2, 'feedback', '📝 New feedback submitted by Everly Green', 'Admin/respond.php?id=6', 1, '2025-07-17 20:32:27'),
(3, 'feedback', '📝 New feedback submitted by Asmara', 'Admin/respond.php?id=7', 0, '2025-07-17 21:42:38'),
(4, 'feedback', '📝 New feedback submitted by Henry ', 'Admin/respond.php?id=8', 0, '2025-07-17 21:43:15'),
(5, 'feedback', '📝 New feedback submitted by Ford', 'Admin/respond.php?id=9', 1, '2025-07-17 21:43:48'),
(6, 'feedback', '📝 New feedback submitted by Salvador', 'Admin/respond.php?id=10', 0, '2025-07-22 15:35:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Admin', 'Admin@gmail.com', '$2y$10$1w87w6BK8/PhzfAXRX8gC.QxOAdc4lg76MCr/iktJvgX/D2Km4Etq', 'admin', '2025-07-17 12:58:17'),
(3, 'Staff', 'staff@gmail.com', '$2y$10$zEJ.IBPfe7zO9tRaXy2queyio5T2pFyrHdf.MFD6Mf64n92TV28wy', 'staff', '2025-07-17 14:46:40'),
(4, 'Staff2', 'staff2@gmail.com', '$2y$10$zMWNyWuKX6gieBLMuBYhTuZn33Hvu3GWXzzA6SNIkrOUxjlgAwz6S', 'staff', '2025-07-18 18:48:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customertbl`
--
ALTER TABLE `customertbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback_form_fields`
--
ALTER TABLE `feedback_form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `feedback_form_responses`
--
ALTER TABLE `feedback_form_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `feedback_responses`
--
ALTER TABLE `feedback_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_id` (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback_response_answers`
--
ALTER TABLE `feedback_response_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `internal_notes`
--
ALTER TABLE `internal_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_id` (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customertbl`
--
ALTER TABLE `customertbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback_form_fields`
--
ALTER TABLE `feedback_form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback_form_responses`
--
ALTER TABLE `feedback_form_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback_responses`
--
ALTER TABLE `feedback_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedback_response_answers`
--
ALTER TABLE `feedback_response_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `internal_notes`
--
ALTER TABLE `internal_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `feedback_form_fields`
--
ALTER TABLE `feedback_form_fields`
  ADD CONSTRAINT `feedback_form_fields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `feedback_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_form_responses`
--
ALTER TABLE `feedback_form_responses`
  ADD CONSTRAINT `feedback_form_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `feedback_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_responses`
--
ALTER TABLE `feedback_responses`
  ADD CONSTRAINT `feedback_responses_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `feedback_response_answers`
--
ALTER TABLE `feedback_response_answers`
  ADD CONSTRAINT `feedback_response_answers_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `feedback_form_responses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_response_answers_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `feedback_form_fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `internal_notes`
--
ALTER TABLE `internal_notes`
  ADD CONSTRAINT `internal_notes_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `internal_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

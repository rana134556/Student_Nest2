-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2026 at 05:46 PM
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
-- Database: `student_nest`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp(),
  `message` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `property_id`, `tenant_id`, `booking_date`, `message`, `status`) VALUES
(1, 1, 1, '2026-07-27 21:30:37', 'I would like to book this room.', 'Approved'),
(2, 2, 3, '2026-07-27 21:30:37', 'Is the room still available?', 'Approved'),
(3, 3, 4, '2026-07-27 21:30:37', 'Can I visit the apartment this weekend?', 'Rejected'),
(4, 4, 5, '2026-07-27 21:30:37', 'I am interested in renting this apartment.', 'Pending'),
(5, 4, 1, '2026-07-28 11:40:57', '', 'Pending'),
(6, 3, 1, '2026-07-28 11:42:52', '', 'Pending'),
(7, 2, 1, '2026-07-28 11:43:46', '', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Single Room'),
(2, 'Double Room'),
(3, 'Empty Apartment');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `property_id`, `tenant_id`, `owner_id`, `created_at`) VALUES
(1, 4, 1, 17, '2026-07-28 09:00:33'),
(2, 3, 1, 15, '2026-07-28 12:30:11'),
(3, 1, 1, 2, '2026-07-30 13:47:59');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`) VALUES
(1, 'Wi-Fi'),
(2, 'Kitchen'),
(3, 'Air Conditioner'),
(4, 'Washing Machine'),
(5, 'Security'),
(6, 'Elevator'),
(7, 'Private Bathroom'),
(8, 'Study Desk'),
(9, 'Parking'),
(10, 'Near University'),
(11, 'Balcony'),
(12, 'Fan');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `tenant_id`, `property_id`, `created_at`) VALUES
(2, 1, 4, '2026-07-27 18:39:50'),
(3, 3, 1, '2026-07-27 18:39:50'),
(4, 3, 3, '2026-07-27 18:39:50'),
(5, 1, 1, '2026-07-28 08:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `housing`
--

CREATE TABLE `housing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `governorate` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `location_link` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_slots` int(11) NOT NULL,
  `gender` enum('Male','Female','Both') NOT NULL,
  `security_deposit_required` tinyint(1) DEFAULT 0,
  `security_deposit_amount` decimal(10,2) DEFAULT NULL,
  `required_field_of_study` varchar(100) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `smoking_allowed` tinyint(1) DEFAULT 0,
  `additional_requirements` text DEFAULT NULL,
  `rooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `housing`
--

INSERT INTO `housing` (`id`, `user_id`, `category_id`, `title`, `description`, `governorate`, `city`, `address`, `location_link`, `price`, `available_slots`, `gender`, `security_deposit_required`, `security_deposit_amount`, `required_field_of_study`, `min_age`, `max_age`, `smoking_allowed`, `additional_requirements`, `rooms`, `bathrooms`, `area`) VALUES
(1, 2, 1, 'Modern Single Room', 'Comfortable single room in Zagazig, Sharqia. Perfect for students seeking a quiet place.', 'Sharqia', 'Zagazig', 'Al Qawmia District', 'https://maps.app.goo.gl/kUJdsVExNYQ37wNz6', 3000.00, 2, 'Female', 1, 2000.00, 'Any Student', 18, 30, 0, 'No overnight guests.\r\nKeep the room clean.', NULL, NULL, NULL),
(2, 2, 2, ' Modern Double Room', 'Affordable double room with a comfortable study environment.', 'Cairo', 'Nasr City', 'Abbas El-Akkad', 'https://maps.app.goo.gl/ALt8m45fAx1rKUj17', 2500.00, 4, 'Female', 1, 1500.00, 'Doctors', 18, 30, 0, 'Quiet students only.', NULL, NULL, NULL),
(3, 15, 1, 'Modern Single Room ', 'Clean and modern single room in Benha, Qalyubia. Ideal for university students.', 'Qalyubia', 'Benha', 'Saad Zaghloul St., Downtown', 'https://maps.app.goo.gl/39wR2BkxymiQRivg8', 2500.00, 2, 'Male', 1, 2500.00, 'Any Student', 18, 38, 0, 'Keep the room clean.', NULL, NULL, NULL),
(4, 17, 3, 'Unfurnished Room in Shared Apartment', 'Empty room in a shared apartment with kitchen and bathroom.', 'Gharbia', 'Tanta', 'Ali Mubarak Street', 'https://maps.app.goo.gl/s3j8uST5ZhrDYYY88', 1200.00, 1, 'Both', 1, 1200.00, 'Any Student', 18, 35, 1, 'Keep common areas clean.', NULL, NULL, NULL),
(5, 2, 2, 'New Property', NULL, 'fayoum', 'Fayoum', 'Zahraa Hospital', 'https://maps.app.goo.gl/EnbzET5wBtuzR5as9', 3000.00, 0, 'Male', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(6, 2, 2, 'New Property', NULL, 'fayoum', 'Fayoum', 'Zahraa Hospital', 'https://maps.app.goo.gl/EnbzET5wBtuzR5as9', 3000.00, 0, 'Male', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(7, 2, 2, 'New Property', NULL, 'fayoum', 'Fayoum', 'Zahraa Hospital', 'https://maps.app.goo.gl/EnbzET5wBtuzR5as9', 3000.00, 0, 'Male', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(8, 15, 1, 'Modern Single Room Near University', 'Clean and comfortable room for students with WiFi and security.', 'Sharqia', 'Zagazig', 'University Street', 'https://maps.google.com', 2500.00, 2, 'Both', 1, 1000.00, 'Computer Science', 18, 30, 0, 'No pets allowed', 3, 2, 120);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender` enum('tenant','owner') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `sender`, `message`, `created_at`) VALUES
(4, 1, 1, 'tenant', 'السلام عليكم', '2026-07-28 09:04:03'),
(5, 1, 1, 'tenant', 'السلام عليكم', '2026-07-28 12:23:56'),
(6, 2, 1, 'tenant', 'كيف الحال', '2026-07-28 15:20:46');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('booking','message','property') NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'booking', 'Your booking request has been approved.', 'bookings.php', 1, '2026-07-27 20:57:05'),
(2, 1, 'message', 'You have a new message.', 'messages.php?chat=1', 0, '2026-07-27 20:57:05'),
(3, 1, 'property', 'A new property matching your search has been added.', 'property_details.php?id=1', 1, '2026-07-27 20:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `property_facilities`
--

CREATE TABLE `property_facilities` (
  `property_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_facilities`
--

INSERT INTO `property_facilities` (`property_id`, `facility_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5),
(2, 3),
(2, 7),
(2, 8),
(2, 9),
(3, 4),
(3, 6),
(3, 8),
(3, 10),
(4, 1),
(4, 6),
(4, 11),
(4, 12),
(5, 1),
(5, 2),
(5, 8),
(5, 11),
(6, 1),
(6, 2),
(6, 8),
(6, 11),
(7, 1),
(7, 2),
(7, 8),
(7, 11);

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image`, `is_main`) VALUES
(1, 1, 'images/bedrooms_2.jfif', 1),
(2, 1, 'images/bedrooms_14.jfif', 0),
(3, 1, 'images/Living_Rooms_2.jfif', 0),
(4, 1, 'images/kitchens_2.jfif', 0),
(5, 1, 'images/bathrooms_4.jfif', 0),
(6, 1, 'images/outdoor_Areas_4.jfif', 0),
(7, 2, 'images/bedrooms_1.jfif', 1),
(8, 2, 'images/bedrooms_4.jfif', 0),
(9, 2, 'images/Living_Rooms_1.jfif', 0),
(10, 2, 'images/kitchens_6.jfif', 0),
(11, 2, 'images/bathrooms_2.jfif', 0),
(12, 2, 'images/outdoor_Areas_5.jfif', 0),
(13, 3, 'images/bedrooms_8.jfif', 1),
(14, 3, 'images/bedrooms_15.jfif', 0),
(15, 3, 'images/Living_Rooms_6.jfif', 0),
(16, 3, 'images/kitchens_1.jfif', 0),
(17, 3, 'images/bathrooms_5.jfif', 0),
(18, 3, 'images/outdoor_Areas_6.jfif', 0),
(19, 4, 'images/image_2_4.jfif', 1),
(20, 4, 'images/image_2_5.jfif', 0),
(21, 4, 'images/image_1_3.jfif', 0),
(22, 4, 'images/image_3_3.jfif', 0),
(23, 4, 'images/image_4_3.jfif', 0),
(24, 4, 'images/image_5_1.jfif', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `review_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `property_id`, `tenant_id`, `rating`, `comment`, `review_date`) VALUES
(1, 1, 1, 5, 'Excellent room, very clean and close to the university.', '2026-07-27 21:37:26'),
(2, 2, 3, 4, 'The apartment is comfortable and the landlord is helpful.', '2026-07-27 21:37:26'),
(3, 3, 4, 5, 'Great location and fast Wi-Fi. Highly recommended!', '2026-07-27 21:37:26'),
(4, 4, 5, 3, 'Good room, but it can be a little noisy at night.', '2026-07-27 21:37:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('tenant','landlord') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `image`) VALUES
(1, 'Rana Mohammed', 'rana01003603413@gmail.com', '+20 1026326685', 'r85@r', 'tenant', '1785252538_6a68caba1cc42_doctor.jpg'),
(2, 'Ahmed Ali', 'ahmed.ali@example.com', '01012345678', 'Ahmed@123', 'landlord', NULL),
(3, 'Sara Mohamed', 'sara.mohamed@example.com', '01123456789', 'Sara@1234', 'tenant', NULL),
(4, 'Omar Hassan', 'omar.hassan@example.com', '01234567890', 'Omar@2026', 'tenant', NULL),
(5, 'Nour El Din', 'nour.eldin@example.com', '01512345678', 'Nour@123', 'tenant', NULL),
(6, 'Mariam Adel', 'mariam.adel@example.com', '01098765432', 'Mariam@123', 'tenant', NULL),
(7, 'Youssef Mahmoud', 'youssef.mahmoud@example.com', '01187654321', 'Youssef@1', 'tenant', NULL),
(8, 'Salma Ahmed', 'salma.ahmed@example.com', '01211223344', 'Salma@2026', 'tenant', NULL),
(9, 'Mohamed Tarek', 'mohamed.tarek@example.com', '01555667788', 'Tarek@123', 'tenant', NULL),
(10, 'Aya Mostafa', 'aya.mostafa@example.com', '01055667788', 'Aya@12345', 'tenant', NULL),
(11, 'Karim Fathy', 'karim.fathy@example.com', '01199887766', 'Karim@2026', 'tenant', NULL),
(12, 'Lina Khaled', 'lina.khaled@example.com', '01033445566', 'Lina@123', 'landlord', NULL),
(13, 'Hassan Mostafa', 'hassan.mostafa@example.com', '01144556677', 'Hassan@123', 'landlord', NULL),
(14, 'Fatma Ibrahim', 'fatma.ibrahim@example.com', '01255667788', 'Fatma@2026', 'landlord', NULL),
(15, 'Ali Samir', 'ali.samir@example.com', '01566778899', 'Ali@12345', 'landlord', NULL),
(16, 'Reem Hossam', 'reem.hossam@example.com', '01077889911', 'Reem@123', 'landlord', NULL),
(17, 'Mahmoud Nabil', 'mahmoud.nabil@example.com', '01188990022', 'Mahmoud@1', 'landlord', NULL),
(18, 'Dina Gamal', 'dina.gamal@example.com', '01299001122', 'Dina@2026', 'landlord', NULL),
(19, 'Mostafa Eid', 'mostafa.eid@example.com', '01510293847', 'Mostafa@123', 'landlord', NULL),
(20, 'Haneen Wael', 'haneen.wael@example.com', '01056473829', 'Haneen@2026', 'landlord', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `housing`
--
ALTER TABLE `housing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `property_facilities`
--
ALTER TABLE `property_facilities`
  ADD PRIMARY KEY (`property_id`,`facility_id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `housing`
--
ALTER TABLE `housing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `housing` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `housing` (`id`),
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `conversations_ibfk_3` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `housing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `housing`
--
ALTER TABLE `housing`
  ADD CONSTRAINT `housing_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `housing_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `property_facilities`
--
ALTER TABLE `property_facilities`
  ADD CONSTRAINT `property_facilities_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `housing` (`id`),
  ADD CONSTRAINT `property_facilities_ibfk_2` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`);

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `housing` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `housing` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

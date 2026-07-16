-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2026 at 04:17 AM
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
-- Database: `chtm_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$cDcduSMrwTk4RyGYpBkbROwXsEPX.LGDHMM.Qunx0S.tWPNwOhx/i', '2026-07-16 01:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `body`, `image_path`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'NAMEPLATE AVAILABILITY', '<p>Good day, Junior Hoteliers!\r\n\r\nThe Society of Junior Hoteliers (SJH) would like to inform everyone that nameplates are now available for ordering at the SJH Office.\r\n\r\nStudents who need their official nameplates are encouraged to visit the SJH Office during office hours to place their orders. This is open to all eligible Hospitality Management students who have not yet secured their nameplates or wish to request an update or replacement.\r\n\r\nPlease ensure that all necessary details are correct upon ordering to avoid errors or delays. Orders will be processed accordingly, and further instructions regarding release will be provided upon confirmation.\r\n\r\nFor additional inquiries, kindly approach any SJH officer or contact us through our official communication channels.\r\n\r\nThank you for your cooperation and please be guided accordingly.</p>', 'Image/Announcment - Dress.jpg', 1, '2026-07-16 01:51:35', '2026-07-16 01:54:07'),
(2, 'SUBMISSION OF GRADUATION DOCUMENT REQUIREMENTS', '<p>Good day!</p><p>The Office of the Registrar would like to remind all <strong>2nd Semester Graduating Students for SY 2025-2026</strong> to submit the required graduation documents in preparation for enrollment for School Year 2025-2026. These documents will be used to determine the subjects you need to take to qualify for graduation.</p><p>The required documents are as follows:</p><p>1. Evaluated Prospectus (accessible through your student portal)<br>2. PSA / Birth Certificate (BC)<br>3. SF10 / Transcript of Records (TOR)<br>4. Form 138 / Original Report Card</p><p><strong>Deadline of Submission: December 12, 2025</strong></p><p>Please be reminded that failure to submit the above-mentioned documents will result in the removal of your name from the roster of graduating students.</p><p>Kindly refer to the attached list for any lacking requirements.</p><p>Thank you for your cooperation and please be guided accordingly.</p>', 'Image/Announcement - graduation.jpg', 2, '2026-07-16 01:51:35', '2026-07-16 01:51:35'),
(3, 'OJT SCREENING', '<p>Hello CHTM STUDENTS The OJT Screening is on July 21 Tuesday! 8AM<p>', 'uploads/announcements/img_6a583a55117b54.08849417.jpg', -2, '2026-07-16 01:56:37', '2026-07-16 01:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `subtitle`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'UC CARES Outreach Program', 'Service-learning, community engagement, and hands-on hospitality experiences beyond the campus.', 1, '2026-07-16 01:51:35', '2026-07-16 01:51:35'),
(2, 'Hospitality & Tourism Congress', 'Industry partners, student leaders, and faculty coming together to celebrate innovation and excellence.', 2, '2026-07-16 01:51:35', '2026-07-16 01:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `event_images`
--

CREATE TABLE `event_images` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_images`
--

INSERT INTO `event_images` (`id`, `event_id`, `image_path`, `sort_order`) VALUES
(1, 1, 'uploads/events/img_6a583b2d30aa81.03993662.jpg', 1),
(2, 1, 'uploads/events/img_6a583b2d319522.09142710.jpg', 2),
(3, 1, 'uploads/events/img_6a583b2d323887.66654395.jpg', 3),
(4, 1, 'uploads/events/img_6a583b2d333e64.12120401.jpg', 4),
(5, 1, 'uploads/events/img_6a583b2d33c3d2.22087265.jpg', 5),
(6, 1, 'uploads/events/img_6a583b2d34aad6.40786368.jpg', 6),
(7, 1, 'uploads/events/img_6a583b2d353fc1.06734921.jpg', 7),
(8, 1, 'uploads/events/img_6a583b2d35d242.36889144.jpg', 8),
(9, 2, 'uploads/events/img_6a583b41706b93.53428978.jpg', 1),
(10, 2, 'uploads/events/img_6a583b417142c2.53246557.jpg', 2),
(11, 2, 'uploads/events/img_6a583b417234e3.05237093.jpg', 3),
(12, 2, 'uploads/events/img_6a583b4172df10.61251514.jpg', 4),
(13, 2, 'uploads/events/img_6a583b4173dd67.60199514.jpg', 5),
(14, 2, 'uploads/events/img_6a583b41744e67.62519326.jpg', 6),
(15, 2, 'uploads/events/img_6a583b4174c779.48335116.jpg', 7),
(16, 2, 'uploads/events/img_6a583b41756044.74641724.jpg', 8),
(17, 2, 'uploads/events/img_6a583b41761de3.83294230.jpg', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_images`
--
ALTER TABLE `event_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_images`
--
ALTER TABLE `event_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_images`
--
ALTER TABLE `event_images`
  ADD CONSTRAINT `event_images_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

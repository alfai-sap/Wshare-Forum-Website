-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 09:47 AM
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
-- Database: `wshare_db_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `SettingID` int(11) NOT NULL,
  `SettingName` varchar(50) NOT NULL,
  `SettingValue` text NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`SettingID`, `SettingName`, `SettingValue`, `UpdatedAt`, `UpdatedBy`) VALUES
(1, 'site_name', 'WShare', '2024-12-06 04:51:36', NULL),
(2, 'allow_registration', '1', '2024-12-06 04:51:36', NULL),
(3, 'maintenance_mode', '0', '2024-12-06 04:51:36', NULL),
(4, 'site_name', 'WShare', '2024-12-06 04:52:12', NULL),
(5, 'allow_registration', '0', '2024-12-06 04:52:12', NULL),
(6, 'maintenance_mode', '0', '2024-12-06 04:52:12', NULL),
(7, 'site_name', 'WShare', '2024-12-06 04:52:15', NULL),
(8, 'allow_registration', '0', '2024-12-06 04:52:15', NULL),
(9, 'maintenance_mode', '0', '2024-12-06 04:52:15', NULL),
(10, 'site_name', 'WShare', '2024-12-06 04:52:19', NULL),
(11, 'allow_registration', '1', '2024-12-06 04:52:19', NULL),
(12, 'maintenance_mode', '0', '2024-12-06 04:52:19', NULL),
(13, 'site_name', 'WShare', '2024-12-06 07:26:27', NULL),
(14, 'allow_registration', '1', '2024-12-06 07:26:27', NULL),
(15, 'maintenance_mode', '0', '2024-12-06 07:26:27', NULL),
(16, 'site_name', 'WShare', '2024-12-06 10:29:20', NULL),
(17, 'allow_registration', '1', '2024-12-06 10:29:20', NULL),
(18, 'maintenance_mode', '1', '2024-12-06 10:29:20', NULL),
(19, 'site_name', 'WShare', '2024-12-06 10:29:40', NULL),
(20, 'allow_registration', '1', '2024-12-06 10:29:40', NULL),
(21, 'maintenance_mode', '1', '2024-12-06 10:29:40', NULL),
(22, 'site_name', 'WShare', '2024-12-06 10:29:41', NULL),
(23, 'allow_registration', '1', '2024-12-06 10:29:41', NULL),
(24, 'maintenance_mode', '1', '2024-12-06 10:29:41', NULL),
(25, 'site_name', 'WShare', '2024-12-06 10:29:42', NULL),
(26, 'allow_registration', '1', '2024-12-06 10:29:42', NULL),
(27, 'maintenance_mode', '1', '2024-12-06 10:29:42', NULL),
(28, 'site_name', 'WShare', '2024-12-06 10:29:55', NULL),
(29, 'allow_registration', '1', '2024-12-06 10:29:55', NULL),
(30, 'maintenance_mode', '1', '2024-12-06 10:29:55', NULL),
(31, 'site_name', 'WShare', '2024-12-06 10:39:40', NULL),
(32, 'allow_registration', '1', '2024-12-06 10:39:40', NULL),
(33, 'maintenance_mode', '1', '2024-12-06 10:39:40', NULL),
(34, 'site_name', 'WShare', '2024-12-06 10:46:51', NULL),
(35, 'allow_registration', '0', '2024-12-06 10:46:51', NULL),
(36, 'maintenance_mode', '0', '2024-12-06 10:46:51', NULL),
(37, 'site_name', 'WShare', '2024-12-06 10:47:10', NULL),
(38, 'allow_registration', '0', '2024-12-06 10:47:10', NULL),
(39, 'maintenance_mode', '1', '2024-12-06 10:47:10', NULL),
(40, 'site_name', 'WShare', '2024-12-06 10:47:20', NULL),
(41, 'allow_registration', '1', '2024-12-06 10:47:20', NULL),
(42, 'maintenance_mode', '0', '2024-12-06 10:47:20', NULL),
(43, 'site_name', 'WShare', '2024-12-07 01:33:32', NULL),
(44, 'allow_registration', '1', '2024-12-07 01:33:32', NULL),
(45, 'maintenance_mode', '1', '2024-12-07 01:33:32', NULL),
(46, 'site_name', 'WShare', '2024-12-07 01:33:49', NULL),
(47, 'allow_registration', '0', '2024-12-07 01:33:49', NULL),
(48, 'maintenance_mode', '1', '2024-12-07 01:33:49', NULL),
(49, 'site_name', 'WShare', '2024-12-07 01:34:06', NULL),
(50, 'allow_registration', '1', '2024-12-07 01:34:06', NULL),
(51, 'maintenance_mode', '0', '2024-12-07 01:34:06', NULL),
(52, 'site_name', 'WShare', '2024-12-09 17:38:41', NULL),
(53, 'allow_registration', '1', '2024-12-09 17:38:41', NULL),
(54, 'maintenance_mode', '1', '2024-12-09 17:38:41', NULL),
(55, 'site_name', 'WShare', '2024-12-09 17:38:44', NULL),
(56, 'allow_registration', '1', '2024-12-09 17:38:44', NULL),
(57, 'maintenance_mode', '0', '2024-12-09 17:38:44', NULL),
(58, 'site_name', 'WShare', '2024-12-10 07:04:30', NULL),
(59, 'allow_registration', '1', '2024-12-10 07:04:30', NULL),
(60, 'maintenance_mode', '1', '2024-12-10 07:04:30', NULL),
(61, 'site_name', 'WShare', '2024-12-10 07:04:46', NULL),
(62, 'allow_registration', '1', '2024-12-10 07:04:46', NULL),
(63, 'maintenance_mode', '0', '2024-12-10 07:04:46', NULL),
(64, 'site_name', 'WShare', '2024-12-10 07:05:03', NULL),
(65, 'allow_registration', '0', '2024-12-10 07:05:03', NULL),
(66, 'maintenance_mode', '0', '2024-12-10 07:05:03', NULL),
(67, 'site_name', 'WShare', '2024-12-10 07:05:04', NULL),
(68, 'allow_registration', '1', '2024-12-10 07:05:04', NULL),
(69, 'maintenance_mode', '0', '2024-12-10 07:05:04', NULL),
(70, 'site_name', 'WShare', '2024-12-10 07:05:07', NULL),
(71, 'allow_registration', '0', '2024-12-10 07:05:07', NULL),
(72, 'maintenance_mode', '0', '2024-12-10 07:05:07', NULL),
(73, 'site_name', 'WShare', '2024-12-10 07:05:19', NULL),
(74, 'allow_registration', '1', '2024-12-10 07:05:19', NULL),
(75, 'maintenance_mode', '0', '2024-12-10 07:05:19', NULL),
(76, 'site_name', 'WShare', '2024-12-16 15:13:19', NULL),
(77, 'allow_registration', '1', '2024-12-16 15:13:19', NULL),
(78, 'maintenance_mode', '1', '2024-12-16 15:13:19', NULL),
(79, 'site_name', 'WShare', '2024-12-16 15:13:35', NULL),
(80, 'allow_registration', '1', '2024-12-16 15:13:35', NULL),
(81, 'maintenance_mode', '1', '2024-12-16 15:13:35', NULL),
(82, 'site_name', 'WShare', '2024-12-16 15:13:37', NULL),
(83, 'allow_registration', '1', '2024-12-16 15:13:38', NULL),
(84, 'maintenance_mode', '0', '2024-12-16 15:13:38', NULL),
(85, 'site_name', 'WShare', '2024-12-24 09:56:23', NULL),
(86, 'allow_registration', '1', '2024-12-24 09:56:23', NULL),
(87, 'maintenance_mode', '1', '2024-12-24 09:56:23', NULL),
(88, 'site_name', 'WShare', '2024-12-24 09:56:39', NULL),
(89, 'allow_registration', '0', '2024-12-24 09:56:39', NULL),
(90, 'maintenance_mode', '1', '2024-12-24 09:56:39', NULL),
(91, 'site_name', 'WShare', '2024-12-24 09:57:17', NULL),
(92, 'allow_registration', '1', '2024-12-24 09:57:17', NULL),
(93, 'maintenance_mode', '0', '2024-12-24 09:57:17', NULL),
(94, 'site_name', 'WShare', '2025-01-06 16:37:10', NULL),
(95, 'allow_registration', '1', '2025-01-06 16:37:10', NULL),
(96, 'maintenance_mode', '0', '2025-01-06 16:37:10', NULL),
(97, 'site_name', 'WShare', '2025-01-06 16:37:13', NULL),
(98, 'allow_registration', '1', '2025-01-06 16:37:13', NULL),
(99, 'maintenance_mode', '0', '2025-01-06 16:37:13', NULL),
(100, 'site_name', 'WShare', '2025-01-06 16:37:14', NULL),
(101, 'allow_registration', '1', '2025-01-06 16:37:14', NULL),
(102, 'maintenance_mode', '0', '2025-01-06 16:37:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `BookmarkID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `Label` varchar(50) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`BookmarkID`, `UserID`, `PostID`, `Label`, `Notes`, `IsActive`, `CreatedAt`, `UpdatedAt`) VALUES
(7, 2, 239, 'nothing', NULL, 1, '2024-12-09 06:37:40', '2024-12-09 09:33:38'),
(9, 2, 38, 'Advice', 'teenage life', 1, '2024-12-09 08:49:12', '2024-12-09 09:34:04'),
(11, 35, 242, 'advice', 'sdmsdad', 1, '2024-12-10 03:19:42', '2024-12-10 07:17:18'),
(12, 35, 194, 'exam', 'reviewer', 1, '2024-12-10 07:12:16', '2024-12-10 07:17:03'),
(13, 2, 242, NULL, NULL, 1, '2024-12-16 06:46:48', '2024-12-16 06:46:48'),
(14, 2, 248, NULL, NULL, 1, '2024-12-16 10:34:44', '2024-12-16 10:34:44'),
(15, 48, 257, 'reviewer', 'exam 1', 1, '2025-01-02 09:08:33', '2025-01-02 09:09:56'),
(16, 48, 253, 'civl', NULL, 1, '2025-01-02 09:08:54', '2025-01-02 09:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `PostID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `PostID`, `UserID`, `Content`, `CreatedAt`, `updatedAt`) VALUES
(21, 22, 2, 'I totally relate to the struggle of finding a balance between academics and social life! One tip that has helped me is creating a weekly schedule where I allocate specific time slots for studying, attending classes, and socializing. It helps me stay organized and ensures that I have time for both work and play.', '2024-05-11 13:30:06', '2024-09-13 02:36:49'),
(22, 23, 2, 'Dorm life can be a rollercoaster ride, but it\'s also a great opportunity to make lifelong friends and learn valuable life skills. One piece of advice I would give is to establish open communication with your roommates from the beginning. Discuss expectations, boundaries, and any concerns you may have to avoid conflicts down the road.', '2024-05-11 13:32:32', '2024-09-13 02:36:49'),
(23, 35, 6, 'Exploring different majors and career paths can be a daunting task, but it\'s also an exciting opportunity to discover what truly interests you. My advice would be to take advantage of resources like career counseling services, internships, and informational interviews with professionals in fields you\'re interested in. Don\'t be afraid to try new things and follow your curiosity!', '2024-05-11 13:33:11', '2024-09-13 02:36:49'),
(24, 23, 6, 'I agree with what thorfin said', '2024-05-11 13:33:48', '2024-09-13 02:36:49'),
(25, 36, 5, 'Stress management is crucial for maintaining a healthy balance in college. One strategy that has helped me is practicing mindfulness and meditation. Taking a few minutes each day to breathe deeply and clear my mind helps me stay grounded and focused. It\'s also important to make time for activities you enjoy, whether it\'s exercising, spending time outdoors, or pursuing hobbies.', '2024-05-11 13:39:23', '2024-09-13 02:36:49'),
(26, 35, 5, 'For me, finding my passion was a process of trial and error. I took a variety of classes in different subjects, participated in extracurricular activities, and did internships to gain hands-on experience. Eventually, I found a major that aligned with my interests and values. Remember, it\'s okay to change your mind and explore different paths until you find what feels right for you.', '2024-05-11 13:39:55', '2024-09-13 02:36:49'),
(27, 36, 8, 'Don\'t hesitate to reach out for support if you\'re struggling with your mental health. Most colleges offer counseling services, support groups, and other resources for students. Talking to a therapist or counselor can provide valuable coping strategies and help you navigate challenges more effectively. Remember, it\'s okay to ask for help when you need it.', '2024-05-11 13:44:03', '2024-09-13 02:36:49'),
(28, 37, 8, 'One piece of advice I wish someone had given me when I started college is to get involved on campus from day one. Join clubs, student organizations, or volunteer groups that align with your interests and values. Not only will you meet new people and make friends, but you\'ll also gain valuable leadership skills and enrich your college experience.', '2024-05-11 13:44:49', '2024-09-13 02:36:49'),
(29, 24, 8, 'Homesickness is a common challenge, especially during the first few weeks of college. One thing that helped me was staying connected with family and friends back home through regular calls, texts, or video chats. Additionally, getting involved in campus activities and clubs helped me build a new support network and feel more at home in my college community.', '2024-05-11 13:46:13', '2024-09-13 02:36:49'),
(30, 38, 3, 'College is a great time to meet new people and form meaningful connections, but it\'s also important to prioritize your own well-being. Don\'t feel pressured to conform to societal expectations or rush into relationships. Take the time to get to know yourself and what you want out of relationships before committing to anything serious.', '2024-05-11 13:49:34', '2024-09-13 02:36:49'),
(31, 38, 3, 'Building and maintaining relationships in college can be both rewarding and challenging. Communication is key in any relationship, so don\'t hesitate to express your needs, boundaries, and feelings openly and honestly. Surround yourself with people who support and uplift you, and don\'t be afraid to set boundaries if a relationship becomes toxic or draining.', '2024-05-11 13:51:20', '2024-09-13 02:36:49'),
(32, 39, 7, 'Finding balance is key when it comes to staying healthy in college. Don\'t be too hard on yourself if you indulge in occasional treats or skip a workout. It\'s all about moderation and consistency. Surround yourself with supportive friends who encourage healthy habits and hold you accountable. Remember, self-care is not selfish—it\'s necessary for your overall well-being.', '2024-05-11 14:03:17', '2024-09-13 02:36:49'),
(33, 36, 2, 'hey', '2024-05-12 09:56:30', '2024-09-13 02:36:49'),
(34, 36, 2, 'thanks for the advice peeps', '2024-05-12 10:39:36', '2024-09-13 02:36:49'),
(35, 36, 2, 'appreciate it!', '2024-05-12 10:59:47', '2024-09-13 02:36:49'),
(36, 24, 2, 'yes it is', '2024-05-12 11:17:03', '2024-09-13 02:36:49'),
(37, 24, 2, 'SOCIALIZE', '2024-05-12 11:19:59', '2024-09-13 02:36:49'),
(38, 43, 2, 'me. 2nd sem grade for 2nd year', '2024-05-12 11:31:05', '2024-09-13 02:36:49'),
(39, 43, 2, 'just visit their official site', '2024-05-12 11:33:19', '2024-09-13 02:36:49'),
(40, 43, 2, 'w', '2024-05-12 11:35:28', '2024-09-13 02:36:49'),
(43, 35, 2, 'nice thanks guys!', '2024-05-12 12:45:04', '2024-09-13 02:36:49'),
(44, 37, 2, 'thanks for advice pipin!\r\n', '2024-05-12 13:25:32', '2024-09-13 02:36:49'),
(45, 44, 2, 'when?', '2024-05-12 14:11:12', '2024-09-13 02:36:49'),
(46, 35, 9, 'INTERESTING!', '2024-05-12 15:17:01', '2024-09-13 02:36:49'),
(47, 22, 9, 'p', '2024-05-12 16:35:07', '2024-09-13 02:36:49'),
(48, 39, 9, 'dsa', '2024-05-12 16:57:11', '2024-09-13 02:36:49'),
(50, 39, 9, 'dsad', '2024-05-12 17:05:05', '2024-09-13 02:36:49'),
(51, 43, 9, 'asd', '2024-05-12 17:06:04', '2024-09-13 02:36:49'),
(52, 43, 9, 'asds', '2024-05-12 17:06:09', '2024-09-13 02:36:49'),
(53, 41, 9, 'dsa', '2024-05-12 17:06:27', '2024-09-13 02:36:49'),
(54, 37, 3, 'nice tips!', '2024-05-12 17:22:11', '2024-09-13 02:36:49'),
(56, 39, 3, 'sadasd', '2024-05-12 20:37:04', '2024-09-13 02:36:49'),
(57, 37, 2, 'cxzc', '2024-05-12 18:45:38', '2024-09-13 02:36:49'),
(58, 37, 8, 'Z', '2024-05-12 22:05:33', '2024-09-13 02:36:49'),
(63, 38, 8, 'sad', '2024-05-13 00:11:12', '2024-09-13 02:36:49'),
(64, 39, 8, 'dsad', '2024-05-13 00:44:45', '2024-09-13 02:36:49'),
(65, 39, 8, 'sadasd', '2024-05-12 21:03:08', '2024-09-13 02:36:49'),
(68, 35, 3, 'fd', '2024-05-14 00:29:26', '2024-09-13 02:36:49'),
(70, 78, 17, 'I highly recommend checking out \"The Architecture of Happiness\" by Alain de Botton for insights into the psychological and emotional impact of architectural design. It\'s a thought-provoking read that might inspire some new ideas for your project.', '2024-05-15 09:48:12', '2024-09-13 02:36:49'),
(71, 39, 17, 'nice thread', '2024-05-15 11:45:03', '2024-09-13 02:36:49'),
(72, 39, 17, 'asda', '2024-05-15 12:36:02', '2024-09-13 02:36:49'),
(73, 78, 17, 'SDDSD', '2024-05-15 14:20:50', '2024-09-13 02:36:49'),
(74, 77, 17, 'nice keep coding', '2024-05-15 14:26:29', '2024-09-13 02:36:49'),
(75, 36, 17, 'sad', '2024-05-15 12:17:34', '2024-09-13 02:36:49'),
(76, 35, 17, 'sadsad', '2024-05-15 15:09:17', '2024-09-13 02:36:49'),
(77, 35, 17, 'ahh', '2024-05-15 15:09:24', '2024-09-13 02:36:49'),
(79, 80, 18, 'shakespeare', '2024-05-16 15:12:25', '2024-09-13 02:36:49'),
(80, 78, 2, 'interesting', '2024-06-02 14:14:11', '2024-09-13 02:36:49'),
(84, 35, 3, 'xxxx', '2024-06-08 14:32:54', '2024-09-13 02:36:49'),
(85, 37, 3, 'sadsad', '2024-06-08 14:41:55', '2024-09-13 02:36:49'),
(86, 37, 3, 'sadasdsad', '2024-06-08 14:43:18', '2024-09-13 02:36:49'),
(87, 37, 3, 'interesting', '2024-06-08 14:43:37', '2024-09-13 02:36:49'),
(89, 37, 3, 'rf', '2024-06-08 15:49:57', '2024-09-13 02:36:49'),
(90, 77, 3, 'go go go!', '2024-06-08 18:51:48', '2024-09-13 02:36:49'),
(91, 35, 2, 'sdsad', '2024-06-11 10:04:31', '2024-09-13 02:36:49'),
(92, 35, 2, 'bbb', '2024-06-11 10:04:37', '2024-09-13 02:36:49'),
(93, 35, 2, 'hj', '2024-06-11 10:39:14', '2024-09-13 02:36:49'),
(94, 96, 21, 'bobo', '2024-06-11 11:03:36', '2024-09-13 02:36:49'),
(95, 44, 21, 'bobo lebron', '2024-06-11 11:04:23', '2024-09-13 02:36:49'),
(96, 37, 2, 'sd', '2024-06-11 11:13:02', '2024-09-13 02:36:49'),
(97, 37, 2, 'fggggg', '2024-06-11 11:14:52', '2024-09-13 02:36:49'),
(98, 37, 2, 'ddd', '2024-06-11 11:15:33', '2024-09-13 02:36:49'),
(99, 37, 2, 'dddddddsddsd', '2024-06-11 11:20:26', '2024-09-13 02:36:49'),
(100, 37, 2, 'jnn', '2024-06-11 11:32:50', '2024-09-13 02:36:49'),
(101, 77, 2, 'hello', '2024-08-15 05:37:11', '2024-09-13 02:36:49'),
(104, 97, 2, 'dshbadskajdhsakjda', '2024-09-05 02:08:59', '2024-09-13 02:36:49'),
(108, 118, 31, 'like', '2024-09-12 12:19:31', '2024-09-13 02:36:49'),
(109, 118, 35, 'bros here', '2024-09-14 04:00:16', '2024-09-14 04:00:16'),
(110, 118, 2, 'Nice', '2024-09-14 04:00:30', '2024-09-14 04:00:30'),
(111, 118, 2, 'kyuuykk', '2024-09-14 10:37:46', '2024-09-14 10:37:46'),
(112, 113, 32, 'hey', '2024-09-14 16:11:27', '2024-09-14 16:11:27'),
(113, 35, 2, 'fd', '2024-09-15 03:28:57', '2024-09-15 03:28:57'),
(115, 123, 2, 'dslsfnsldfdsf', '2024-09-19 02:30:34', '2024-09-19 02:30:34'),
(116, 35, 37, 'ligma', '2024-09-19 02:33:40', '2024-09-19 02:33:40'),
(117, 149, 2, 'yow', '2024-09-25 02:48:53', '2024-09-25 02:48:53'),
(118, 149, 38, 'dslkfmmdsf', '2024-09-25 02:49:15', '2024-09-25 02:49:15'),
(123, 113, 2, 'adss', '2024-11-22 13:01:27', '2024-11-22 13:01:27'),
(124, 97, 2, 'fdsfsdff', '2024-11-22 13:08:19', '2024-11-22 13:08:19'),
(125, 184, 35, 'just read this a week ago ', '2024-11-23 06:20:30', '2024-11-23 06:20:30'),
(126, 202, 35, 'tis good', '2024-11-23 12:36:31', '2024-11-23 12:36:31'),
(127, 202, 35, 'dasdsad', '2024-11-23 12:36:39', '2024-11-23 12:36:39'),
(128, 204, 35, 'dasdasdasd', '2024-11-23 13:59:18', '2024-11-23 13:59:18'),
(129, 205, 35, 'fdfssdfsdf', '2024-11-25 16:41:50', '2024-11-25 16:41:50'),
(130, 207, 2, 'ewqewqewqewqewq', '2024-12-06 03:43:01', '2024-12-06 03:43:01'),
(131, 205, 2, 'dasdasd', '2024-12-06 04:46:37', '2024-12-06 04:46:37'),
(132, 207, 2, 'dawd', '2024-12-06 08:42:37', '2024-12-06 08:42:37'),
(133, 208, 2, 'ewqewqe', '2024-12-06 12:00:46', '2024-12-06 12:00:46'),
(134, 207, 2, 'dsadasdasd', '2024-12-06 12:02:25', '2024-12-06 12:02:25'),
(135, 206, 2, 'dsadasdasd', '2024-12-06 12:02:33', '2024-12-06 12:02:33'),
(136, 201, 2, 'dasdasdasd', '2024-12-06 12:02:45', '2024-12-06 12:02:45'),
(137, 209, 2, 'dasdasdd', '2024-12-06 12:43:23', '2024-12-06 12:43:23'),
(138, 123, 2, 'dwedwed', '2024-12-06 12:47:38', '2024-12-06 12:47:38'),
(146, 242, 2, 'xzczxc', '2024-12-09 17:23:46', '2024-12-09 17:23:46'),
(147, 242, 35, 'dsdsd', '2024-12-10 07:02:39', '2024-12-10 07:02:39'),
(148, 242, 47, 'DD', '2024-12-15 02:31:55', '2024-12-15 02:31:55'),
(149, 207, 2, 'fdffmldf', '2024-12-19 13:01:07', '2024-12-19 13:01:07'),
(150, 207, 2, 'hi', '2024-12-19 13:01:24', '2024-12-19 13:01:24'),
(152, 252, 2, 'wow', '2024-12-19 13:20:13', '2024-12-19 13:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `comment_attachments`
--

CREATE TABLE `comment_attachments` (
  `AttachmentID` int(11) NOT NULL,
  `CommentID` int(11) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` enum('image','video','document') NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FileSize` int(11) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `CommunityID` int(11) NOT NULL,
  `CreatorID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Thumbnail` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Visibility` enum('public','private') NOT NULL DEFAULT 'public',
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`CommunityID`, `CreatorID`, `Title`, `Description`, `Thumbnail`, `CreatedAt`, `Visibility`, `updatedAt`) VALUES
(2, 2, 'Angkol', 'angkol ka? dito na kana', 'uploads/crazy-expression-draw-260nw-1351230482.png', '2024-09-16 15:38:49', 'public', '2024-12-06 06:41:36'),
(3, 2, 'PHPmyLoveMen', 'mamaw lang sa php pwede dito', 'uploads/Screenshot 2024-09-10 233418.png', '2024-09-16 15:40:01', 'public', '2024-12-06 06:41:36'),
(4, 2, 'Mamaw sa CMD', 'any cmd related discussions', 'uploads/Screenshot 2024-09-14 110701.png', '2024-09-16 15:41:32', 'public', '2024-12-06 06:41:36'),
(5, 2, 'Elden Lords', 'Ye who ascendth the throne, come forth.', 'uploads/Screenshot 2024-08-26 132636.png', '2024-09-16 15:43:19', 'public', '2024-12-06 06:41:36'),
(6, 2, 'WMSU Business Peeps', 'may idea ka business? share mo diskarte mo!', 'uploads/josh.jpg', '2024-09-16 15:48:53', 'public', '2024-12-06 06:41:36'),
(7, 2, 'Study Buddies (CALCULUS)', 'LF engineers yung tuturuan ako mag mah-- mathematical computations', 'uploads/oppenheimer.PNG', '2024-09-16 15:50:22', 'public', '2024-12-06 06:41:36'),
(8, 2, 'TEKKEN TA DUK', 'watatatatatata', 'uploads/tekken-8-3840x2160-20402.jpg', '2024-09-16 15:51:03', 'public', '2024-12-06 06:41:36'),
(9, 2, 'Black Myth: Wukong Fans', 'The GOAT monke', 'uploads/5047008.jpg', '2024-09-17 16:06:23', 'public', '2024-12-06 06:41:36'),
(10, 37, 'WMSU marketplace', 'blablabla', '', '2024-09-19 02:32:19', 'public', '2024-12-06 06:41:36'),
(11, 2, 'test', 'test', 'community_thumbs/markus-spiske-cjOAigK9xo0-unsplash.jpg', '2024-09-20 04:02:09', 'public', '2024-12-06 06:41:36'),
(13, 3, 'edhgasjhdhjsadjkas', 'kjasbdkasbdksadj', 'community_thumbs/e6163f715a4e2a979dec5932de93f71a.png', '2024-09-25 01:54:26', 'public', '2024-12-06 06:41:36'),
(16, 3, 'game dev', 'game dev community', 'community_thumbs/57d025262e7907875beb666b64788899_1.jpg', '2024-10-13 11:22:37', 'public', '2024-12-06 06:41:36'),
(18, 2, 'adad', 'adadadada', 'community_thumbs/Tree.png', '2024-11-19 04:52:51', 'public', '2024-12-06 06:41:36'),
(19, 29, 'sadasd', 'dasdsd', 'community_thumbs/Green Aesthetic Poster Portrait.png', '2024-11-21 02:58:49', 'public', '2024-12-06 06:41:36'),
(20, 2, 'private', 'private', 'community_thumbs/127089.jpg', '2024-11-21 13:57:07', 'private', '2024-12-06 06:41:36'),
(21, 3, 'fdgfd', 'fgdfgdfg', 'community_thumbs/images (37).jpeg', '2024-11-21 15:29:47', 'private', '2024-12-06 06:41:36'),
(22, 3, 'naot', 'sadad', 'community_thumbs/5922707.jpg', '2024-11-22 07:14:09', 'private', '2024-12-06 06:41:36'),
(23, 2, 'test', 'asdasdsad', 'community_thumbs/wallpaperflare.com_wallpaper.jpg', '2024-11-22 07:16:14', 'private', '2024-12-06 06:41:36'),
(24, 3, 'test owner', 'sdsadsad', 'community_thumbs/6617357.jpg', '2024-11-22 07:20:27', 'private', '2024-12-06 06:41:36'),
(25, 2, 'bloodborne', 'yarnamers yarnamers', 'uploads/peakpx (3).jpg', '2024-11-22 15:40:47', 'public', '2024-12-06 06:41:36'),
(26, 2, 'das', 'dasdsadasd', 'community_thumbs/LendWorks-ERDupdated (1)-LENDWORKS ERD.drawio (2).png', '2024-12-06 12:44:41', 'public', '2024-12-06 12:44:41'),
(27, 2, 'sdfc', 'asdsasdaasdasd', 'uploads/defaultbg.jpg', '2024-12-06 12:58:24', 'private', '2024-12-07 09:31:07'),
(29, 2, 'math majors', 'let\'s study and learn together', 'community_thumbs/Screenshot 2024-09-09 174645.png', '2024-12-09 06:35:47', 'public', '2024-12-09 06:35:47');

-- --------------------------------------------------------

--
-- Table structure for table `community_bookmarks`
--

CREATE TABLE `community_bookmarks` (
  `BookmarkID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `Label` varchar(50) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_comments`
--

CREATE TABLE `community_comments` (
  `CommentID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_comments`
--

INSERT INTO `community_comments` (`CommentID`, `PostID`, `UserID`, `Content`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 2, 3, 'sadhashd', '2024-10-08 00:47:52', '2024-10-08 00:47:52'),
(2, 2, 3, 'sadhashd', '2024-10-08 00:48:15', '2024-10-08 00:48:15'),
(3, 7, 3, 'dsfd', '2024-10-08 00:55:29', '2024-10-08 00:55:29'),
(4, 7, 3, 'kk', '2024-10-08 01:13:40', '2024-10-08 01:13:40'),
(5, 9, 3, 'sadasdasdsa', '2024-10-13 11:06:39', '2024-10-13 11:06:39'),
(6, 13, 35, 'fsdfsdfsdfsdf', '2024-11-25 12:17:40', '2024-11-25 12:17:40'),
(7, 13, 35, 'fsdfsdfsdff', '2024-11-25 12:17:42', '2024-11-25 12:17:42'),
(8, 13, 35, 'fsdfsdfsdf', '2024-11-25 12:17:45', '2024-11-25 12:17:45'),
(9, 13, 35, 'fsdfsdf', '2024-11-25 12:17:47', '2024-11-25 12:17:47'),
(10, 23, 35, 'dsadasd', '2024-11-25 17:09:55', '2024-11-25 17:09:55'),
(11, 20, 35, 'rdtdgfdg', '2024-11-25 17:21:59', '2024-11-25 17:21:59'),
(12, 25, 2, 'fdgdfgdfgdfg', '2024-12-08 01:29:20', '2024-12-08 01:29:20'),
(13, 25, 2, 'dsadasd', '2024-12-08 01:32:52', '2024-12-08 01:32:52'),
(14, 26, 2, 'dsaasdsd', '2024-12-15 05:27:19', '2024-12-15 05:27:19'),
(15, 24, 8, 'fdd', '2024-12-16 15:24:25', '2024-12-16 15:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `community_join_requests`
--

CREATE TABLE `community_join_requests` (
  `RequestID` int(11) NOT NULL,
  `CommunityID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `RequestDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_join_requests`
--

INSERT INTO `community_join_requests` (`RequestID`, `CommunityID`, `UserID`, `RequestDate`, `status`) VALUES
(7, 23, 8, '2024-11-22 08:16:34', 'accepted'),
(8, 23, 35, '2024-11-22 08:24:31', 'accepted'),
(9, 23, 3, '2024-11-22 08:26:18', 'accepted'),
(10, 23, 3, '2024-11-22 08:42:27', 'accepted'),
(11, 23, 3, '2024-11-22 08:44:29', 'accepted'),
(12, 23, 3, '2024-11-22 09:06:36', 'accepted'),
(13, 23, 3, '2024-11-22 09:16:00', 'accepted'),
(14, 23, 3, '2024-11-22 09:18:07', 'accepted'),
(15, 23, 3, '2024-11-22 09:19:01', 'accepted'),
(16, 23, 3, '2024-11-22 09:19:48', 'accepted'),
(17, 23, 2, '2024-11-23 02:37:27', 'pending'),
(18, 25, 35, '2024-11-23 02:57:41', 'rejected'),
(19, 25, 35, '2024-11-23 02:58:24', 'rejected'),
(20, 25, 35, '2024-11-23 02:59:21', 'rejected'),
(21, 21, 35, '2024-11-25 17:07:37', 'pending'),
(22, 20, 3, '2024-12-06 06:42:24', 'pending'),
(23, 27, 3, '2024-12-08 10:25:28', 'accepted'),
(24, 27, 8, '2024-12-16 15:23:51', 'pending'),
(25, 27, 3, '2025-01-05 14:37:43', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `community_likes`
--

CREATE TABLE `community_likes` (
  `LikeID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `LikedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_likes`
--

INSERT INTO `community_likes` (`LikeID`, `PostID`, `UserID`, `LikedAt`) VALUES
(3, 13, 2, '2024-12-06 14:57:45'),
(11, 23, 8, '2024-12-06 15:23:23'),
(14, 21, 8, '2024-12-06 15:29:58'),
(17, 20, 2, '2024-12-06 17:32:14'),
(20, 25, 2, '2024-12-08 02:34:10'),
(21, 23, 2, '2024-12-08 02:43:47'),
(22, 22, 2, '2024-12-08 02:44:18'),
(23, 21, 2, '2024-12-08 06:02:19'),
(25, 26, 35, '2024-12-10 04:52:13'),
(28, 17, 2, '2024-12-15 11:11:08'),
(30, 26, 2, '2024-12-16 15:22:56'),
(31, 16, 2, '2024-12-16 15:23:24'),
(32, 24, 8, '2024-12-16 15:24:08'),
(33, 18, 8, '2024-12-16 15:41:54'),
(34, 26, 8, '2024-12-16 15:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `community_members`
--

CREATE TABLE `community_members` (
  `CommunityID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Role` enum('admin','member') NOT NULL DEFAULT 'member',
  `JoinedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_members`
--

INSERT INTO `community_members` (`CommunityID`, `UserID`, `Role`, `JoinedAt`) VALUES
(2, 2, 'admin', '2024-09-16 15:38:49'),
(2, 3, 'member', '2024-09-16 15:55:37'),
(3, 2, 'admin', '2024-09-16 15:40:01'),
(3, 35, 'member', '2024-11-23 03:00:53'),
(4, 2, 'admin', '2024-09-16 15:41:32'),
(4, 3, 'member', '2024-09-16 15:55:28'),
(4, 29, 'member', '2024-09-17 16:32:15'),
(5, 2, 'admin', '2024-09-16 15:43:19'),
(5, 8, 'member', '2024-09-19 02:39:55'),
(5, 29, 'admin', '2024-09-17 16:35:00'),
(5, 32, 'member', '2024-09-22 05:50:21'),
(5, 35, 'member', '2024-09-22 06:46:27'),
(6, 2, 'admin', '2024-09-16 15:48:53'),
(6, 3, 'member', '2024-10-13 11:06:01'),
(7, 2, 'admin', '2024-09-16 15:50:22'),
(7, 3, 'member', '2024-09-16 15:55:24'),
(8, 2, 'admin', '2024-09-16 15:51:03'),
(8, 3, 'member', '2024-09-16 15:55:55'),
(9, 2, 'admin', '2024-09-17 16:06:23'),
(10, 2, 'member', '2024-11-22 14:55:20'),
(10, 3, 'member', '2024-11-22 09:15:42'),
(10, 8, 'member', '2024-09-19 02:39:42'),
(10, 37, 'admin', '2024-09-19 02:32:19'),
(11, 2, 'admin', '2024-09-20 04:02:09'),
(13, 2, 'admin', '2024-09-25 01:54:46'),
(16, 3, 'admin', '2024-10-13 11:22:37'),
(16, 35, 'admin', '2024-10-13 11:24:15'),
(18, 2, 'admin', '2024-11-19 04:52:51'),
(18, 3, 'member', '2024-11-21 15:29:26'),
(18, 35, 'member', '2024-11-25 17:07:48'),
(19, 3, 'member', '2024-11-21 15:22:11'),
(19, 29, 'admin', '2024-11-21 02:58:49'),
(20, 2, 'admin', '2024-11-21 13:57:07'),
(21, 3, 'admin', '2024-11-21 15:29:47'),
(22, 3, 'admin', '2024-11-22 07:14:09'),
(23, 3, 'admin', '2024-11-22 09:50:39'),
(23, 8, 'member', '2024-11-22 08:21:20'),
(23, 35, 'member', '2024-11-22 08:25:20'),
(24, 2, 'admin', '2024-11-22 07:20:47'),
(24, 3, 'admin', '2024-11-22 08:02:48'),
(24, 35, 'member', '2024-11-22 07:54:22'),
(25, 2, 'admin', '2024-11-22 15:40:47'),
(25, 3, 'member', '2024-12-06 06:42:14'),
(25, 8, 'member', '2024-12-16 15:41:19'),
(25, 35, 'member', '2024-11-23 03:00:09'),
(25, 48, 'member', '2025-01-02 09:04:07'),
(26, 2, 'member', '2024-12-06 12:44:48'),
(26, 3, 'member', '2024-12-15 09:01:03'),
(27, 2, 'admin', '2024-12-06 12:58:24'),
(27, 3, 'member', '2025-01-05 14:38:02'),
(29, 2, 'admin', '2024-12-09 06:35:47'),
(29, 35, 'member', '2024-12-10 07:10:34');

-- --------------------------------------------------------

--
-- Table structure for table `community_messages`
--

CREATE TABLE `community_messages` (
  `MessageID` int(11) NOT NULL,
  `CommunityID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ParentMessageID` int(11) DEFAULT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeletedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_messages`
--

INSERT INTO `community_messages` (`MessageID`, `CommunityID`, `UserID`, `ParentMessageID`, `Content`, `CreatedAt`, `UpdatedAt`, `DeletedAt`) VALUES
(1, 27, 2, NULL, 'xcxzcxzcxzc', '2024-12-08 03:35:48', '2024-12-08 03:35:48', NULL),
(2, 27, 2, NULL, 'dasdd', '2024-12-08 03:44:21', '2024-12-08 03:44:21', NULL),
(3, 25, 2, NULL, 'hi guys', '2024-12-08 04:00:47', '2024-12-08 04:00:47', NULL),
(4, 25, 3, NULL, 'heyhahaha', '2024-12-08 04:06:07', '2024-12-08 04:06:07', NULL),
(5, 25, 3, NULL, 'look', '2024-12-08 04:12:06', '2024-12-08 04:12:06', NULL),
(6, 25, 3, NULL, 'ganda neto guys', '2024-12-08 04:12:38', '2024-12-08 04:12:38', NULL),
(7, 25, 2, NULL, 'seesh galing', '2024-12-08 04:17:05', '2024-12-08 04:17:05', NULL),
(8, 25, 3, NULL, 'san na kayo banda ?', '2024-12-08 04:17:45', '2024-12-08 04:17:45', NULL),
(9, 25, 3, NULL, 'malapit na to sa ending?', '2024-12-08 04:17:57', '2024-12-08 04:17:57', NULL),
(10, 25, 3, 7, 'thanks haha', '2024-12-08 04:28:35', '2024-12-08 04:28:35', NULL),
(21, 25, 35, NULL, 'ano meron?', '2024-12-08 05:45:29', '2024-12-08 05:45:29', NULL),
(22, 25, 35, NULL, 'bat yan?', '2024-12-08 05:45:40', '2024-12-08 05:45:40', NULL),
(23, 25, 35, 10, 'nice one', '2024-12-08 05:45:55', '2024-12-08 05:45:55', NULL),
(24, 25, 35, NULL, 'oh', '2024-12-08 05:46:12', '2024-12-08 05:46:12', NULL),
(26, 25, 35, NULL, 'goodshit', '2024-12-08 05:46:46', '2024-12-08 05:46:46', NULL),
(28, 25, 35, NULL, 'sasa', '2024-12-08 05:47:18', '2024-12-08 05:47:18', NULL),
(30, 25, 35, NULL, 'ddasdasd', '2024-12-08 05:56:43', '2024-12-08 05:56:43', NULL),
(31, 25, 35, NULL, 'dsadadasd', '2024-12-08 05:57:06', '2024-12-08 05:57:06', NULL),
(32, 25, 35, NULL, 'dsadasdasdsadfgfhfghgfhfghgf', '2024-12-08 05:57:16', '2024-12-08 05:57:16', NULL),
(33, 24, 35, NULL, 'wala man tao dito?', '2024-12-08 05:58:59', '2024-12-08 05:58:59', NULL),
(34, 24, 35, NULL, 'look', '2024-12-08 05:59:09', '2024-12-08 05:59:09', NULL),
(35, 24, 2, NULL, 'meron di lang active', '2024-12-08 06:01:49', '2024-12-08 06:01:49', NULL),
(36, 24, 2, 33, 'ako pala admin nice to meet you', '2024-12-08 06:02:06', '2024-12-08 06:02:06', NULL),
(37, 24, 3, NULL, 'hi', '2024-12-08 06:09:22', '2024-12-08 06:09:22', NULL),
(38, 24, 3, NULL, 'dd', '2024-12-08 06:10:07', '2024-12-08 06:10:07', NULL),
(39, 25, 3, NULL, 'rgdgdfg', '2024-12-08 06:45:12', '2024-12-08 06:45:12', NULL),
(40, 25, 3, 32, 'dsdsada', '2024-12-08 06:45:24', '2024-12-08 06:45:24', NULL),
(41, 25, 3, NULL, 'what the', '2024-12-08 13:07:33', '2024-12-08 13:07:33', NULL),
(42, 25, 35, NULL, 'guys boring', '2024-12-09 10:55:26', '2024-12-09 10:55:26', NULL),
(43, 25, 35, NULL, 'ddsdsd', '2024-12-10 06:44:06', '2024-12-10 06:44:06', NULL),
(44, 25, 35, NULL, 'dsdsd', '2024-12-10 06:45:00', '2024-12-10 06:45:00', NULL),
(45, 25, 35, NULL, 'dsdd', '2024-12-10 06:59:37', '2024-12-10 06:59:37', NULL),
(46, 25, 35, 41, 'hahahaha', '2024-12-10 07:00:44', '2024-12-10 07:00:44', NULL),
(47, 25, 35, NULL, 'eldld', '2024-12-10 07:01:33', '2024-12-10 07:01:33', NULL),
(48, 25, 2, 40, 'what?', '2024-12-15 06:30:03', '2024-12-15 06:30:03', NULL),
(49, 25, 2, 48, 'wwdwd', '2024-12-15 06:30:16', '2024-12-15 06:30:16', NULL),
(50, 25, 2, NULL, 'what', '2024-12-15 06:35:36', '2024-12-15 06:35:36', NULL),
(51, 25, 2, NULL, 'dasdasd', '2024-12-15 07:15:59', '2024-12-15 07:15:59', NULL),
(52, 25, 3, NULL, 'hallooooooo', '2024-12-15 08:23:56', '2024-12-15 08:23:56', NULL),
(53, 25, 3, 51, 'ano yan?', '2024-12-15 08:24:10', '2024-12-15 08:24:10', NULL),
(54, 25, 3, NULL, 'eto project oh', '2024-12-15 08:27:11', '2024-12-15 08:27:11', NULL),
(55, 25, 8, NULL, 'hello po!', '2024-12-16 15:42:22', '2024-12-16 15:42:22', NULL),
(56, 25, 48, NULL, 'hello', '2025-01-02 09:04:59', '2025-01-02 09:04:59', NULL),
(57, 24, 2, NULL, 'c', '2025-01-05 14:35:52', '2025-01-05 14:35:52', NULL),
(58, 27, 2, NULL, 'hello', '2025-01-05 14:38:37', '2025-01-05 14:38:37', NULL),
(59, 27, 3, NULL, 'hi po', '2025-01-05 14:38:56', '2025-01-05 14:38:56', NULL),
(60, 27, 2, NULL, 'bagog animation', '2025-01-05 15:02:16', '2025-01-05 15:02:16', NULL),
(61, 27, 3, 58, 'huway', '2025-01-05 15:18:48', '2025-01-05 15:18:48', NULL),
(62, 27, 2, 61, 'ahahaa', '2025-01-05 15:19:24', '2025-01-05 15:19:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `PostID` int(11) NOT NULL,
  `CommunityID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PhotoPath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`PostID`, `CommunityID`, `UserID`, `Title`, `Content`, `CreatedAt`, `UpdatedAt`, `PhotoPath`) VALUES
(1, 9, 2, 'INTERESTED IN THIS GAME', 'should i read journey to the west first?', '2024-09-17 16:09:58', '2024-09-17 16:09:58', NULL),
(2, 9, 37, 'how to crack this game?', 'asdkasdk', '2024-09-19 02:31:48', '2024-09-19 02:31:48', NULL),
(3, 11, 2, 'dfzdfd', 'fdsfdsf', '2024-09-22 04:47:40', '2024-09-22 04:47:40', NULL),
(4, 5, 2, 'hello guys', 'these are the community rules', '2024-09-23 03:20:06', '2024-09-23 03:20:06', NULL),
(5, 13, 2, 'csjdbfskdbfdsf', 'skdfhskfhdsf', '2024-09-25 01:54:57', '2024-09-25 01:54:57', NULL),
(6, 13, 2, 'sdfdsfdsf', 'fsdfds', '2024-09-25 01:55:07', '2024-09-25 01:55:07', NULL),
(7, 9, 38, 'dfdsf', 'fsdfd', '2024-09-25 03:14:57', '2024-09-25 03:14:57', NULL),
(8, 6, 2, 'Join na kayo!', 'build tayo business haha', '2024-10-13 07:36:25', '2024-10-13 07:36:25', NULL),
(9, 6, 3, 'kangkong order', 'may nagbebenta ba ng kangkong chips?', '2024-10-13 11:06:32', '2024-10-13 11:06:32', NULL),
(10, 16, 35, 'ef', 'edfed', '2024-10-13 11:23:12', '2024-10-13 11:23:12', NULL),
(12, 7, 2, 'dad', 'dawdaw', '2024-11-21 03:05:00', '2024-11-21 03:05:00', NULL),
(13, 25, 35, 'dadasd', 'dasdsdad', '2024-11-23 07:37:57', '2024-11-23 07:37:57', NULL),
(14, 25, 35, 'dfgdfg', 'dfgdfgdfg', '2024-11-25 15:49:07', '2024-11-25 15:49:07', 'uploads/67449c7333819_LendWorks-ERDupdated (1)-DFD.drawio.png'),
(15, 25, 35, '232323323', 'dasdasdasdasdas', '2024-11-25 15:50:22', '2024-11-25 15:50:22', NULL),
(16, 25, 35, '121212', '223232', '2024-11-25 16:16:03', '2024-11-25 16:16:03', 'uploads/6744a2c3e25ca_LendWorks_CFD_v2.png'),
(17, 25, 35, 'hgyujghjghfjhj', 'dad', '2024-11-25 16:18:07', '2024-11-25 16:18:07', NULL),
(18, 25, 35, 'xdf', '34343', '2024-11-25 16:26:59', '2024-11-25 16:26:59', 'uploads/6744a5533a2a5_132155.jpg'),
(19, 25, 35, 'dsad', '4343434343434', '2024-11-25 16:28:20', '2024-11-25 16:28:20', 'uploads/6744a5a41115f_337967.jpg'),
(20, 25, 35, 'dsad', '4343434343434', '2024-11-25 16:29:54', '2024-11-25 16:29:54', 'uploads/6744a602b148a_337967.jpg'),
(21, 24, 35, 'fdsfsdfdfg45454', 'esrsdfdf', '2024-11-25 16:42:58', '2024-11-25 16:42:58', 'uploads/6744a9129489b_5922598.jpg'),
(22, 18, 35, 'rewrew', 'rewrewrewrewr', '2024-11-25 17:09:07', '2024-11-25 17:09:07', 'uploads/6744af3396150_2651908.png'),
(23, 18, 35, 'dsad', 'sdasdsad', '2024-11-25 17:09:39', '2024-11-25 17:09:39', 'uploads/6744af539bf3e_LendWorks_CFD_v2.png'),
(24, 27, 2, 'dasdsa', 'dasdasdd', '2024-12-06 12:58:34', '2024-12-06 12:58:34', NULL),
(25, 25, 2, 'wqee', 'wrffefsfsdf', '2024-12-08 01:25:58', '2024-12-08 01:25:58', NULL),
(26, 25, 35, 'webpage', 'webpages', '2024-12-09 12:57:20', '2024-12-09 12:57:20', 'uploads/6756e9306d920_Screenshot 2024-10-04 111932.png'),
(27, 24, 2, 'ppafkmh', '23343434', '2024-12-10 02:10:01', '2024-12-10 02:10:01', 'uploads/6757a2f98ec90_Screenshot 2024-10-16 230547.png');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_tags`
--

CREATE TABLE `community_post_tags` (
  `PostID` int(11) NOT NULL,
  `TagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_post_tags`
--

INSERT INTO `community_post_tags` (`PostID`, `TagID`) VALUES
(20, 98),
(20, 133),
(20, 142),
(20, 156),
(21, 83),
(21, 92),
(21, 96),
(21, 115),
(22, 122),
(22, 155),
(23, 111),
(25, 158),
(26, 117),
(27, 117);

-- --------------------------------------------------------

--
-- Table structure for table `community_replies`
--

CREATE TABLE `community_replies` (
  `ReplyID` int(11) NOT NULL,
  `CommentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_replies`
--

INSERT INTO `community_replies` (`ReplyID`, `CommentID`, `UserID`, `Content`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 3, 3, 'jkkj', '2024-10-08 01:17:46', '2024-10-08 01:17:46'),
(2, 3, 3, 'jkkj', '2024-10-08 01:19:32', '2024-10-08 01:19:32'),
(3, 3, 3, 'DSDSDSD', '2024-10-08 01:21:04', '2024-10-08 01:21:04'),
(4, 5, 3, 'dsadsad', '2024-10-13 11:06:42', '2024-10-13 11:06:42'),
(5, 6, 35, 'fsdfsdfsdfdf', '2024-11-25 12:17:52', '2024-11-25 12:17:52'),
(6, 6, 35, 'fsdfsdfsdf', '2024-11-25 12:17:57', '2024-11-25 12:17:57'),
(7, 11, 35, 'gdfgdfg', '2024-11-25 17:22:03', '2024-11-25 17:22:03'),
(8, 12, 2, 'ssddasd', '2024-12-08 01:32:29', '2024-12-08 01:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `FollowID` int(11) NOT NULL,
  `FollowerID` int(11) NOT NULL,
  `FollowingID` int(11) NOT NULL,
  `FollowDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`FollowID`, `FollowerID`, `FollowingID`, `FollowDate`) VALUES
(43, 32, 29, '2024-09-14 16:11:51'),
(44, 32, 3, '2024-09-14 16:12:02'),
(45, 35, 2, '2024-09-14 16:26:57'),
(47, 2, 11, '2024-09-15 01:24:41'),
(52, 29, 3, '2024-09-16 00:33:47'),
(53, 29, 32, '2024-09-16 01:37:31'),
(54, 3, 14, '2024-09-16 01:45:14'),
(55, 3, 24, '2024-09-16 01:45:20'),
(56, 3, 35, '2024-09-16 01:45:27'),
(57, 3, 36, '2024-09-16 01:45:30'),
(58, 2, 7, '2024-09-16 15:36:31'),
(59, 2, 37, '2024-09-19 02:32:55'),
(60, 37, 2, '2024-09-19 02:33:14'),
(62, 3, 6, '2024-09-25 01:53:51'),
(71, 3, 11, '2024-10-13 10:47:08'),
(77, 42, 2, '2024-11-19 10:56:54'),
(83, 2, 38, '2024-11-20 15:19:52'),
(90, 2, 30, '2024-11-20 15:40:04'),
(91, 2, 31, '2024-11-20 15:40:11'),
(97, 2, 3, '2024-11-20 16:11:08'),
(104, 3, 2, '2024-11-21 15:21:32'),
(109, 2, 35, '2024-12-06 12:43:31'),
(110, 2, 5, '2024-12-06 13:17:31'),
(111, 8, 2, '2024-12-06 15:10:39'),
(112, 35, 3, '2024-12-10 07:09:38'),
(113, 47, 2, '2024-12-15 02:35:30'),
(114, 48, 2, '2025-01-02 09:07:40'),
(115, 48, 3, '2025-01-02 09:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `LikeID` int(11) NOT NULL,
  `PostID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `LikedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`LikeID`, `PostID`, `UserID`, `LikedAt`) VALUES
(174, 22, 2, '2024-11-22 14:37:22'),
(176, 36, 2, '2024-11-22 14:37:27'),
(177, 37, 2, '2024-11-22 14:37:28'),
(178, 24, 2, '2024-11-22 14:37:30'),
(179, 44, 2, '2024-11-22 14:37:34'),
(180, 43, 2, '2024-11-22 14:37:37'),
(181, 80, 2, '2024-11-22 14:37:40'),
(183, 178, 35, '2024-11-23 03:01:32'),
(184, 182, 35, '2024-11-23 05:23:08'),
(189, 35, 35, '2024-11-23 05:46:57'),
(190, 36, 35, '2024-11-23 05:47:00'),
(191, 118, 35, '2024-11-23 05:47:07'),
(193, 184, 35, '2024-11-23 06:16:42'),
(194, 183, 35, '2024-11-23 06:17:07'),
(199, 189, 35, '2024-11-23 06:43:17'),
(200, 181, 35, '2024-11-23 06:43:31'),
(206, 182, 2, '2024-11-23 08:13:21'),
(208, 195, 35, '2024-11-23 08:25:49'),
(211, 201, 35, '2024-11-23 11:11:26'),
(213, 202, 2, '2024-11-23 12:15:17'),
(214, 202, 35, '2024-11-23 12:36:22'),
(215, 204, 35, '2024-11-23 13:01:30'),
(216, 37, 35, '2024-11-23 13:59:02'),
(221, 207, 2, '2024-12-06 03:42:57'),
(223, 206, 2, '2024-12-06 10:00:17'),
(234, 149, 2, '2024-12-06 12:44:30'),
(238, 208, 2, '2024-12-06 12:58:16'),
(241, 198, 2, '2024-12-06 13:31:17'),
(243, 184, 2, '2024-12-06 13:47:44'),
(244, 205, 2, '2024-12-06 13:47:51'),
(245, 209, 35, '2024-12-06 13:54:51'),
(249, 204, 2, '2024-12-06 14:05:32'),
(263, 181, 2, '2024-12-06 14:08:46'),
(264, 179, 2, '2024-12-06 14:08:47'),
(265, 180, 2, '2024-12-06 14:08:50'),
(266, 183, 2, '2024-12-06 14:08:52'),
(267, 189, 2, '2024-12-06 14:08:59'),
(269, 173, 2, '2024-12-06 14:11:15'),
(271, 123, 2, '2024-12-06 14:53:46'),
(272, 209, 2, '2024-12-06 15:00:43'),
(273, 166, 2, '2024-12-06 15:01:50'),
(274, 38, 8, '2024-12-06 15:09:43'),
(276, 208, 8, '2024-12-06 15:09:55'),
(277, 207, 8, '2024-12-06 15:10:15'),
(278, 178, 8, '2024-12-06 15:10:44'),
(279, 37, 8, '2024-12-06 15:24:11'),
(283, 35, 8, '2024-12-06 15:25:42'),
(284, 206, 8, '2024-12-06 15:25:44'),
(285, 209, 8, '2024-12-06 15:26:54'),
(292, 211, 2, '2024-12-07 09:46:12'),
(294, 179, 3, '2024-12-07 11:43:18'),
(295, 39, 3, '2024-12-07 12:21:26'),
(297, 39, 2, '2024-12-07 12:41:48'),
(298, 239, 3, '2024-12-07 12:42:33'),
(299, 38, 2, '2024-12-07 12:57:21'),
(302, 35, 3, '2024-12-08 09:01:17'),
(303, 178, 3, '2024-12-08 09:01:23'),
(305, 239, 2, '2024-12-09 06:33:58'),
(306, 237, 2, '2024-12-09 07:53:22'),
(310, 242, 2, '2024-12-09 17:24:59'),
(312, 194, 35, '2024-12-10 07:12:18'),
(313, 242, 35, '2024-12-10 07:18:00'),
(315, 242, 47, '2024-12-15 02:31:35'),
(316, 244, 47, '2024-12-15 02:34:24'),
(317, 236, 47, '2024-12-15 02:35:17'),
(318, 190, 2, '2024-12-15 05:22:09'),
(319, 244, 3, '2024-12-15 08:48:19'),
(320, 244, 2, '2024-12-16 03:51:33'),
(321, 248, 2, '2024-12-16 11:07:54'),
(323, 35, 2, '2024-12-16 11:51:10'),
(324, 256, 2, '2024-12-16 11:51:17'),
(325, 257, 2, '2024-12-16 15:17:28'),
(326, 257, 8, '2024-12-17 02:55:41'),
(327, 252, 2, '2024-12-19 13:31:18'),
(328, 22, 48, '2025-01-02 08:54:41'),
(329, 258, 48, '2025-01-02 08:55:52'),
(330, 257, 48, '2025-01-02 09:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE `message_attachments` (
  `AttachmentID` int(11) NOT NULL,
  `MessageID` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FileType` varchar(50) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileSize` int(11) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_attachments`
--

INSERT INTO `message_attachments` (`AttachmentID`, `MessageID`, `FileName`, `FileType`, `FilePath`, `FileSize`, `UploadedAt`) VALUES
(1, 2, 'extracted_table_definitions.sql', 'application/octet-stream', 'uploads/extracted_table_definitions.sql', 0, '2024-12-08 03:44:21'),
(2, 5, 'Screenshot 2024-08-24 222425.png', 'image/png', 'uploads/Screenshot 2024-08-24 222425.png', 0, '2024-12-08 04:12:07'),
(3, 6, '1006(1).mp4', 'video/mp4', 'uploads/1006(1).mp4', 0, '2024-12-08 04:12:38'),
(4, 24, 'Screenshot 2024-08-24 222425.png', 'image/png', 'uploads/675532a47a039_Screenshot 2024-08-24 222425.png', 0, '2024-12-08 05:46:12'),
(5, 26, '1006(1).mp4', 'video/mp4', 'uploads/675532c6d53a4_1006(1).mp4', 0, '2024-12-08 05:46:46'),
(6, 28, '1006(2).mp4', 'video/mp4', 'uploads/675532e69f367_1006(2).mp4', 0, '2024-12-08 05:47:18'),
(7, 34, 'Screenshot 2024-08-24 222425.png', 'image/png', 'uploads/675535ad6724b_Screenshot 2024-08-24 222425.png', 0, '2024-12-08 05:59:09'),
(8, 38, 'Screenshot 2024-08-24 222425.png', 'image/png', 'uploads/6755383f9f0ab_Screenshot 2024-08-24 222425.png', 0, '2024-12-08 06:10:07'),
(9, 41, 'images (37).jpeg', 'image/jpeg', 'uploads/67559a15eefec_images (37).jpeg', 0, '2024-12-08 13:07:33'),
(10, 44, 'Screenshot 2024-12-02 140053.png', 'image/png', 'uploads/6757e36c254b7_Screenshot 2024-12-02 140053.png', 0, '2024-12-10 06:45:00'),
(11, 45, '1006(2).mp4', 'video/mp4', 'uploads/6757e6d917dfc_1006(2).mp4', 0, '2024-12-10 06:59:37'),
(12, 47, 'Screenshot 2024-08-02 110655.png', 'image/png', 'uploads/6757e74d34a13_Screenshot 2024-08-02 110655.png', 0, '2024-12-10 07:01:33'),
(13, 54, 'Online Medicine Shopping System.pdf', 'application/pdf', 'uploads/675e92df70c35_Online Medicine Shopping System.pdf', 0, '2024-12-15 08:27:11'),
(14, 60, '0104(1).mp4', 'video/mp4', 'uploads/677a9ef86fc27_0104(1).mp4', 0, '2025-01-05 15:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `message_media`
--

CREATE TABLE `message_media` (
  `MediaID` int(11) NOT NULL,
  `MessageID` int(11) NOT NULL,
  `MediaType` enum('image','video','audio') NOT NULL,
  `OriginalPath` varchar(255) NOT NULL,
  `ThumbnailPath` varchar(255) DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Width` int(11) DEFAULT NULL,
  `Height` int(11) DEFAULT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_reactions`
--

CREATE TABLE `message_reactions` (
  `ReactionID` int(11) NOT NULL,
  `MessageID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ReactionType` varchar(20) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_reactions`
--

INSERT INTO `message_reactions` (`ReactionID`, `MessageID`, `UserID`, `ReactionType`, `CreatedAt`) VALUES
(2, 33, 2, '😄', '2024-12-08 06:07:18'),
(4, 36, 2, '👍', '2024-12-08 06:07:42'),
(5, 34, 3, '😮', '2024-12-08 06:09:31'),
(6, 36, 3, '❤️', '2024-12-08 06:09:38'),
(7, 33, 3, '😄', '2024-12-08 06:09:45'),
(8, 32, 3, '😮', '2024-12-08 06:44:00'),
(9, 41, 35, '😮', '2024-12-09 10:55:34'),
(10, 44, 35, '👍', '2024-12-10 06:45:06'),
(12, 46, 35, '❤️', '2024-12-10 07:00:49'),
(13, 3, 35, '😮', '2024-12-10 07:00:53'),
(14, 47, 35, '👍', '2024-12-10 07:01:37'),
(41, 46, 2, '😮', '2024-12-15 06:30:55'),
(58, 50, 2, '👍', '2024-12-15 08:09:58'),
(63, 4, 2, '😮', '2024-12-16 15:21:46'),
(64, 56, 48, '😮', '2025-01-02 09:06:01'),
(65, 58, 3, '😮', '2025-01-05 14:38:47'),
(68, 3, 2, '😮', '2025-01-06 11:18:47'),
(73, 47, 2, '👍', '2025-01-06 11:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `message_read_status`
--

CREATE TABLE `message_read_status` (
  `StatusID` int(11) NOT NULL,
  `MessageID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ReadAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationID` int(11) NOT NULL,
  `RecipientID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Type` enum('comment','like','reply','follow','community') NOT NULL,
  `Content` text NOT NULL,
  `ReferenceID` int(11) DEFAULT NULL,
  `Seen` tinyint(1) DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`NotificationID`, `RecipientID`, `UserID`, `Type`, `Content`, `ReferenceID`, `Seen`, `CreatedAt`) VALUES
(1, 35, 2, 'like', 'your post gained a point.', 198, 1, '2024-12-06 13:31:17'),
(2, 2, 35, 'like', 'your post gained a point.', 209, 1, '2024-12-06 13:45:52'),
(3, 35, 2, 'like', 'your post gained a point.', 184, 0, '2024-12-06 13:47:44'),
(4, 35, 2, 'like', 'your post gained a point.', 205, 0, '2024-12-06 13:47:51'),
(5, 2, 35, 'like', 'your post gained a point.', 209, 1, '2024-12-06 13:54:51'),
(6, 37, 2, 'like', 'your post gained a point.', 123, 0, '2024-12-06 14:03:35'),
(7, 37, 2, 'like', 'your post gained a point.', 123, 0, '2024-12-06 14:03:39'),
(8, 35, 2, 'like', 'your post gained a point.', 204, 0, '2024-12-06 14:05:32'),
(9, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:45'),
(10, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:47'),
(11, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:49'),
(12, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:50'),
(13, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:55'),
(14, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:56'),
(15, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:57'),
(16, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:58'),
(17, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:58'),
(18, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:05:59'),
(19, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:06:00'),
(20, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:06:01'),
(21, 35, 2, 'like', 'your post gained a point.', 181, 0, '2024-12-06 14:08:46'),
(22, 35, 2, 'like', 'your post gained a point.', 179, 0, '2024-12-06 14:08:47'),
(23, 35, 2, 'like', '0', 180, 0, '2024-12-06 14:08:50'),
(24, 35, 2, 'like', '0', 183, 0, '2024-12-06 14:08:52'),
(25, 35, 2, 'like', '0', 189, 0, '2024-12-06 14:08:59'),
(26, 29, 2, 'like', '0', 173, 0, '2024-12-06 14:11:15'),
(27, 37, 2, 'like', '0', 123, 0, '2024-12-06 14:12:26'),
(28, 37, 2, 'like', '0', 123, 0, '2024-12-06 14:53:46'),
(29, 42, 2, 'like', '0', 166, 0, '2024-12-06 15:01:50'),
(30, 2, 8, 'like', '0', 209, 0, '2024-12-06 15:09:52'),
(31, 2, 8, 'like', '0', 208, 0, '2024-12-06 15:09:55'),
(32, 2, 8, 'like', '0', 207, 0, '2024-12-06 15:10:16'),
(33, 2, 8, 'like', '0', 178, 0, '2024-12-06 15:10:44'),
(34, 5, 8, 'like', '0', 37, 0, '2024-12-06 15:24:11'),
(35, 3, 8, 'like', '0', 39, 1, '2024-12-06 15:25:34'),
(36, 3, 8, 'like', '0', 24, 1, '2024-12-06 15:25:36'),
(37, 3, 8, 'like', '0', 39, 1, '2024-12-06 15:25:39'),
(38, 2, 8, 'like', '0', 35, 0, '2024-12-06 15:25:42'),
(39, 2, 8, 'like', '0', 206, 0, '2024-12-06 15:25:44'),
(40, 2, 8, 'like', '0', 209, 0, '2024-12-06 15:26:54'),
(41, 35, 3, 'like', '0', 179, 0, '2024-12-07 11:43:18'),
(42, 2, 3, 'like', '0', 241, 0, '2024-12-07 12:23:17'),
(43, 3, 2, 'like', '0', 39, 1, '2024-12-07 12:41:48'),
(44, 2, 3, 'like', '0', 239, 0, '2024-12-07 12:42:33'),
(45, 8, 2, 'like', '0', 38, 0, '2024-12-07 12:57:21'),
(46, 2, 8, 'like', '0', 241, 1, '2024-12-07 13:20:49'),
(47, 2, 3, 'like', '0', 35, 1, '2024-12-08 09:01:17'),
(48, 2, 3, 'like', '0', 178, 1, '2024-12-08 09:01:23'),
(49, 2, 35, 'like', '0', 242, 1, '2024-12-09 09:50:21'),
(50, 2, 35, 'like', '0', 242, 1, '2024-12-10 07:03:40'),
(51, 2, 35, 'like', '0', 242, 1, '2024-12-10 07:18:00'),
(52, 2, 35, 'like', '0', 240, 1, '2024-12-10 07:19:20'),
(53, 2, 47, 'like', '0', 242, 1, '2024-12-15 02:31:35'),
(54, 2, 47, 'like', '0', 236, 1, '2024-12-15 02:35:17'),
(55, 47, 3, 'like', '0', 244, 0, '2024-12-15 08:48:19'),
(56, 47, 2, 'like', '0', 244, 0, '2024-12-16 03:51:33'),
(57, 2, 8, 'like', '0', 257, 1, '2024-12-17 02:55:41'),
(58, 3, 48, 'like', '0', 22, 0, '2025-01-02 08:54:41'),
(59, 2, 48, 'like', '0', 257, 1, '2025-01-02 09:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `PostID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `PhotoPath` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`PostID`, `UserID`, `Title`, `Content`, `PhotoPath`, `CreatedAt`, `updatedAt`) VALUES
(22, 3, 'Balancing Academics and Social Life in College', 'I\'m struggling to find a balance between focusing on my studies and making time for socializing and extracurricular activities in college. How do you manage your time effectively to excel academically while still enjoying the college experience?', '', '2024-05-11 07:51:15', '2024-09-13 02:26:38'),
(23, 3, 'Navigating Dorm Life: Tips and Tricks', 'Moving into the dorms can be overwhelming, especially for freshmen. What are some essential tips and tricks for surviving and thriving in dorm life? From dealing with roommates to managing communal spaces, share your experiences and advice!', '', '2024-05-11 08:09:54', '2024-09-13 02:26:38'),
(24, 3, 'Dealing with Homesickness in College', 'Being away from home for the first time can trigger feelings of homesickness for many college students. How do you cope with homesickness and adjust to your new environment? Share your strategies and experiences to help others going through the same challenges.', '', '2024-05-11 08:09:57', '2024-09-13 02:26:38'),
(35, 2, '', 'Choosing a major and deciding on a career path can feel overwhelming for many college students. How did you discover your passion and decide on your major? Share your journey, tips, and advice for fellow students who are still exploring their options.', 'uploads/inspirational-one-words.png', '2024-05-11 13:31:39', '2024-11-23 11:46:57'),
(36, 6, 'Managing Stress and Mental Health in College', 'College life can be stressful, with academic pressures, social challenges, and the transition to adulthood. How do you prioritize self-care and manage stress while juggling your responsibilities? Share your coping strategies, wellness tips, and support resources for maintaining good mental health in college.', '', '2024-05-11 13:35:45', '2024-09-13 02:26:38'),
(37, 5, 'Making the Most of College: Tips for Success', 'College is a unique opportunity for personal growth, academic exploration, and building lifelong connections. What advice would you give to incoming freshmen or current students who want to make the most of their college experience? Share your top tips for academic success, personal development, and seizing opportunities.', '', '2024-05-11 13:37:20', '2024-09-13 02:26:38'),
(38, 8, 'Navigating Relationships in College: Friendships, Dating, and More', 'College is not only about academics but also about building relationships with peers, friends, and potential romantic partners. How do you navigate friendships, dating, and other relationships in college? Share your experiences, challenges, and advice for maintaining healthy relationships while focusing on your studies.', '', '2024-05-11 13:48:13', '2024-09-13 02:26:38'),
(39, 3, 'Staying Healthy and Fit in College: Tips for Maintaining a Balanced Lifestyle', 'Maintaining a healthy lifestyle can be challenging in college, with late-night study sessions, fast food temptations, and limited time for exercise. How do you stay healthy and fit while juggling academic and social commitments? Share your favorite wellness tips, healthy eating hacks, and workout routines for staying in shape.', '', '2024-05-11 14:01:23', '2024-09-13 02:26:38'),
(41, 9, 'Career Tips', 'What are the habits that you incorporate in your life that will help you in the future?  ', '', '2024-05-11 12:05:42', '2024-09-13 02:26:38'),
(43, 9, 'Scholarship', 'Anyone who took DOST scholarship program? specifically JLSS. what are the requirements that i need to submit? thanks ', '', '2024-05-11 12:15:19', '2024-09-13 02:26:38'),
(44, 10, 'Basketball', 'Any athletes in here who want to join a friendly match? comment your contacts here so i can add you.', '', '2024-05-11 12:29:34', '2024-09-13 02:26:38'),
(77, 15, 'Embracing the Code: A Computer Science Journey', 'Hey fellow CS majors!\r\n\r\nAs a student navigating through the world of algorithms and programming languages, I find myself oscillating between bouts of excitement and moments of frustration. But hey, isn\'t that the charm of being a CS major?\r\n\r\nOne thing that constantly amazes me is the sense of community within our department. Whether we\'re collaborating on a group project or debugging code late into the night, there\'s always someone willing to lend a hand or share a meme to lighten the mood. It\'s this camaraderie that makes the challenges of coding feel less daunting.\r\n\r\nOf course, college life isn\'t just about academics. From hackathons to LAN parties, there\'s never a dull moment when you\'re surrounded by fellow tech enthusiasts. And let\'s not forget about the legendary pizza-fueled study sessions that somehow always end with a deep dive into the latest tech trends.\r\n\r\nSure, there are times when the workload seems overwhelming, and the dreaded imposter syndrome rears its ugly head. But then I remember why I chose this path—to create, innovate, and make a tangible impact on the world through technology.\r\n\r\nSo here\'s to late nights in the lab, endless lines of code, and the friendships forged over shared struggles and triumphs. Together, let\'s continue to embrace the code and redefine what\'s possible.\r\n\r\nKeep coding.', '', '2024-05-15 09:29:08', '2024-09-13 02:26:38'),
(78, 16, ' Seeking Insights: Architecture Community, I Need Your Expertise!', 'Hello fellow architecture enthusiasts!\r\n\r\nI hope you\'re all doing well. I\'m currently working on a project for my architecture studio class, and I could really use some advice and insights from those with more experience in the field.\r\n\r\nI\'m tasked with designing a sustainable housing complex that incorporates passive design strategies to minimize energy consumption. While I have some ideas in mind, I\'m struggling with the best way to integrate green spaces into the design to promote biodiversity and enhance the residents\' quality of life.\r\n\r\nIf any of you have worked on similar projects or have expertise in sustainable architecture, I would greatly appreciate any tips or suggestions you could offer. Specifically, I\'m interested in innovative approaches to incorporating green roofs, vertical gardens, and communal outdoor spaces into the design.\r\n\r\nAdditionally, if you have any recommendations for resources—books, articles, case studies—that delve into sustainable architecture and biophilic design, please feel free to share them. I\'m always eager to expand my knowledge and explore new ideas.\r\n\r\nThank you in advance for your assistance! I truly value the expertise and insights of this community, and I\'m excited to see where your guidance leads me in my design journey.', '', '2024-05-15 09:44:40', '2024-09-13 02:26:38'),
(80, 17, 'help!', 'any books on literature?', '', '2024-05-15 14:26:58', '2024-09-13 02:26:38'),
(96, 21, 'college', 'hello freshmen', '', '2024-06-11 11:03:08', '2024-09-13 02:26:38'),
(97, 21, 'fffff', 'unu kunu', '', '2024-06-11 11:06:19', '2024-09-13 02:26:38'),
(100, 22, 'fgj', 'hghkghjfjhgjghjgh', '', '2024-06-11 14:01:39', '2024-09-13 02:26:38'),
(113, 27, 'sadaddd', 'ewdwerweewr', '', '2024-09-09 03:11:23', '2024-09-13 02:26:38'),
(115, 29, 'hello', 'damned world', '', '2024-09-09 04:40:10', '2024-09-13 02:30:10'),
(116, 30, 'Business Idea', 'Anyone can join me to brainstorm a business idea?\r\n\r\nyou\'re all welcome', '', '2024-09-12 12:09:00', '2024-09-13 02:26:38'),
(118, 31, 'i\'m jimmy', 'nice meeting y\'all', '', '2024-09-12 12:17:41', '2024-09-13 02:26:38'),
(122, 29, 'dwdd', 'sdsdsd', '', '2024-09-16 01:27:20', '2024-09-16 01:27:20'),
(123, 37, 'si huesca pogi tlaga', 'hi po huesca crush kita', '', '2024-09-19 02:30:16', '2024-09-19 02:30:16'),
(149, 38, 'hello', 'ako si fgfggfgfgff', '', '2024-09-25 02:48:36', '2024-09-25 03:16:59'),
(166, 42, 'efdf', 'dfdfdsfsdf', '', '2024-11-19 10:57:32', '2024-11-19 10:57:32'),
(171, 29, 'dadasddasasda', 'asdsad', '', '2024-11-21 02:58:19', '2024-11-21 02:58:19'),
(172, 29, 'asdasd', 'asdsad', '', '2024-11-21 02:59:57', '2024-11-21 02:59:57'),
(173, 29, 'asdsadas', 'asd', '', '2024-11-21 03:00:03', '2024-11-21 03:00:03'),
(178, 2, 'xcvxdsadasddwadawd', 'xcvxcvasdasddds', 'uploads/images.jpg', '2024-11-22 15:40:19', '2024-11-23 12:13:06'),
(179, 35, 'dasdsa', 'asdasdasdd', '', '2024-11-23 04:23:24', '2024-11-23 04:23:24'),
(180, 35, 'd', 'sadsadsaas', '', '2024-11-23 04:23:52', '2024-11-23 04:23:52'),
(181, 35, 'sads', 'sadasd', NULL, '2024-11-23 04:33:30', '2024-11-23 04:33:30'),
(182, 35, 'dwa', 'dwadawd', 'uploads/Green Aesthetic Poster Portrait.png', '2024-11-23 04:41:03', '2024-11-23 04:41:03'),
(183, 35, 'gojo satorou', 'sad', 'uploads/images (90).jpeg', '2024-11-23 05:29:59', '2024-11-23 05:29:59'),
(184, 35, 'Chainsaw man', 'damn this is wild!', 'uploads/images (93).jpeg', '2024-11-23 06:12:46', '2024-11-23 06:12:46'),
(189, 35, 'adad', 'asdasdasdasd', 'uploads/258200.jpg', '2024-11-23 06:42:18', '2024-11-23 06:42:18'),
(190, NULL, 'test tag', 'test tag', 'uploads/peakpx (1).jpg', '2024-11-23 07:06:01', '2024-11-23 07:06:01'),
(191, NULL, 'dasd', 'sadasdasd', 'uploads/737111.png', '2024-11-23 07:06:42', '2024-11-23 07:06:42'),
(192, NULL, 'dfgd', 'gdfgdfg', 'uploads/5922447.jpg', '2024-11-23 07:09:00', '2024-11-23 07:09:00'),
(193, NULL, 'dsd', 'adasdasdasd', 'uploads/peakpx (2).jpg', '2024-11-23 07:17:37', '2024-11-23 07:17:37'),
(194, NULL, 'dsad', 'sdasdasdas', 'uploads/5922598.jpg', '2024-11-23 07:19:34', '2024-11-23 07:19:34'),
(195, 35, 'applle', 'dasdadas', 'uploads/5922493.jpg', '2024-11-23 07:24:34', '2024-11-23 07:24:34'),
(198, 35, 'empty', 'sad all the way', '', '2024-11-23 07:37:01', '2024-11-23 12:18:18'),
(201, 35, 'Things will not always go your way', 'and that\'s what makes life beautiful. mate trust me', 'uploads/Screenshot 2024-08-02 213815.png', '2024-11-23 09:09:13', '2024-11-23 12:27:36'),
(202, 2, 'true', 'hahaha', 'uploads/male-nurse-meme-1.jpg', '2024-11-23 12:14:50', '2024-11-23 12:14:50'),
(204, 35, 'COMSCI', 'GG na wala na', 'uploads/665d65fda1c2a.png', '2024-11-23 13:00:04', '2024-11-25 13:57:03'),
(205, 35, 'ddsd', 'dasdasdad', 'uploads/LendWorks-ERDupdated (1)-Page-5.drawio.png', '2024-11-25 15:46:29', '2024-11-25 15:46:29'),
(206, 2, 'fsdfsdf', 'sdfsdfsdfsdf', 'uploads/LendWorks-ERDupdated (1)-LENDWORKS ERD.drawio (2).png', '2024-12-05 18:03:58', '2024-12-05 18:03:58'),
(207, 2, 'wqeewqe', 'wqewqewqewqee', 'uploads/img_0511.jpg', '2024-12-06 03:42:54', '2024-12-06 03:42:54'),
(208, 2, 'dsad', 'sadadadasd', NULL, '2024-12-06 10:47:41', '2024-12-06 10:47:41'),
(209, 2, 'dsadsad', 'asdasdasd', NULL, '2024-12-06 12:43:06', '2024-12-06 12:43:06'),
(211, 2, 'A collection of mesmerizing view', 'got to pause and think about my life each time.', 'uploads/67541e569d590_Screenshot 2024-08-02 111029.png', '2024-12-07 02:21:53', '2024-12-07 10:07:18'),
(236, 2, 'wde', 'adasdasd', 'uploads/67541e4595b59_Screenshot 2024-08-26 132537.png', '2024-12-07 09:50:56', '2024-12-07 10:07:01'),
(237, 2, 'weyytytr', 'yrtyrtyrty', 'uploads/67541f034800f_Screenshot 2024-08-01 150745.png', '2024-12-07 10:10:11', '2024-12-07 10:10:11'),
(239, 2, 'sdggggggg', 'gfgdfsdfsfdfdf', 'uploads/67541f3be63f0_Screenshot 2024-10-05 220740.png', '2024-12-07 10:11:07', '2024-12-07 10:11:07'),
(242, 2, 'Graphs and Balance sheets', 'Feel free to analyze them and share your thoughts', 'uploads/6756939e1709f_Screenshot 2024-10-30 195408.png', '2024-12-09 06:52:14', '2024-12-09 06:52:14'),
(244, 47, 'LESSON NOTES FOR ADVANCE DATABASE', 'notes that may be useful for you', 'uploads/675e402950a21_Screenshot 2024-12-02 140023.png', '2024-12-15 02:34:17', '2024-12-15 02:34:17'),
(245, 2, 'LENDWORKS', 'company', 'uploads/675fae4fb402a_[removal.ai]_8dd3a076-6790-442e-8adb-619b289efb9e-e67769f5-a176-437c-8900-c37a347b51df_LIM52P.png', '2024-12-16 04:36:31', '2024-12-16 04:36:31'),
(246, 2, 'Research project', 'research materials', NULL, '2024-12-16 04:40:59', '2024-12-16 04:40:59'),
(248, 2, 'Lesson materials', 'notes', 'uploads/675fb4a7c0b70_image-asset.jpeg', '2024-12-16 05:03:35', '2024-12-16 05:03:35'),
(249, 2, 'Link test', 'http://localhost/php-parctice/wshare%20admin%20latest/Wshare/wshare%20system%20(adjusted)/create_post.php', NULL, '2024-12-16 06:43:09', '2024-12-16 06:43:09'),
(250, 2, 'test text only', 'text only', NULL, '2024-12-16 10:36:29', '2024-12-16 10:36:29'),
(251, 2, 'test with tags', 'test with tags', NULL, '2024-12-16 10:36:46', '2024-12-16 10:36:46'),
(252, 2, 'test with photo and tags', 'test', 'uploads/676002d916114_img_0511.jpg', '2024-12-16 10:37:13', '2024-12-16 10:37:13'),
(253, 2, 'test with multiple photos and tags', 'test', 'uploads/67600342b3f11_Handwriting.jpg', '2024-12-16 10:38:58', '2024-12-16 10:38:58'),
(254, 2, 'test with photos and documents and tags', 'test', 'uploads/67600393ec45e_466909336_1104262674006053_7080248103325891903_n (2).png', '2024-12-16 10:40:19', '2024-12-16 10:40:19'),
(255, 2, 'test video only', 'test', NULL, '2024-12-16 10:40:57', '2024-12-16 10:40:57'),
(256, 2, 'test video with multiple photos', 'test', 'uploads/6760079c5f86b_Screenshot 2024-12-02 140006.png', '2024-12-16 10:57:32', '2024-12-16 10:57:32'),
(257, 2, 'test all attachments', 'test', 'uploads/67603ce0357d9_57d025262e7907875beb666b64788899_1.jpg', '2024-12-16 14:44:48', '2024-12-16 14:44:48'),
(258, 48, 'test', 'testing', 'uploads/677653bb4a282_127089.jpg', '2025-01-02 08:52:11', '2025-01-02 08:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `post_documents`
--

CREATE TABLE `post_documents` (
  `DocumentID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `DocumentPath` varchar(255) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_documents`
--

INSERT INTO `post_documents` (`DocumentID`, `PostID`, `DocumentPath`, `UploadedAt`) VALUES
(3, 248, 'uploads/documents/Sappari, 4-7pm (1).docx', '2024-12-16 05:03:35'),
(4, 254, 'uploads/documents/Sappari, 4-7pm (1) (2).docx', '2024-12-16 10:40:19'),
(5, 257, 'uploads/documents/SE-presentation.pdf', '2024-12-16 14:44:48'),
(6, 258, 'uploads/documents/Handouts-1680171201952.pdf', '2025-01-02 08:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `ImageID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `ImagePath` varchar(255) NOT NULL,
  `Caption` text DEFAULT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `DisplayOrder` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_images`
--

INSERT INTO `post_images` (`ImageID`, `PostID`, `ImagePath`, `Caption`, `UploadedAt`, `DisplayOrder`) VALUES
(3, 211, 'uploads/6753b1417b234_Screenshot 2024-08-02 163015.png', NULL, '2024-12-07 02:21:53', 2),
(4, 211, 'uploads/6753b1417afee_Screenshot 2024-08-02 110812.png', NULL, '2024-12-07 02:21:53', 3),
(5, 211, 'uploads/6753b1417ae9b_Screenshot 2024-08-02 110720.png', NULL, '2024-12-07 02:21:53', 4),
(50, 236, 'uploads/67541a80824e6_Screenshot 2024-08-26 132537.png', NULL, '2024-12-07 09:50:56', 0),
(51, 236, 'uploads/67541a8081eaa_Screenshot 2024-08-26 132519.png', NULL, '2024-12-07 09:50:56', 1),
(52, 236, 'uploads/67541a80829fd_Screenshot 2024-08-26 132616.png', NULL, '2024-12-07 09:50:56', 2),
(53, 236, 'uploads/67541a80827de_Screenshot 2024-08-26 132553.png', NULL, '2024-12-07 09:50:56', 3),
(54, 236, 'uploads/67541a8082cee_Screenshot 2024-08-26 132636.png', NULL, '2024-12-07 09:50:56', 4),
(55, 211, 'uploads/67541d148e596_Screenshot 2024-07-28 092521.png', NULL, '2024-12-07 10:01:56', 5),
(56, 239, 'uploads/67541f3be8549_Screenshot 2024-08-03 200735.png', NULL, '2024-12-07 10:11:07', 0),
(57, 239, 'uploads/67541f3be80a1_Screenshot 2024-08-03 200703.png', NULL, '2024-12-07 10:11:07', 1),
(58, 239, 'uploads/67541f3be82c9_Screenshot 2024-08-03 200723.png', NULL, '2024-12-07 10:11:07', 2),
(59, 239, 'uploads/67541f3be8740_Screenshot 2024-08-03 200749.png', NULL, '2024-12-07 10:11:07', 3),
(60, 239, 'uploads/67541f3be8938_Screenshot 2024-08-03 200805.png', NULL, '2024-12-07 10:11:07', 4),
(61, 239, 'uploads/67541f3be7951_Screenshot 2024-08-03 192830.png', NULL, '2024-12-07 10:11:07', 5),
(62, 239, 'uploads/67541f3be7c97_Screenshot 2024-08-03 192847.png', NULL, '2024-12-07 10:11:07', 6),
(63, 239, 'uploads/67541f3be8b46_Screenshot 2024-08-03 201027.png', NULL, '2024-12-07 10:11:07', 7),
(64, 239, 'uploads/67541f3be7ebc_Screenshot 2024-08-03 200605.png', NULL, '2024-12-07 10:11:07', 8),
(65, 239, 'uploads/67541f3be8de3_Screenshot 2024-08-03 201054.png', NULL, '2024-12-07 10:11:07', 9),
(71, 242, 'uploads/6756939e18fcd_Screenshot 2024-10-15 164055.png', NULL, '2024-12-09 06:52:14', 0),
(72, 242, 'uploads/6756939e19c54_Screenshot 2024-10-15 173231.png', NULL, '2024-12-09 06:52:14', 1),
(73, 242, 'uploads/6756939e19557_Screenshot 2024-10-15 172858.png', NULL, '2024-12-09 06:52:14', 2),
(74, 242, 'uploads/6756939e19974_Screenshot 2024-10-15 173109.png', NULL, '2024-12-09 06:52:14', 3),
(75, 242, 'uploads/6756939e1a60d_Screenshot 2024-10-15 230459.png', NULL, '2024-12-09 06:52:14', 4),
(76, 242, 'uploads/6756939e19fa6_Screenshot 2024-10-15 173318.png', NULL, '2024-12-09 06:52:14', 5),
(78, 242, 'uploads/6756a5bd8e10f_Screenshot 2024-09-18 211030.png', NULL, '2024-12-09 08:09:33', 6),
(85, 245, 'uploads/675fae4fb8340_{21867BB2-5412-4FDB-A37C-4CBEAC44F5B8}_processed.png', NULL, '2024-12-16 04:36:31', 0),
(86, 248, 'uploads/675fb4a7c341a_img_0511.jpg', NULL, '2024-12-16 05:03:35', 0),
(87, 248, 'uploads/675fb4a7c37a1_61I4RbsNvSL._AC_UF894,1000_QL80_.jpg', NULL, '2024-12-16 05:03:35', 1),
(88, 253, 'uploads/67600342b55d8_Handwriting.jpg', NULL, '2024-12-16 10:38:58', 0),
(89, 253, 'uploads/67600342b50f2_hand-icon.png', NULL, '2024-12-16 10:38:58', 1),
(90, 253, 'uploads/67600342b59f5_61I4RbsNvSL._AC_UF894,1000_QL80_.jpg', NULL, '2024-12-16 10:38:58', 2),
(91, 253, 'uploads/67600342b5f21_image-asset.jpeg', NULL, '2024-12-16 10:38:58', 3),
(92, 253, 'uploads/67600342b6388_LendWorks-ERDupdated (1)-LENDWORKS ERD.drawio (1).png', NULL, '2024-12-16 10:38:58', 4),
(93, 254, 'uploads/67600393ef378_466909336_1104262674006053_7080248103325891903_n (1).png', NULL, '2024-12-16 10:40:19', 0),
(94, 254, 'uploads/67600393efb7b_466909336_1104262674006053_7080248103325891903_n.png', NULL, '2024-12-16 10:40:19', 1),
(95, 256, 'uploads/6760079c62adc_Screenshot 2024-12-02 140006.png', NULL, '2024-12-16 10:57:32', 0),
(96, 256, 'uploads/6760079c639d7_Screenshot 2024-12-02 140053.png', NULL, '2024-12-16 10:57:32', 1),
(97, 256, 'uploads/6760079c645e6_Screenshot 2024-12-02 140111.png', NULL, '2024-12-16 10:57:32', 2),
(98, 256, 'uploads/6760079c6421e_Screenshot 2024-12-02 140101.png', NULL, '2024-12-16 10:57:32', 3),
(99, 256, 'uploads/6760079c633ec_Screenshot 2024-12-02 140040.png', NULL, '2024-12-16 10:57:32', 4),
(100, 256, 'uploads/6760079c63027_Screenshot 2024-12-02 140023.png', NULL, '2024-12-16 10:57:32', 5),
(101, 257, 'uploads/67603ce039755_232672.jpg', NULL, '2024-12-16 14:44:48', 0),
(102, 257, 'uploads/67603ce039b9a_5047008.jpg', NULL, '2024-12-16 14:44:48', 1),
(103, 257, 'uploads/67603ce039e91_wallpaperflare.com_wallpaper.jpg', NULL, '2024-12-16 14:44:48', 2),
(104, 258, 'uploads/677653bb4db3d_5922493.jpg', NULL, '2025-01-02 08:52:11', 0),
(105, 258, 'uploads/677653bb4dcf2_5922598.jpg', NULL, '2025-01-02 08:52:11', 0),
(106, 258, 'uploads/677653bb4ddf0_5922707.jpg', NULL, '2025-01-02 08:52:11', 0),
(107, 258, 'uploads/677653bb4ded1_6617357.jpg', NULL, '2025-01-02 08:52:11', 0),
(108, 258, 'uploads/677653bb4dfb0_city_silhouette_art_136751_3840x2160.jpg', NULL, '2025-01-02 08:52:11', 0),
(109, 258, 'uploads/677653bb4e0d2_crazy-expression-draw-260nw-1351230482.png', NULL, '2025-01-02 08:52:11', 0),
(110, 258, 'uploads/677653bb4e0d2_crazy-expression-draw-260nw-1351230482.png', NULL, '2025-01-02 08:52:11', 1),
(111, 258, 'uploads/677653bb4ded1_6617357.jpg', NULL, '2025-01-02 08:52:11', 2),
(112, 258, 'uploads/677653bb4ddf0_5922707.jpg', NULL, '2025-01-02 08:52:11', 3),
(113, 258, 'uploads/677653bb4db3d_5922493.jpg', NULL, '2025-01-02 08:52:11', 4),
(114, 258, 'uploads/677653bb4dcf2_5922598.jpg', NULL, '2025-01-02 08:52:11', 5),
(115, 258, 'uploads/677653bb4dfb0_city_silhouette_art_136751_3840x2160.jpg', NULL, '2025-01-02 08:52:11', 6);

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `PostID` int(11) NOT NULL,
  `TagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_tags`
--

INSERT INTO `post_tags` (`PostID`, `TagID`) VALUES
(35, 80),
(35, 102),
(35, 158),
(178, 111),
(178, 156),
(178, 158),
(194, 111),
(194, 132),
(194, 142),
(194, 156),
(195, 111),
(195, 132),
(195, 142),
(195, 156),
(198, 111),
(198, 132),
(198, 142),
(198, 156),
(201, 80),
(201, 126),
(201, 141),
(202, 80),
(202, 125),
(202, 154),
(202, 157),
(204, 80),
(204, 157),
(205, 114),
(205, 117),
(205, 119),
(206, 111),
(207, 156),
(208, 158),
(209, 111),
(211, 80),
(242, 117),
(242, 121),
(242, 135),
(244, 117),
(246, 112),
(248, 107),
(251, 102),
(252, 156),
(253, 107),
(253, 117),
(253, 136),
(254, 107),
(254, 117),
(257, 107),
(258, 132),
(258, 142);

-- --------------------------------------------------------

--
-- Table structure for table `post_videos`
--

CREATE TABLE `post_videos` (
  `VideoID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `VideoPath` varchar(255) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_videos`
--

INSERT INTO `post_videos` (`VideoID`, `PostID`, `VideoPath`, `UploadedAt`) VALUES
(1, 255, 'uploads/videos/676003b993d7b_1006(1).mp4', '2024-12-16 10:40:57'),
(2, 256, 'uploads/videos/6760079c61006_1006(1).mp4', '2024-12-16 10:57:32'),
(3, 256, 'uploads/videos/67603862ba13d_1006.mp4', '2024-12-16 14:25:38'),
(4, 257, 'uploads/videos/67603ce038768_1027 (1)(1).mp4', '2024-12-16 14:44:48'),
(5, 258, 'uploads/videos/677653bb4cc1a_1006(1).mp4', '2025-01-02 08:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `ReplyID` int(11) NOT NULL,
  `CommentID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`ReplyID`, `CommentID`, `UserID`, `Content`, `CreatedAt`, `updatedAt`) VALUES
(1, 23, 17, 'ill take note of that', '2024-05-15 13:29:22', '2024-09-13 02:37:22'),
(2, 26, 17, 'nice', '2024-05-15 13:56:30', '2024-09-13 02:37:22'),
(3, 23, 17, 'sds', '2024-05-15 14:34:46', '2024-09-13 02:37:22'),
(4, 23, 17, 'fdsfdsf', '2024-05-15 15:09:03', '2024-09-13 02:37:22'),
(5, 76, 17, 'aw', '2024-05-15 15:09:32', '2024-09-13 02:37:22'),
(6, 43, 17, 'welcome', '2024-05-15 15:24:09', '2024-09-13 02:37:22'),
(7, 23, 17, 'test', '2024-05-15 15:40:45', '2024-09-13 02:37:22'),
(8, 23, 2, 'what?', '2024-05-15 15:58:23', '2024-09-13 02:37:22'),
(9, 30, 11, 'wow thanks for this!', '2024-05-16 14:24:30', '2024-09-13 02:37:22'),
(10, 32, 11, 'sdsd', '2024-05-16 14:35:16', '2024-09-13 02:37:22'),
(11, 32, 11, 'hello', '2024-05-16 14:36:04', '2024-09-13 02:37:22'),
(12, 21, 2, 'hmm', '2024-05-20 23:54:50', '2024-09-13 02:37:22'),
(13, 74, 2, 'aww', '2024-05-21 05:05:39', '2024-09-13 02:37:22'),
(14, 79, 2, 'nice bro', '2024-06-04 20:44:54', '2024-09-13 02:37:22'),
(15, 31, 8, 'thanks po ate!', '2024-06-08 17:05:09', '2024-09-13 02:37:22'),
(16, 39, 8, 'may link ka po? ', '2024-06-08 17:06:04', '2024-09-13 02:37:22'),
(18, 32, 2, 'sorry', '2024-06-11 06:53:29', '2024-09-13 02:37:22'),
(19, 32, 2, '1', '2024-06-11 07:09:04', '2024-09-13 02:37:22'),
(21, 50, 2, '3', '2024-06-11 07:09:13', '2024-09-13 02:37:22'),
(22, 56, 2, '4', '2024-06-11 07:09:18', '2024-09-13 02:37:22'),
(23, 92, 2, 'vvvv', '2024-06-11 10:04:42', '2024-09-13 02:37:22'),
(24, 23, 2, 'ddsd', '2024-06-11 10:38:58', '2024-09-13 02:37:22'),
(25, 23, 2, 'li;;;;;;;;;', '2024-06-11 10:39:07', '2024-09-13 02:37:22'),
(26, 97, 2, 'dfcdf', '2024-06-11 11:45:54', '2024-09-13 02:37:22'),
(27, 25, 2, 'nice bro', '2024-06-11 13:34:29', '2024-09-13 02:37:22'),
(28, 23, 2, 'jsndns', '2024-08-19 03:13:31', '2024-09-13 02:37:22'),
(29, 31, 2, 'hello po!', '2024-08-19 03:14:27', '2024-09-13 02:37:22'),
(30, 26, 2, 'hello po\r\n', '2024-09-02 06:50:22', '2024-09-13 02:37:22'),
(31, 70, 2, 'nkjkjk', '2024-09-09 03:47:27', '2024-09-13 02:37:22'),
(32, 99, 2, 'srdrffgdgfdg\r\n', '2024-09-09 04:16:46', '2024-09-13 02:37:22'),
(35, 108, 35, 'fr', '2024-09-12 12:52:03', '2024-09-13 02:37:22'),
(36, 109, 2, 'Yeah', '2024-09-14 04:05:46', '2024-09-14 04:05:46'),
(37, 111, 2, 'ykuku', '2024-09-14 10:37:53', '2024-09-14 10:37:53'),
(38, 93, 2, 'ds', '2024-09-15 03:28:54', '2024-09-15 03:28:54'),
(40, 115, 37, 'eneebee', '2024-09-19 02:30:51', '2024-09-19 02:30:51'),
(41, 117, 38, 'sdlkfjsdlkfjkdsjf', '2024-09-25 02:49:05', '2024-09-25 02:49:05'),
(47, 112, 2, 'zczc', '2024-11-22 13:01:09', '2024-11-22 13:01:09'),
(48, 104, 2, 'fsfdsfsdfsf', '2024-11-22 13:08:17', '2024-11-22 13:08:17'),
(49, 126, 35, 'dasdasdasd', '2024-11-23 12:36:36', '2024-11-23 12:36:36'),
(50, 129, 2, 'dasdasdssa', '2024-12-06 04:46:35', '2024-12-06 04:46:35'),
(51, 130, 2, 'adsadasd', '2024-12-06 12:02:22', '2024-12-06 12:02:22'),
(52, 115, 2, 'wdewdwed', '2024-12-06 12:47:36', '2024-12-06 12:47:36'),
(56, 146, 35, 'dsdsd', '2024-12-10 07:02:36', '2024-12-10 07:02:36'),
(57, 146, 2, 'czzz', '2024-12-15 05:40:35', '2024-12-15 05:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `reply_attachments`
--

CREATE TABLE `reply_attachments` (
  `AttachmentID` int(11) NOT NULL,
  `ReplyID` int(11) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` enum('image','video','document') NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FileSize` int(11) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `ReportID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ReportedUserID` int(11) NOT NULL,
  `ReportType` enum('post','comment','user') NOT NULL,
  `TargetID` int(11) NOT NULL,
  `Violation` text NOT NULL,
  `EvidencePhoto` varchar(255) DEFAULT NULL,
  `Status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PostType` enum('general','community') NOT NULL DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`ReportID`, `UserID`, `ReportedUserID`, `ReportType`, `TargetID`, `Violation`, `EvidencePhoto`, `Status`, `CreatedAt`, `updatedAt`, `PostType`) VALUES
(1, 2, 35, 'post', 205, 'dasdasdasd', NULL, 'resolved', '2024-12-06 04:45:41', '2024-12-06 04:49:27', 'general'),
(2, 2, 2, 'post', 208, 'dsadasdd', NULL, 'reviewed', '2024-12-06 10:47:46', '2024-12-06 15:31:50', 'general'),
(3, 2, 2, 'post', 209, 'dsadasdasda', 'uploads/reports/6752f95ead192_LendWorks-ERDupdated (1)-LENDWORKS ERD.drawio (2).png', 'pending', '2024-12-06 13:17:18', '2024-12-06 13:17:18', 'general'),
(4, 3, 2, 'post', 240, 'acxzdczxczcz', NULL, 'pending', '2024-12-08 10:25:11', '2024-12-08 10:25:11', 'general'),
(5, 35, 35, 'post', 243, 'di naga sabi maayos', NULL, 'pending', '2024-12-09 14:14:44', '2024-12-09 14:14:44', 'general'),
(6, 35, 2, '', 25, 'wala kwenta', NULL, 'pending', '2024-12-09 14:16:29', '2024-12-09 14:16:29', 'general'),
(7, 35, 2, '', 25, 'gags', NULL, 'pending', '2024-12-09 14:17:34', '2024-12-09 14:27:49', 'general'),
(8, 35, 2, '', 25, 'gags', NULL, 'pending', '2024-12-09 14:17:34', '2024-12-09 14:17:34', 'general'),
(9, 35, 2, '', 25, 'zczxcxzcxcxz', NULL, 'pending', '2024-12-09 14:28:26', '2024-12-09 14:28:26', 'general'),
(10, 35, 35, '', 26, 'tangina', NULL, 'pending', '2024-12-09 14:53:26', '2024-12-09 14:53:26', 'community'),
(11, 35, 2, '', 25, 'inappropriate language', NULL, 'resolved', '2024-12-09 15:04:14', '2024-12-10 02:02:36', 'community'),
(12, 2, 35, '', 26, 'lklk', 'uploads/reports/6757219682e57_comm_sample2.png', 'resolved', '2024-12-09 16:57:58', '2024-12-10 02:02:34', 'community'),
(13, 3, 2, '', 27, 'unwanted behaviour', NULL, 'pending', '2024-12-10 02:10:50', '2024-12-10 02:10:50', 'community'),
(14, 35, 35, '', 26, 'walla', NULL, 'pending', '2024-12-10 06:07:22', '2024-12-15 11:13:27', 'community'),
(15, 35, 2, 'post', 242, 'ddddd', 'uploads/reports/6757e7a225ccd_comm_sample2.png', 'resolved', '2024-12-10 07:02:58', '2024-12-15 11:29:56', 'general'),
(16, 2, 35, '', 26, 'inappropriate language', NULL, 'resolved', '2024-12-10 07:07:17', '2024-12-16 03:37:31', 'community'),
(17, 2, 35, '', 18, 'trip lang', 'uploads/reports/675e7e7051595_[removal.ai]_8dd3a076-6790-442e-8adb-619b289efb9e-e67769f5-a176-437c-8900-c37a347b51df_LIM52P.png', 'resolved', '2024-12-15 07:00:00', '2024-12-16 15:09:54', 'community'),
(18, 48, 48, 'post', 258, 'bawal', 'uploads/reports/677654bbde20c_[removal.ai]_8dd3a076-6790-442e-8adb-619b289efb9e-e67769f5-a176-437c-8900-c37a347b51df_LIM52P.png', 'reviewed', '2025-01-02 08:56:27', '2025-01-02 08:56:54', 'general');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `TagID` int(11) NOT NULL,
  `TagName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`TagID`, `TagName`) VALUES
(111, 'Academic Success'),
(158, 'Advice'),
(156, 'Agriculture'),
(142, 'Anthropology'),
(132, 'Architecture'),
(141, 'Art History'),
(84, 'Assignments'),
(126, 'Biology'),
(119, 'Business Administration'),
(82, 'Campus Events'),
(114, 'Campus Safety'),
(102, 'Career Advice'),
(133, 'Chemistry'),
(131, 'Civil Engineering'),
(107, 'Class Notes'),
(80, 'College Life'),
(94, 'College Organizations'),
(98, 'College Parties'),
(116, 'College Sports'),
(149, 'Communication Studies'),
(117, 'Computer Science'),
(147, 'Design'),
(136, 'Economics'),
(124, 'Education'),
(130, 'Electrical Engineering'),
(128, 'Engineering'),
(146, 'Environmental Science'),
(83, 'Exams'),
(96, 'Finals Week'),
(121, 'Finance'),
(153, 'Fine Arts'),
(115, 'Fitness on Campus'),
(92, 'Freshman Year'),
(110, 'Graduation'),
(148, 'Graphic Design'),
(85, 'Group Projects'),
(145, 'Health Sciences'),
(139, 'History'),
(157, 'Humor'),
(87, 'Internships'),
(150, 'Journalism'),
(123, 'Law'),
(108, 'Lectures'),
(143, 'Linguistics'),
(120, 'Marketing'),
(135, 'Mathematics'),
(129, 'Mechanical Engineering'),
(127, 'Medicine'),
(99, 'Mental Health'),
(152, 'Music'),
(103, 'Networking'),
(125, 'Nursing'),
(89, 'Part-Time Jobs'),
(144, 'Pharmacy'),
(140, 'Philosophy'),
(134, 'Physics'),
(137, 'Political Science'),
(122, 'Psychology'),
(155, 'Public Health'),
(104, 'Public Speaking'),
(112, 'Research Projects'),
(91, 'Roommates'),
(88, 'Scholarships'),
(154, 'Social Work'),
(138, 'Sociology'),
(118, 'Software Engineering'),
(100, 'Stress Management'),
(105, 'Student Clubs'),
(86, 'Student Discounts'),
(95, 'Student Government'),
(113, 'Student Housing'),
(90, 'Student Loans'),
(93, 'Study Abroad'),
(97, 'Study Groups'),
(81, 'Study Tips'),
(151, 'Theater'),
(101, 'Time Management'),
(106, 'Tutoring'),
(109, 'Work-Life Balance');

-- --------------------------------------------------------

--
-- Table structure for table `userprofiles`
--

CREATE TABLE `userprofiles` (
  `UserID` int(11) NOT NULL,
  `Fullname` varchar(100) DEFAULT 'fullname not available',
  `Bio` text NOT NULL DEFAULT '\'bio not set\'',
  `ProfilePic` varchar(255) DEFAULT NULL,
  `JoinedAt` date NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userprofiles`
--

INSERT INTO `userprofiles` (`UserID`, `Fullname`, `Bio`, `ProfilePic`, `JoinedAt`, `updatedAt`) VALUES
(2, NULL, 'i have no enemies', 'uploads/6b029309-494d-4bf9-adb3-e3bd6c58e86a.jpg', '2024-05-15', '2024-12-06 12:30:59'),
(3, NULL, 'new member', 'uploads/sample_profile2.png', '2024-05-15', '2024-12-08 12:37:48'),
(5, NULL, 'I was born happy, I grew up sad.', 'uploads/sample_profile11.png', '2024-05-15', '0000-00-00 00:00:00'),
(6, NULL, 'playing dotes since 2010', 'uploads/sample_profile4.png', '2024-05-15', '0000-00-00 00:00:00'),
(7, NULL, 'yeah I love ONE PIECE!', 'uploads/sample_profile3.png', '2024-05-15', '0000-00-00 00:00:00'),
(8, NULL, '', 'uploads/sample_profile6.png', '2024-05-15', '0000-00-00 00:00:00'),
(9, NULL, 'beautiful and dangerous, same as a rose.', 'uploads/sample_profile9.png', '2024-05-15', '0000-00-00 00:00:00'),
(10, NULL, 'YOU are My Sunshine', 'uploads/sample_profile10.png', '2024-05-15', '0000-00-00 00:00:00'),
(11, NULL, 'hey', 'uploads/images (37).jpeg', '2024-05-15', '0000-00-00 00:00:00'),
(13, NULL, 'i\'m good', 'uploads/sample_profile8.png', '2024-05-15', '0000-00-00 00:00:00'),
(14, NULL, 'I see bad moon rising.', 'uploads/sample_profile13.png', '2024-05-15', '0000-00-00 00:00:00'),
(15, NULL, '', NULL, '2024-05-15', '0000-00-00 00:00:00'),
(16, NULL, 'Plates Plates PLATES!', 'uploads/sample_profile14.png', '2024-05-15', '0000-00-00 00:00:00'),
(17, NULL, '', 'uploads/sample_profile15.png', '2024-05-15', '0000-00-00 00:00:00'),
(18, NULL, '', 'uploads/attachment_109749965.png', '2024-05-16', '0000-00-00 00:00:00'),
(21, NULL, '', NULL, '2024-06-11', '0000-00-00 00:00:00'),
(22, NULL, '', 'uploads/profile-portrait-ordinary-caucasian-guy-with-bristle-looking-left-blank-white-cope-space-no-emotions-casual-expression-standing-queue-waiting-someone-people-promo-concept.jpg', '2024-06-11', '0000-00-00 00:00:00'),
(23, NULL, '', NULL, '2024-06-11', '0000-00-00 00:00:00'),
(24, NULL, '', 'uploads/wlop-ghost-blade-ghost-blade-women-wallpaper-preview.jpg', '2024-06-11', '0000-00-00 00:00:00'),
(26, 'fullname not available', '', NULL, '2024-08-23', '0000-00-00 00:00:00'),
(27, 'fullname not available', '', NULL, '2024-09-09', '0000-00-00 00:00:00'),
(28, 'fullname not available', '', NULL, '2024-09-09', '0000-00-00 00:00:00'),
(29, 'fullname not available', 'not today', 'uploads/images (37).jpeg', '2024-09-09', '0000-00-00 00:00:00'),
(30, 'fullname not available', '', 'uploads/peakpx.jpg', '2024-09-12', '0000-00-00 00:00:00'),
(31, 'fullname not available', '', 'uploads/2651908.png', '2024-09-12', '0000-00-00 00:00:00'),
(32, 'fullname not available', '', 'uploads/images (78).jpeg', '2024-09-12', '0000-00-00 00:00:00'),
(35, 'fullname not available', 'hdsaidhiasdasdasd', 'uploads/127089.jpg', '2024-09-12', '2024-11-23 12:33:21'),
(36, 'fullname not available', '', NULL, '2024-09-15', '0000-00-00 00:00:00'),
(37, 'fullname not available', 'nwdiansid', 'uploads/WIN_20240919_07_37_25_Pro.jpg', '2024-09-19', '0000-00-00 00:00:00'),
(38, 'fullname not available', '', 'uploads/oppenheimer.PNG', '2024-09-25', '0000-00-00 00:00:00'),
(42, 'fullname not available', '', NULL, '2024-11-19', '2024-11-19 10:57:57'),
(43, 'fullname not available', '', NULL, '2024-11-19', '2024-11-19 11:02:40'),
(45, 'fullname not available', '', NULL, '2024-12-06', '2024-12-06 08:04:14'),
(46, 'fullname not available', '', NULL, '2024-12-06', '2024-12-06 08:07:42'),
(47, 'fullname not available', 'HELLO', 'uploads/[removal.ai]_8dd3a076-6790-442e-8adb-619b289efb9e-e67769f5-a176-437c-8900-c37a347b51df_LIM52P.png', '2024-12-15', '2024-12-15 02:32:27'),
(48, 'fullname not available', '123', 'uploads/crazy-expression-draw-260nw-1351230482.png', '2025-01-02', '2025-01-02 08:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `JoinedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsAdmin` tinyint(1) DEFAULT 0,
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `LastUsernameChange` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `JoinedAt`, `IsAdmin`, `updatedAt`, `LastUsernameChange`) VALUES
(2, 'thorfin', 'bn201903321@wmsu.edu.ph', '$2y$10$N5P2OMT04NzWttcSwJnY6uZ57yUe1VUN8.mSsJY0vfTwPZvKXwlvC', '2024-09-08 16:00:00', 0, '2025-01-06 17:47:04', NULL),
(3, 'mary ann', 'as202201322@wmsu.edu.ph', '$2y$10$HRAnnyQqk2h/tn65MYXu4uYKlz04zw8SxDyZtQToTtNGBWUARnzMu', '2024-09-08 16:00:00', 0, '2024-12-08 12:43:24', '2024-12-08 12:43:24'),
(5, 'walter', 'as202201324@wmsu.edu.ph', '$2y$10$FwJ/prLzRTYtI1ylinGFauFmYZGJB0B4N4t7G2T7FHN6MLUv.Dq2u', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(6, 'naix', 'as202201323@wmsu.edu.ph', '$2y$10$qFkSQYwb7HljV5EPOIRXjuEx/9/GoSaCCN07q8eK9s1.rq7qREHmu', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(7, 'luffy', 'as202201382@wmsu.edu.ph', '$2y$10$8VN/u15Xl3yoyEs7qTYyF.LA8lXi.ahIxoUFf8wJK1cMX1vnpm2ky', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(8, 'pipin', 'bz201903321@wmsu.edu.ph', '$2y$10$8NK.MIqi4C0V2n6GXV8H5.z.sQBBufCPAbLHhqJiqLk.ANYIr5YoS', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(9, 'rosemary', 'as202207322@wmsu.edu.ph', '$2y$10$dq.gzexMtx8vF5HP6ww00esX.tj4XH8O4n/5hLYuIhtv1cnH9GNLK', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(10, 'james', 'ce202201322@wmsu.edu.ph', '$2y$10$f4gYD.T0TrB2fGlo9US4QuAPDBAC76K95dHkUDWS7VwqMCNszVoyK', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(11, 'alfaisal', 'eh202201122@wmsu.edu.ph', '$2y$10$75FJMXnw6vzO0/iXvSdGWemzOWdRLnusO0GMesoWHUP3SiWnp0//W', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(13, 'SMARTYPANTS', 'qr202201322@wmsu.edu.ph', '$2y$10$Ol9kxJ6H42SJzMDCIYo.4OjIJ2GTm9nFS2LvbVKqnBBqTDPhiGsIO', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(14, 'crimson sky', 'cs202105423@wmsu.edu.ph', '$argon2i$v=19$m=65536,t=4,p=1$UURZSS44RnF3NzNKR3dIeQ$t8CeheFzNkGt7j5a7LPeG4BcSL2RUAJlYPb3FWGkgSA', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(15, 'studentako', 'ps202201322@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$SDZwSVI1RWowb1hLc2lpTA$xObIlwO8IwhI9UV+vYZfBW/WEUCcsfEI50No0wCP0BU', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(16, 'archiGirl', 'ty202201328@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$d21iYjh0UjBCeGRyQVVpcw$DAb+tBljh7AxYItfYNpPOLl1YMbvADXFUqvzxU6dH7s', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(17, 'MICH', 'cv201903321@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$OUpnQVRUMmUwTkZ4aEZybw$56llL0cGQaLZYJo9wMS3i9GKd31CdpW5XUH8A9vCf3A', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(18, 'squshy', 'eh201901122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$eldWZUp6S1dEbW8uUXZLYw$cFWCv3eqfUiuR/pxwTPh4u4pFHjTwdXqHR+KpSkGlb8', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(19, 'fahad', 'eh202201822@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$bC5lZ0Q3MFJxWDg3YlVpUg$hWQDdUl1c5bysf40vMHTzLvYeMQRKBNEt5rj5vLo8Fw', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(21, 'fahads', 'eh201901132@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$Q1J2blQuYWxxSkFTL3M3cA$vd/RshnW2pWci+e86XrX2eGwinNWzQ+D05WWG+AXNUQ', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(22, 'amongus', 'eh201901298@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$Smk1OHNOd3hQLlFkZHdqSw$ML04YYW1ri/+Ei2uTO0uWl+Ebq8SgI6M5ATpnsdw8QA', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(23, 'newpeep', 'eh202201177@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$RmZKemRHSnE4RUNEancyZQ$ef1q+3JboASWlbiBrITT1z6Ua/OyRc7R8s8VcG3uFaM', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(24, 'michelle', 'eh202001122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$aWF1Y0hIazRqT1k3MGNiRg$oOHEdEIZpt7YLjzfJvuwcsi0V+enyzIOB4yjfTd/glU', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(25, 'ahaboy', 'eh202501234@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$MXVNVkFsejU4SDF3MlZLdQ$whMIYCmLnN14w8aPegsc5DOO++o1A+Pc3nLmfeBCt0U', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(26, 'may', 'op202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$cVBCSldJQzhNYVByQWxueA$dmZCGz+qTv4IYlZoLluF2jaL8vc+Kb3T55oBAfXQUDI', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(27, 'aldrikz', 'eh202201120@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$VjFYaHUwdFpWbEphZ1ViNA$QvAtvUiyOvD9fCGO47uiqqkXPb9nkWSoSxRQV+eDpBE', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(28, 'new user', 'ox202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$dXFHVFFHcHBULlJhdlVWTQ$W0tVYy7n6tCPQFpRvoQjWgIKk1k9VLO/U5F2MrZoFv4', '2024-09-08 16:00:00', 0, '2024-12-06 08:52:23', NULL),
(29, 'ronald_2222', 'oq202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$VG8yUkVGR1ZLS3MuWVN5Yw$OYA644z1CgXl7BPU/GJA7xwbZEosUuIjbBVoKRnPAhI', '2024-09-09 04:39:32', 0, '2024-12-06 08:52:23', NULL),
(30, 'wmsu_boy', 'ex202201122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$bHZmMVNrNEFjWGJXZmZIUg$LSSx4kvHLzIgeG/T7tl3OIdP1iiF5A3XFZTP5rjoSCk', '2024-09-12 12:07:52', 0, '2024-12-06 08:52:23', NULL),
(31, 'jimmy', 'om202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$OUNRU2ozcnhkZTl2OGJ6bg$1rOfAu284J6IHDCaICPF0o9T84eorjM1Yi5ADk0OlWU', '2024-09-12 12:17:14', 0, '2024-12-06 08:52:23', NULL),
(32, 'jeoffrey', 'ox202201623@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$QnJmRERLaGJER0xQZ085aA$Y7ONEA8EMw3fog8z2e47NPFb4OLY206u5VCcIQ3OR+M', '2024-09-12 12:33:37', 0, '2024-12-06 08:52:23', NULL),
(35, 'unknown', 'op202201623@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$TkZRdEIydENzakpKUHJDMQ$y2OIqUVhA5WvetEVCctM07LMsjUYOR+zTMkkhPXEbqo', '2024-09-12 12:51:19', 0, '2024-12-09 09:49:38', '2024-12-09 09:49:38'),
(36, 'batista', 'ob202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$dXljeGU5cXpQYmRRZ25MSQ$N0npeUazzA52Kz9EE3b8F6NdSLMnFGo1drRky4fk+Ok', '2024-09-15 13:37:13', 0, '2024-12-06 08:52:23', NULL),
(37, 'drikzz', 'eh202201066@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$QUU5QVBueFJYSUpzaXNtVA$1aRRKhPIU5v5sWcajchnG3kSbhoyHJ8Mb6knEzCZGKM', '2024-09-19 02:28:41', 0, '2024-12-06 08:52:23', NULL),
(38, 'jude', 'od202201623@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$eVF2Lk9xV2UzRHdQa3FiNA$WVMsIGiKUq/tus5kCMV9e6ZCJ1Urth044Hnz2zwSLeg', '2024-09-25 02:48:00', 0, '2024-12-06 08:52:23', NULL),
(41, 'boy', 'eh202201162@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$QW9TM0x5RWtpMHUuMHQvZA$y3Z95Yztpv6kcd2T20OVI5Fr5DrF2nScnD/bqbjJ9jc', '2024-11-19 10:14:35', 0, '2024-12-06 08:52:23', NULL),
(42, 'change', 'op202201128@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$ZVlwRmlmYXNHWXVhdTQydg$e7JlJQYq1ezWIjJCuTyrotScRpFTpCDp6Qtdr7G7jQU', '2024-11-19 10:42:24', 0, '2024-12-06 08:52:23', NULL),
(43, 'malakas', 'lp202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$RGc3UDNQSWZPRVpodkFheA$09rIVnCF6g146Kpx65oMPj+U+OEtW7WgVNXFszf542c', '2024-11-19 11:00:46', 0, '2024-12-06 08:52:23', NULL),
(45, 'wshare_admin', 'admin@wshare.com', '$2y$10$r2OeCyaTuTxtGZUi7k6uweHg4GloDhz/WLrVd0aFIkE.Y//4DqjTW', '2024-12-06 08:03:33', 1, '2024-12-06 08:52:23', NULL),
(46, 'zabuza', 'eh202701122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$bEdZblFOc0w2QlZ2cHo4WA$ixBsBNULB1THyujvxG1SyOCq4VAqyMpVUMDYZeBTKnU', '2024-12-06 08:07:18', 0, '2024-12-06 08:52:23', NULL),
(47, 'john', 'eh202201192@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$N0daMi5QbnI2eDd4N2NIWA$mxarB3jmqytYhDfYKenh/Dj6I3r1wD8NzXpPBP09ZMY', '2024-12-15 02:29:57', 0, '2024-12-15 02:29:57', NULL),
(48, 'opop', 'eh202501122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$TVVSTXI0QkNMZDJUZTNqTg$m0rlNI5Sxcll6/YRpBGFLHuMQXge2AiotWqtWls5804', '2025-01-02 08:48:15', 0, '2025-01-02 08:49:22', '2025-01-02 08:49:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `LogID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ActivityType` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `IPAddress` varchar(45) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bans`
--

CREATE TABLE `user_bans` (
  `BanID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `BannedBy` int(11) NOT NULL,
  `BanReason` text NOT NULL,
  `BanStart` timestamp NOT NULL DEFAULT current_timestamp(),
  `BanEnd` timestamp NULL DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CommunityID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_bans`
--

INSERT INTO `user_bans` (`BanID`, `UserID`, `BannedBy`, `BanReason`, `BanStart`, `BanEnd`, `IsActive`, `CommunityID`) VALUES
(1, 2, 45, 'dsadsad', '2024-12-06 08:21:40', '2024-12-09 01:21:40', 0, NULL),
(2, 2, 45, 'Inappropriate behaviour towards peers', '2024-12-06 09:46:54', '0000-00-00 00:00:00', 0, NULL),
(3, 2, 45, 'Inappropriate behaviour towards peers', '2024-12-06 09:47:06', '0000-00-00 00:00:00', 0, NULL),
(4, 3, 45, 'rwer', '2024-12-06 09:48:53', '2024-12-07 02:48:53', 1, NULL),
(5, 2, 45, 'rewr', '2024-12-06 09:49:01', '2024-12-07 02:49:01', 0, NULL),
(6, 3, 45, 'tripping', '2024-12-07 11:42:47', '2024-12-08 04:42:47', 1, NULL),
(7, 2, 45, 'Violation of community guidelines', '2024-12-09 08:48:26', '2025-01-08 08:48:26', 0, NULL),
(45, 35, 2, 'd', '2024-12-10 03:07:50', '2024-12-10 20:07:50', 0, 25),
(46, 35, 2, 'd', '2024-12-10 03:07:50', '2024-12-10 20:07:50', 0, 25),
(47, 35, 45, 'superadmin ban', '2024-12-09 20:42:08', '2024-12-10 20:42:08', 0, NULL),
(48, 35, 2, 'vbiolation', '2024-12-10 07:09:05', '2024-12-11 00:09:05', 0, 25),
(49, 35, 45, 'over violation', '2024-12-10 00:13:22', '2024-12-11 00:13:22', 0, NULL),
(50, 35, 2, 'Violation of community rules', '2024-12-15 07:26:57', NULL, 0, 25),
(51, 35, 2, 'epal', '2024-12-15 07:48:31', '2025-01-14 00:48:31', 0, 25),
(52, 5, 45, 'no reason', '2024-12-15 04:28:44', '2024-12-16 04:28:44', 1, NULL),
(53, 35, 45, 'test', '2024-12-15 20:39:14', '2024-12-16 20:39:14', 0, NULL),
(54, 35, 2, 'test', '2024-12-16 15:09:50', '2024-12-17 08:09:50', 1, 25),
(55, 48, 45, 'testing', '2025-01-02 02:01:42', '0000-00-00 00:00:00', 0, NULL),
(56, 48, 45, 'testing', '2025-01-02 02:02:27', '2025-01-05 02:02:27', 0, NULL),
(57, 2, 45, 'test', '2025-01-06 22:22:33', '2025-01-07 22:22:33', 0, NULL),
(58, 48, 45, 'test', '2025-01-07 00:30:46', '2025-01-10 00:30:46', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `SessionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `LoginTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `LogoutTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`SessionID`, `UserID`, `LoginTime`, `LogoutTime`) VALUES
(3, 2, '2024-09-14 16:07:18', '2024-09-14 16:07:31'),
(7, 2, '2024-09-14 16:26:36', '2024-09-14 16:26:38'),
(8, 35, '2024-09-14 16:26:50', '2024-09-14 16:27:01'),
(9, 2, '2024-09-15 01:22:40', '2024-09-15 01:26:40'),
(10, 2, '2024-09-15 01:28:58', '2024-09-15 11:10:06'),
(12, 2, '2024-09-15 13:13:22', '2024-09-15 13:14:19'),
(13, 2, '2024-09-15 13:36:40', '2024-09-15 13:36:43'),
(14, 36, '2024-09-15 13:37:19', '2024-09-15 13:37:35'),
(15, 2, '2024-09-15 13:51:44', '2024-09-15 13:57:12'),
(17, 2, '2024-09-15 14:02:40', '2024-09-15 14:28:36'),
(19, 29, '2024-09-16 01:44:03', '2024-09-16 01:44:18'),
(20, 3, '2024-09-16 01:44:42', NULL),
(21, 2, '2024-09-16 15:36:22', '2024-09-16 15:55:12'),
(22, 3, '2024-09-16 15:55:18', '2024-09-16 15:56:25'),
(23, 2, '2024-09-16 15:58:08', '2024-09-17 16:28:54'),
(24, 29, '2024-09-17 16:31:58', '2024-09-17 16:39:27'),
(25, 35, '2024-09-17 16:42:25', '2024-09-17 16:45:10'),
(26, 2, '2024-09-19 02:19:47', '2024-09-19 02:20:06'),
(27, 2, '2024-09-19 02:21:11', '2024-09-19 02:42:07'),
(28, 37, '2024-09-19 02:28:50', '2024-09-19 02:35:11'),
(29, 8, '2024-09-19 02:38:01', NULL),
(30, 3, '2024-09-19 02:42:29', '2024-09-19 02:42:31'),
(31, 8, '2024-09-19 02:42:59', '2024-09-19 02:43:02'),
(32, 2, '2024-09-20 02:45:15', NULL),
(33, 2, '2024-09-22 04:07:10', '2024-09-22 05:49:17'),
(34, 32, '2024-09-22 05:49:23', '2024-09-22 05:55:27'),
(35, 2, '2024-09-22 05:55:31', '2024-09-22 06:00:08'),
(36, 2, '2024-09-22 06:00:19', '2024-09-22 06:46:06'),
(37, 35, '2024-09-22 06:46:11', '2024-09-22 06:47:00'),
(38, 3, '2024-09-22 06:47:04', '2024-09-22 06:47:24'),
(39, 2, '2024-09-22 06:47:34', '2024-09-22 06:48:05'),
(40, 8, '2024-09-22 06:48:13', '2024-09-22 07:49:37'),
(41, 2, '2024-09-22 07:49:42', NULL),
(42, 2, '2024-09-23 02:55:03', NULL),
(43, 2, '2024-09-23 03:04:27', '2024-09-23 03:21:16'),
(44, 2, '2024-09-23 03:24:27', '2024-09-23 03:25:14'),
(45, 35, '2024-09-23 03:25:46', '2024-09-23 03:26:01'),
(46, 3, '2024-09-25 01:53:07', '2024-09-25 01:54:37'),
(47, 2, '2024-09-25 01:54:42', NULL),
(48, 35, '2024-09-25 02:31:12', '2024-09-25 02:31:55'),
(49, 2, '2024-09-25 02:32:00', NULL),
(50, 2, '2024-09-25 02:40:28', '2024-09-25 02:44:13'),
(51, 3, '2024-09-25 02:44:22', '2024-09-25 02:47:11'),
(52, 38, '2024-09-25 02:48:08', NULL),
(53, 2, '2024-09-26 02:26:38', '2024-09-26 02:28:20'),
(54, 3, '2024-09-26 02:28:28', '2024-09-26 02:29:32'),
(55, 2, '2024-09-26 02:29:37', '2024-09-26 02:30:32'),
(56, 2, '2024-09-26 02:44:31', NULL),
(57, 2, '2024-10-02 08:06:01', NULL),
(58, 2, '2024-10-02 10:29:03', NULL),
(59, 2, '2024-10-04 15:52:22', NULL),
(60, 3, '2024-10-08 00:42:28', NULL),
(61, 2, '2024-10-10 12:48:15', NULL),
(62, 2, '2024-10-13 07:33:13', '2024-10-13 10:32:11'),
(63, 3, '2024-10-13 10:32:16', '2024-10-13 10:44:54'),
(64, 3, '2024-10-13 10:46:28', '2024-10-13 11:22:44'),
(65, 35, '2024-10-13 11:23:02', '2024-10-13 11:23:40'),
(66, 3, '2024-10-13 11:23:45', '2024-10-13 11:24:06'),
(67, 35, '2024-10-13 11:24:11', '2024-10-13 11:24:17'),
(68, 3, '2024-10-13 11:24:22', '2024-10-14 02:52:58'),
(69, 2, '2024-10-14 02:53:04', '2024-10-14 02:59:25'),
(70, 3, '2024-10-14 02:59:31', '2024-10-14 02:59:49'),
(71, 2, '2024-10-14 03:00:44', NULL),
(73, 2, '2024-10-14 03:34:07', '2024-10-14 03:35:34'),
(75, 2, '2024-10-14 03:38:53', NULL),
(76, 2, '2024-11-19 02:42:04', '2024-11-19 03:18:56'),
(77, 2, '2024-11-19 03:33:57', '2024-11-19 04:05:34'),
(78, 2, '2024-11-19 04:05:42', '2024-11-19 04:46:22'),
(79, 2, '2024-11-19 04:52:42', '2024-11-19 09:02:20'),
(80, 2, '2024-11-19 10:23:44', '2024-11-19 10:24:14'),
(81, 42, '2024-11-19 10:42:40', '2024-11-19 10:57:43'),
(82, 42, '2024-11-19 10:57:56', '2024-11-19 10:59:29'),
(83, 43, '2024-11-19 11:01:00', '2024-11-19 12:25:59'),
(84, 2, '2024-11-19 12:26:06', '2024-11-19 12:33:55'),
(85, 2, '2024-11-19 12:34:49', '2024-11-19 13:46:16'),
(86, 2, '2024-11-20 02:46:00', '2024-11-20 05:44:12'),
(87, 2, '2024-11-20 05:47:48', '2024-11-20 08:17:28'),
(88, 2, '2024-11-20 08:29:01', '2024-11-20 16:31:00'),
(89, 2, '2024-11-20 16:34:22', '2024-11-20 16:40:56'),
(90, 2, '2024-11-21 02:51:06', '2024-11-21 02:55:42'),
(91, 29, '2024-11-21 02:57:56', '2024-11-21 03:00:06'),
(92, 2, '2024-11-21 03:01:09', '2024-11-21 03:22:26'),
(93, 2, '2024-11-21 12:22:03', '2024-11-21 12:22:07'),
(94, 2, '2024-11-21 12:22:20', NULL),
(95, 2, '2024-11-21 13:05:34', NULL),
(96, 2, '2024-11-21 13:07:01', NULL),
(97, 2, '2024-11-21 13:07:36', '2024-11-21 14:34:56'),
(98, 3, '2024-11-21 14:35:02', NULL),
(99, 2, '2024-11-22 02:58:06', '2024-11-22 04:09:08'),
(100, 2, '2024-11-22 04:09:16', '2024-11-22 06:06:37'),
(101, 2, '2024-11-22 06:06:53', '2024-11-22 06:06:54'),
(102, 3, '2024-11-22 06:06:59', '2024-11-22 07:14:22'),
(103, 2, '2024-11-22 07:14:27', '2024-11-22 07:16:25'),
(104, 3, '2024-11-22 07:16:30', '2024-11-22 07:20:37'),
(105, 2, '2024-11-22 07:20:43', '2024-11-22 07:21:10'),
(106, 2, '2024-11-22 07:21:16', '2024-11-22 07:21:19'),
(107, 3, '2024-11-22 07:21:24', '2024-11-22 07:54:04'),
(108, 35, '2024-11-22 07:54:18', '2024-11-22 07:59:18'),
(109, 2, '2024-11-22 07:59:28', '2024-11-22 08:02:39'),
(110, 3, '2024-11-22 08:02:44', '2024-11-22 08:03:05'),
(111, 8, '2024-11-22 08:03:15', '2024-11-22 08:20:11'),
(112, 2, '2024-11-22 08:20:16', '2024-11-22 08:21:28'),
(113, 8, '2024-11-22 08:21:37', '2024-11-22 08:23:36'),
(114, 35, '2024-11-22 08:24:07', '2024-11-22 08:24:45'),
(115, 2, '2024-11-22 08:24:50', '2024-11-22 08:25:31'),
(116, 35, '2024-11-22 08:25:37', '2024-11-22 08:25:44'),
(117, 3, '2024-11-22 08:25:51', '2024-11-22 08:26:24'),
(118, 2, '2024-11-22 08:26:30', '2024-11-22 08:26:52'),
(119, 3, '2024-11-22 08:26:57', '2024-11-22 08:43:55'),
(120, 2, '2024-11-22 08:44:01', '2024-11-22 08:44:13'),
(121, 3, '2024-11-22 08:44:22', '2024-11-22 08:45:10'),
(122, 2, '2024-11-22 08:45:15', '2024-11-22 08:45:37'),
(123, 3, '2024-11-22 08:45:42', '2024-11-22 09:06:55'),
(124, 2, '2024-11-22 09:07:00', '2024-11-22 09:07:42'),
(125, 3, '2024-11-22 09:07:47', '2024-11-22 09:12:48'),
(126, 2, '2024-11-22 09:12:54', '2024-11-22 09:13:09'),
(127, 3, '2024-11-22 09:13:14', '2024-11-22 09:16:22'),
(128, 2, '2024-11-22 09:16:28', '2024-11-22 09:16:44'),
(129, 3, '2024-11-22 09:16:50', '2024-11-22 09:17:04'),
(130, 3, '2024-11-22 09:17:20', '2024-11-22 09:17:29'),
(131, 2, '2024-11-22 09:17:34', '2024-11-22 09:18:00'),
(132, 3, '2024-11-22 09:18:05', '2024-11-22 09:18:33'),
(133, 2, '2024-11-22 09:18:39', '2024-11-22 09:18:51'),
(134, 3, '2024-11-22 09:18:56', '2024-11-22 09:19:12'),
(135, 2, '2024-11-22 09:19:19', '2024-11-22 09:19:28'),
(136, 3, '2024-11-22 09:19:34', '2024-11-22 09:50:03'),
(137, 2, '2024-11-22 09:50:10', '2024-11-22 13:06:21'),
(138, 2, '2024-11-22 13:06:29', '2024-11-23 02:45:14'),
(139, 35, '2024-11-23 02:45:20', '2024-11-23 02:57:46'),
(140, 2, '2024-11-23 02:57:51', '2024-11-23 02:58:13'),
(141, 35, '2024-11-23 02:58:19', '2024-11-23 02:58:32'),
(142, 2, '2024-11-23 02:58:37', '2024-11-23 02:58:58'),
(143, 35, '2024-11-23 02:59:05', '2024-11-23 02:59:23'),
(144, 2, '2024-11-23 02:59:28', '2024-11-23 02:59:53'),
(145, 35, '2024-11-23 03:00:00', '2024-11-23 07:59:58'),
(146, 2, '2024-11-23 08:00:04', '2024-11-23 08:14:27'),
(147, 35, '2024-11-23 08:14:33', '2024-11-23 11:13:47'),
(148, 2, '2024-11-23 11:13:53', '2024-11-23 12:15:31'),
(149, 35, '2024-11-23 12:15:37', '2024-11-23 12:33:45'),
(150, 35, '2024-11-23 12:33:52', NULL),
(151, 2, '2024-12-05 17:54:13', '2024-12-05 19:00:16'),
(152, 2, '2024-12-06 02:52:04', '2024-12-06 06:41:53'),
(153, 3, '2024-12-06 06:41:59', '2024-12-06 07:46:26'),
(154, 45, '2024-12-06 08:03:54', '2024-12-06 08:06:35'),
(155, 46, '2024-12-06 08:07:37', '2024-12-06 08:15:03'),
(156, 2, '2024-12-06 08:17:30', '2024-12-06 08:17:33'),
(157, 2, '2024-12-06 08:38:12', '2024-12-06 08:56:53'),
(158, 3, '2024-12-06 08:56:59', '2024-12-06 09:30:41'),
(159, 2, '2024-12-06 09:34:24', '2024-12-06 09:47:16'),
(160, 2, '2024-12-06 09:47:22', '2024-12-06 09:48:20'),
(161, 2, '2024-12-06 09:51:00', '2024-12-06 10:04:45'),
(162, 3, '2024-12-06 10:04:50', '2024-12-06 10:08:33'),
(163, 2, '2024-12-06 10:08:39', '2024-12-06 10:29:33'),
(164, 2, '2024-12-06 10:47:30', '2024-12-06 13:34:29'),
(165, 35, '2024-12-06 13:34:35', '2024-12-06 13:45:56'),
(166, 2, '2024-12-06 13:46:01', '2024-12-06 13:48:05'),
(167, 35, '2024-12-06 13:48:11', '2024-12-06 13:54:54'),
(168, 2, '2024-12-06 13:54:59', '2024-12-06 15:04:17'),
(169, 8, '2024-12-06 15:04:35', '2024-12-06 16:08:54'),
(170, 2, '2024-12-06 16:08:59', '2024-12-06 16:14:42'),
(171, 2, '2024-12-06 16:14:53', '2024-12-07 01:33:27'),
(172, 3, '2024-12-07 01:35:23', '2024-12-07 01:36:20'),
(173, 2, '2024-12-07 01:36:27', NULL),
(174, 2, '2024-12-07 08:19:19', '2024-12-07 11:35:37'),
(175, 3, '2024-12-07 11:35:43', '2024-12-07 12:23:20'),
(176, 2, '2024-12-07 12:23:26', '2024-12-07 12:41:52'),
(177, 3, '2024-12-07 12:41:58', '2024-12-07 12:42:38'),
(178, 2, '2024-12-07 12:42:43', '2024-12-07 12:57:28'),
(179, 8, '2024-12-07 12:57:36', '2024-12-07 13:20:55'),
(180, 2, '2024-12-07 13:21:02', '2024-12-08 03:59:14'),
(181, 3, '2024-12-08 03:59:19', '2024-12-08 04:00:11'),
(182, 2, '2024-12-08 04:00:16', '2024-12-08 04:01:13'),
(183, 3, '2024-12-08 04:01:18', '2024-12-08 04:16:27'),
(184, 2, '2024-12-08 04:16:33', '2024-12-08 04:17:10'),
(185, 3, '2024-12-08 04:17:15', '2024-12-08 05:28:21'),
(186, 35, '2024-12-08 05:28:27', '2024-12-08 05:59:16'),
(187, 2, '2024-12-08 05:59:21', '2024-12-08 06:08:53'),
(188, 2, '2024-12-08 06:08:59', '2024-12-08 06:09:02'),
(189, 3, '2024-12-08 06:09:07', NULL),
(190, 2, '2024-12-09 03:08:06', NULL),
(191, 2, '2024-12-09 04:16:51', '2024-12-09 09:37:02'),
(192, 3, '2024-12-09 09:37:21', '2024-12-09 09:37:35'),
(193, 35, '2024-12-09 09:37:41', '2024-12-09 15:11:42'),
(194, 2, '2024-12-09 15:11:47', '2024-12-10 02:03:44'),
(195, 35, '2024-12-10 02:03:58', '2024-12-10 02:08:13'),
(196, 3, '2024-12-10 02:08:28', '2024-12-10 02:09:18'),
(197, 2, '2024-12-10 02:09:23', '2024-12-10 02:10:08'),
(198, 3, '2024-12-10 02:10:18', '2024-12-10 02:11:11'),
(199, 2, '2024-12-10 02:11:17', '2024-12-10 02:58:23'),
(200, 35, '2024-12-10 02:58:30', '2024-12-10 03:07:06'),
(201, 2, '2024-12-10 03:07:12', '2024-12-10 03:08:10'),
(202, 35, '2024-12-10 03:08:23', '2024-12-10 07:04:33'),
(203, 2, '2024-12-10 07:06:53', '2024-12-10 07:09:12'),
(204, 35, '2024-12-10 07:09:22', '2024-12-10 07:13:49'),
(205, 2, '2024-12-10 07:13:55', '2024-12-10 07:15:03'),
(206, 35, '2024-12-10 07:15:18', '2024-12-10 07:19:24'),
(207, 2, '2024-12-10 07:19:30', NULL),
(208, 47, '2024-12-15 02:30:38', '2024-12-15 04:25:29'),
(209, 2, '2024-12-15 04:25:34', '2024-12-15 07:27:08'),
(210, 35, '2024-12-15 07:27:17', '2024-12-15 07:28:10'),
(211, 2, '2024-12-15 07:28:16', '2024-12-15 07:48:36'),
(212, 35, '2024-12-15 07:48:45', '2024-12-15 07:49:00'),
(213, 2, '2024-12-15 07:49:11', '2024-12-15 07:49:23'),
(214, 35, '2024-12-15 07:49:30', '2024-12-15 07:49:44'),
(215, 2, '2024-12-15 07:49:49', '2024-12-15 08:23:22'),
(216, 3, '2024-12-15 08:23:36', '2024-12-15 10:32:05'),
(217, 2, '2024-12-15 10:32:11', NULL),
(218, 2, '2024-12-16 10:33:10', '2024-12-16 15:10:14'),
(219, 35, '2024-12-16 15:10:22', '2024-12-16 15:12:09'),
(220, 2, '2024-12-16 15:12:14', '2024-12-16 15:13:22'),
(221, 2, '2024-12-16 15:14:19', '2024-12-16 15:23:38'),
(222, 8, '2024-12-16 15:23:46', NULL),
(223, 2, '2024-12-18 11:49:02', NULL),
(224, 2, '2024-12-18 12:03:19', NULL),
(225, 2, '2024-12-19 12:14:44', '2024-12-24 09:55:28'),
(226, 2, '2024-12-24 09:57:25', NULL),
(227, 2, '2025-01-02 07:38:31', '2025-01-02 08:46:46'),
(228, 48, '2025-01-02 08:48:25', NULL),
(229, 2, '2025-01-05 14:35:12', '2025-01-06 11:23:40'),
(230, 3, '2025-01-05 14:37:10', NULL),
(231, 2, '2025-01-06 11:33:35', '2025-01-06 11:49:41'),
(232, 2, '2025-01-06 15:36:13', '2025-01-06 15:37:02'),
(233, 2, '2025-01-06 15:37:38', '2025-01-06 16:04:35'),
(234, 2, '2025-01-06 16:05:02', '2025-01-07 08:08:33'),
(235, 2, '2025-01-07 08:18:15', '2025-01-07 08:42:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `SettingID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `SettingName` varchar(50) NOT NULL,
  `SettingValue` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`SettingID`),
  ADD KEY `UpdatedBy` (`UpdatedBy`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`BookmarkID`),
  ADD UNIQUE KEY `unique_user_post_bookmark` (`UserID`,`PostID`),
  ADD KEY `idx_user_bookmarks` (`UserID`),
  ADD KEY `idx_post_bookmarks` (`PostID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `comment_attachments`
--
ALTER TABLE `comment_attachments`
  ADD PRIMARY KEY (`AttachmentID`),
  ADD KEY `CommentID` (`CommentID`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`CommunityID`),
  ADD KEY `CreatorID` (`CreatorID`);

--
-- Indexes for table `community_bookmarks`
--
ALTER TABLE `community_bookmarks`
  ADD PRIMARY KEY (`BookmarkID`),
  ADD UNIQUE KEY `unique_user_communitypost_bookmark` (`UserID`,`PostID`),
  ADD KEY `idx_user_community_bookmarks` (`UserID`),
  ADD KEY `idx_communitypost_bookmarks` (`PostID`);

--
-- Indexes for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `community_join_requests`
--
ALTER TABLE `community_join_requests`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `CommunityID` (`CommunityID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `community_likes`
--
ALTER TABLE `community_likes`
  ADD PRIMARY KEY (`LikeID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `community_members`
--
ALTER TABLE `community_members`
  ADD PRIMARY KEY (`CommunityID`,`UserID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `community_messages`
--
ALTER TABLE `community_messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `idx_community_messages_community` (`CommunityID`),
  ADD KEY `idx_community_messages_parent` (`ParentMessageID`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `CommunityID` (`CommunityID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `community_post_tags`
--
ALTER TABLE `community_post_tags`
  ADD PRIMARY KEY (`PostID`,`TagID`),
  ADD KEY `TagID` (`TagID`);

--
-- Indexes for table `community_replies`
--
ALTER TABLE `community_replies`
  ADD PRIMARY KEY (`ReplyID`),
  ADD KEY `CommentID` (`CommentID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`FollowID`),
  ADD KEY `FollowerID` (`FollowerID`),
  ADD KEY `FollowingID` (`FollowingID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`LikeID`),
  ADD UNIQUE KEY `PostID` (`PostID`,`UserID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`AttachmentID`),
  ADD KEY `idx_message_attachments_message` (`MessageID`);

--
-- Indexes for table `message_media`
--
ALTER TABLE `message_media`
  ADD PRIMARY KEY (`MediaID`),
  ADD KEY `MessageID` (`MessageID`);

--
-- Indexes for table `message_reactions`
--
ALTER TABLE `message_reactions`
  ADD PRIMARY KEY (`ReactionID`),
  ADD UNIQUE KEY `unique_user_message_reaction` (`MessageID`,`UserID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `message_read_status`
--
ALTER TABLE `message_read_status`
  ADD PRIMARY KEY (`StatusID`),
  ADD UNIQUE KEY `unique_message_user` (`MessageID`,`UserID`),
  ADD KEY `idx_message_read_status_user` (`UserID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `RecipientID` (`RecipientID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `post_documents`
--
ALTER TABLE `post_documents`
  ADD PRIMARY KEY (`DocumentID`),
  ADD KEY `PostID` (`PostID`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`ImageID`),
  ADD KEY `post_images_post_idx` (`PostID`);

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`PostID`,`TagID`),
  ADD KEY `TagID` (`TagID`);

--
-- Indexes for table `post_videos`
--
ALTER TABLE `post_videos`
  ADD PRIMARY KEY (`VideoID`),
  ADD KEY `PostID` (`PostID`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`ReplyID`),
  ADD KEY `CommentID` (`CommentID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  ADD PRIMARY KEY (`AttachmentID`),
  ADD KEY `ReplyID` (`ReplyID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ReportedUserID` (`ReportedUserID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`TagID`),
  ADD UNIQUE KEY `TagName` (`TagName`);

--
-- Indexes for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `user_bans`
--
ALTER TABLE `user_bans`
  ADD PRIMARY KEY (`BanID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BannedBy` (`BannedBy`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`SessionID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`SettingID`),
  ADD UNIQUE KEY `unique_user_setting` (`UserID`,`SettingName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `SettingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `BookmarkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `comment_attachments`
--
ALTER TABLE `comment_attachments`
  MODIFY `AttachmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `CommunityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `community_bookmarks`
--
ALTER TABLE `community_bookmarks`
  MODIFY `BookmarkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `community_join_requests`
--
ALTER TABLE `community_join_requests`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `community_likes`
--
ALTER TABLE `community_likes`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `community_messages`
--
ALTER TABLE `community_messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `community_replies`
--
ALTER TABLE `community_replies`
  MODIFY `ReplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `FollowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=331;

--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `AttachmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `message_media`
--
ALTER TABLE `message_media`
  MODIFY `MediaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_reactions`
--
ALTER TABLE `message_reactions`
  MODIFY `ReactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `message_read_status`
--
ALTER TABLE `message_read_status`
  MODIFY `StatusID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT for table `post_documents`
--
ALTER TABLE `post_documents`
  MODIFY `DocumentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `ImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `post_videos`
--
ALTER TABLE `post_videos`
  MODIFY `VideoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `ReplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  MODIFY `AttachmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `TagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `userprofiles`
--
ALTER TABLE `userprofiles`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_bans`
--
ALTER TABLE `user_bans`
  MODIFY `BanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `SessionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `SettingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD CONSTRAINT `admin_settings_ibfk_1` FOREIGN KEY (`UpdatedBy`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `fk_bookmark_post` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bookmark_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `comment_attachments`
--
ALTER TABLE `comment_attachments`
  ADD CONSTRAINT `comment_attachments_ibfk_1` FOREIGN KEY (`CommentID`) REFERENCES `comments` (`CommentID`) ON DELETE CASCADE;

--
-- Constraints for table `communities`
--
ALTER TABLE `communities`
  ADD CONSTRAINT `communities_ibfk_1` FOREIGN KEY (`CreatorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_bookmarks`
--
ALTER TABLE `community_bookmarks`
  ADD CONSTRAINT `fk_community_bookmark_post` FOREIGN KEY (`PostID`) REFERENCES `community_posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_community_bookmark_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD CONSTRAINT `community_comments_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `community_posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_comments_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_join_requests`
--
ALTER TABLE `community_join_requests`
  ADD CONSTRAINT `community_join_requests_ibfk_1` FOREIGN KEY (`CommunityID`) REFERENCES `communities` (`CommunityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_join_requests_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_likes`
--
ALTER TABLE `community_likes`
  ADD CONSTRAINT `community_likes_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `community_posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_likes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_members`
--
ALTER TABLE `community_members`
  ADD CONSTRAINT `community_members_ibfk_1` FOREIGN KEY (`CommunityID`) REFERENCES `communities` (`CommunityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_members_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_messages`
--
ALTER TABLE `community_messages`
  ADD CONSTRAINT `community_messages_ibfk_1` FOREIGN KEY (`CommunityID`) REFERENCES `communities` (`CommunityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_messages_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_parent_message` FOREIGN KEY (`ParentMessageID`) REFERENCES `community_messages` (`MessageID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`CommunityID`) REFERENCES `communities` (`CommunityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_posts_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `community_post_tags`
--
ALTER TABLE `community_post_tags`
  ADD CONSTRAINT `community_post_tags_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `community_posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_post_tags_ibfk_2` FOREIGN KEY (`TagID`) REFERENCES `tags` (`TagID`) ON DELETE CASCADE;

--
-- Constraints for table `community_replies`
--
ALTER TABLE `community_replies`
  ADD CONSTRAINT `community_replies_ibfk_1` FOREIGN KEY (`CommentID`) REFERENCES `community_comments` (`CommentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_replies_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`FollowerID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`FollowingID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD CONSTRAINT `message_attachments_ibfk_1` FOREIGN KEY (`MessageID`) REFERENCES `community_messages` (`MessageID`) ON DELETE CASCADE;

--
-- Constraints for table `message_media`
--
ALTER TABLE `message_media`
  ADD CONSTRAINT `message_media_ibfk_1` FOREIGN KEY (`MessageID`) REFERENCES `community_messages` (`MessageID`) ON DELETE CASCADE;

--
-- Constraints for table `message_reactions`
--
ALTER TABLE `message_reactions`
  ADD CONSTRAINT `message_reactions_ibfk_1` FOREIGN KEY (`MessageID`) REFERENCES `community_messages` (`MessageID`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_reactions_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `message_read_status`
--
ALTER TABLE `message_read_status`
  ADD CONSTRAINT `message_read_status_ibfk_1` FOREIGN KEY (`MessageID`) REFERENCES `community_messages` (`MessageID`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_read_status_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`RecipientID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `post_documents`
--
ALTER TABLE `post_documents`
  ADD CONSTRAINT `post_documents_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE;

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `fk_post_images_post` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE;

--
-- Constraints for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`TagID`) REFERENCES `tags` (`TagID`) ON DELETE CASCADE;

--
-- Constraints for table `post_videos`
--
ALTER TABLE `post_videos`
  ADD CONSTRAINT `post_videos_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`CommentID`) REFERENCES `comments` (`CommentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  ADD CONSTRAINT `reply_attachments_ibfk_1` FOREIGN KEY (`ReplyID`) REFERENCES `replies` (`ReplyID`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`ReportedUserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD CONSTRAINT `userprofiles_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `user_activity_logs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `user_bans`
--
ALTER TABLE `user_bans`
  ADD CONSTRAINT `user_bans_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_bans_ibfk_2` FOREIGN KEY (`BannedBy`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

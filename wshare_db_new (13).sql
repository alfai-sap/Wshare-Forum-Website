-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 04:42 AM
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
(67, 22, 8, 'dsa', '2024-05-14 00:24:34', '2024-09-13 02:36:49'),
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
(88, 78, 3, 'sadsadsad', '2024-06-08 15:23:19', '2024-09-13 02:36:49'),
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
(124, 97, 2, 'fdsfsdff', '2024-11-22 13:08:19', '2024-11-22 13:08:19');

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
  `Visibility` enum('public','private') NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`CommunityID`, `CreatorID`, `Title`, `Description`, `Thumbnail`, `CreatedAt`, `Visibility`) VALUES
(1, 2, 'JJK peeps', 'dismantle boy, dismantle!', 'uploads/images (78).jpeg', '2024-09-16 15:37:55', 'public'),
(2, 2, 'Angkol', 'angkol ka? dito na kana', 'uploads/crazy-expression-draw-260nw-1351230482.png', '2024-09-16 15:38:49', 'public'),
(3, 2, 'PHPmyLoveMen', 'mamaw lang sa php pwede dito', 'uploads/Screenshot 2024-09-10 233418.png', '2024-09-16 15:40:01', 'public'),
(4, 2, 'Mamaw sa CMD', 'any cmd related discussions', 'uploads/Screenshot 2024-09-14 110701.png', '2024-09-16 15:41:32', 'public'),
(5, 2, 'Elden Lords', 'Ye who ascendth the throne, come forth.', 'uploads/Screenshot 2024-08-26 132636.png', '2024-09-16 15:43:19', 'public'),
(6, 2, 'WMSU Business Peeps', 'may idea ka business? share mo diskarte mo!', 'uploads/josh.jpg', '2024-09-16 15:48:53', 'public'),
(7, 2, 'Study Buddies (CALCULUS)', 'LF engineers yung tuturuan ako mag mah-- mathematical computations', 'uploads/oppenheimer.PNG', '2024-09-16 15:50:22', 'public'),
(8, 2, 'TEKKEN TA DUK', 'watatatatatata', 'uploads/tekken-8-3840x2160-20402.jpg', '2024-09-16 15:51:03', 'public'),
(9, 2, 'Black Myth: Wukong Fans', 'The GOAT monke', 'uploads/5047008.jpg', '2024-09-17 16:06:23', 'public'),
(10, 37, 'WMSU marketplace', 'blablabla', '', '2024-09-19 02:32:19', 'public'),
(11, 2, 'test', 'test', 'community_thumbs/markus-spiske-cjOAigK9xo0-unsplash.jpg', '2024-09-20 04:02:09', 'public'),
(12, 2, 'akakkak', 'hairless', 'community_thumbs/crazy-expression-draw-260nw-1351230482.png', '2024-09-23 03:19:19', 'public'),
(13, 3, 'edhgasjhdhjsadjkas', 'kjasbdkasbdksadj', 'community_thumbs/e6163f715a4e2a979dec5932de93f71a.png', '2024-09-25 01:54:26', 'public'),
(16, 3, 'game dev', 'game dev community', 'community_thumbs/57d025262e7907875beb666b64788899_1.jpg', '2024-10-13 11:22:37', 'public'),
(18, 2, 'adad', 'adadadada', 'community_thumbs/Tree.png', '2024-11-19 04:52:51', 'public'),
(19, 29, 'sadasd', 'dasdsd', 'community_thumbs/Green Aesthetic Poster Portrait.png', '2024-11-21 02:58:49', 'public'),
(20, 2, 'private', 'private', 'community_thumbs/127089.jpg', '2024-11-21 13:57:07', 'private'),
(21, 3, 'fdgfd', 'fgdfgdfg', 'community_thumbs/images (37).jpeg', '2024-11-21 15:29:47', 'private'),
(22, 3, 'naot', 'sadad', 'community_thumbs/5922707.jpg', '2024-11-22 07:14:09', 'private'),
(23, 2, 'test', 'asdasdsad', 'community_thumbs/wallpaperflare.com_wallpaper.jpg', '2024-11-22 07:16:14', 'private'),
(24, 3, 'test owner', 'sdsadsad', 'community_thumbs/6617357.jpg', '2024-11-22 07:20:27', 'public'),
(25, 2, 'bloodborne', 'yarnamers yarnamers', 'uploads/peakpx (3).jpg', '2024-11-22 15:40:47', 'public');

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
(5, 9, 3, 'sadasdasdsa', '2024-10-13 11:06:39', '2024-10-13 11:06:39');

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
(20, 25, 35, '2024-11-23 02:59:21', 'rejected');

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
(1, 2, 'admin', '2024-09-16 15:37:55'),
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
(12, 2, 'admin', '2024-09-23 03:19:19'),
(12, 3, 'member', '2024-11-22 09:15:24'),
(13, 2, 'admin', '2024-09-25 01:54:46'),
(16, 3, 'admin', '2024-10-13 11:22:37'),
(16, 35, 'admin', '2024-10-13 11:24:15'),
(18, 2, 'admin', '2024-11-19 04:52:51'),
(18, 3, 'member', '2024-11-21 15:29:26'),
(19, 3, 'member', '2024-11-21 15:22:11'),
(19, 29, 'admin', '2024-11-21 02:58:49'),
(20, 2, 'admin', '2024-11-21 13:57:07'),
(21, 3, 'admin', '2024-11-21 15:29:47'),
(22, 3, 'admin', '2024-11-22 07:14:09'),
(23, 3, 'admin', '2024-11-22 09:50:39'),
(23, 8, 'member', '2024-11-22 08:21:20'),
(23, 35, 'member', '2024-11-22 08:25:20'),
(24, 2, 'admin', '2024-11-22 07:20:47'),
(24, 3, 'member', '2024-11-22 08:02:48'),
(24, 35, 'member', '2024-11-22 07:54:22'),
(25, 2, 'admin', '2024-11-22 15:40:47'),
(25, 35, 'member', '2024-11-23 03:00:09');

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
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`PostID`, `CommunityID`, `UserID`, `Title`, `Content`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 9, 2, 'INTERESTED IN THIS GAME', 'should i read journey to the west first?', '2024-09-17 16:09:58', '2024-09-17 16:09:58'),
(2, 9, 37, 'how to crack this game?', 'asdkasdk', '2024-09-19 02:31:48', '2024-09-19 02:31:48'),
(3, 11, 2, 'dfzdfd', 'fdsfdsf', '2024-09-22 04:47:40', '2024-09-22 04:47:40'),
(4, 5, 2, 'hello guys', 'these are the community rules', '2024-09-23 03:20:06', '2024-09-23 03:20:06'),
(5, 13, 2, 'csjdbfskdbfdsf', 'skdfhskfhdsf', '2024-09-25 01:54:57', '2024-09-25 01:54:57'),
(6, 13, 2, 'sdfdsfdsf', 'fsdfds', '2024-09-25 01:55:07', '2024-09-25 01:55:07'),
(7, 9, 38, 'dfdsf', 'fsdfd', '2024-09-25 03:14:57', '2024-09-25 03:14:57'),
(8, 6, 2, 'Join na kayo!', 'build tayo business haha', '2024-10-13 07:36:25', '2024-10-13 07:36:25'),
(9, 6, 3, 'kangkong order', 'may nagbebenta ba ng kangkong chips?', '2024-10-13 11:06:32', '2024-10-13 11:06:32'),
(10, 16, 35, 'ef', 'edfed', '2024-10-13 11:23:12', '2024-10-13 11:23:12'),
(12, 7, 2, 'dad', 'dawdaw', '2024-11-21 03:05:00', '2024-11-21 03:05:00');

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
(4, 5, 3, 'dsadsad', '2024-10-13 11:06:42', '2024-10-13 11:06:42');

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
(69, 2, 35, '2024-10-02 10:30:12'),
(71, 3, 11, '2024-10-13 10:47:08'),
(77, 42, 2, '2024-11-19 10:56:54'),
(83, 2, 38, '2024-11-20 15:19:52'),
(90, 2, 30, '2024-11-20 15:40:04'),
(91, 2, 31, '2024-11-20 15:40:11'),
(97, 2, 3, '2024-11-20 16:11:08'),
(104, 3, 2, '2024-11-21 15:21:32');

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
(175, 35, 2, '2024-11-22 14:37:24'),
(176, 36, 2, '2024-11-22 14:37:27'),
(177, 37, 2, '2024-11-22 14:37:28'),
(178, 24, 2, '2024-11-22 14:37:30'),
(179, 44, 2, '2024-11-22 14:37:34'),
(180, 43, 2, '2024-11-22 14:37:37'),
(181, 80, 2, '2024-11-22 14:37:40'),
(182, 35, 35, '2024-11-23 03:01:30'),
(183, 178, 35, '2024-11-23 03:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Type` enum('comment','like','reply','follow','community','message') NOT NULL,
  `Content` text NOT NULL,
  `ReferenceID` int(11) DEFAULT NULL,
  `Seen` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(35, 2, 'Finding Your Passion: Exploring Majors and Career Paths', 'Choosing a major and deciding on a career path can feel overwhelming for many college students. How did you discover your passion and decide on your major? Share your journey, tips, and advice for fellow students who are still exploring their options.', '', '2024-05-11 13:31:39', '2024-09-13 02:26:38'),
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
(166, 42, 'efdf', 'dfdfdsfsdf', NULL, '2024-11-19 10:57:32', '2024-11-19 10:57:32'),
(171, 29, 'dadasddasasda', 'asdsad', NULL, '2024-11-21 02:58:19', '2024-11-21 02:58:19'),
(172, 29, 'asdasd', 'asdsad', NULL, '2024-11-21 02:59:57', '2024-11-21 02:59:57'),
(173, 29, 'asdsadas', 'asd', NULL, '2024-11-21 03:00:03', '2024-11-21 03:00:03'),
(178, 2, 'xcvx', 'xcvxcv', NULL, '2024-11-22 15:40:19', '2024-11-22 15:40:19');

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
(17, 32, 3, 'bobo', '2024-06-08 18:30:34', '2024-09-13 02:37:22'),
(18, 32, 2, 'sorry', '2024-06-11 06:53:29', '2024-09-13 02:37:22'),
(19, 32, 2, '1', '2024-06-11 07:09:04', '2024-09-13 02:37:22'),
(20, 48, 2, '2', '2024-06-11 07:09:09', '2024-09-13 02:37:22'),
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
(48, 104, 2, 'fsfdsfsdfsf', '2024-11-22 13:08:17', '2024-11-22 13:08:17');

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
(2, NULL, '', 'uploads/6b029309-494d-4bf9-adb3-e3bd6c58e86a.jpg', '2024-05-15', '2024-11-22 15:40:09'),
(3, NULL, '', 'uploads/sample_profile2.png', '2024-05-15', '0000-00-00 00:00:00'),
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
(35, 'fullname not available', '', 'uploads/127089.jpg', '2024-09-12', '0000-00-00 00:00:00'),
(36, 'fullname not available', '', NULL, '2024-09-15', '0000-00-00 00:00:00'),
(37, 'fullname not available', 'nwdiansid', 'uploads/WIN_20240919_07_37_25_Pro.jpg', '2024-09-19', '0000-00-00 00:00:00'),
(38, 'fullname not available', '', 'uploads/oppenheimer.PNG', '2024-09-25', '0000-00-00 00:00:00'),
(42, 'fullname not available', '', NULL, '2024-11-19', '2024-11-19 10:57:57'),
(43, 'fullname not available', '', NULL, '2024-11-19', '2024-11-19 11:02:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `dateJoined` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `dateJoined`) VALUES
(2, 'thorfin', 'bn201903321@wmsu.edu.ph', '$2y$10$N5P2OMT04NzWttcSwJnY6uZ57yUe1VUN8.mSsJY0vfTwPZvKXwlvC', '2024-09-08 16:00:00'),
(3, 'maryhad', 'as202201322@wmsu.edu.ph', '$2y$10$HRAnnyQqk2h/tn65MYXu4uYKlz04zw8SxDyZtQToTtNGBWUARnzMu', '2024-09-08 16:00:00'),
(5, 'walter', 'as202201324@wmsu.edu.ph', '$2y$10$FwJ/prLzRTYtI1ylinGFauFmYZGJB0B4N4t7G2T7FHN6MLUv.Dq2u', '2024-09-08 16:00:00'),
(6, 'naix', 'as202201323@wmsu.edu.ph', '$2y$10$qFkSQYwb7HljV5EPOIRXjuEx/9/GoSaCCN07q8eK9s1.rq7qREHmu', '2024-09-08 16:00:00'),
(7, 'luffy', 'as202201382@wmsu.edu.ph', '$2y$10$8VN/u15Xl3yoyEs7qTYyF.LA8lXi.ahIxoUFf8wJK1cMX1vnpm2ky', '2024-09-08 16:00:00'),
(8, 'pipin', 'bz201903321@wmsu.edu.ph', '$2y$10$8NK.MIqi4C0V2n6GXV8H5.z.sQBBufCPAbLHhqJiqLk.ANYIr5YoS', '2024-09-08 16:00:00'),
(9, 'rosemary', 'as202207322@wmsu.edu.ph', '$2y$10$dq.gzexMtx8vF5HP6ww00esX.tj4XH8O4n/5hLYuIhtv1cnH9GNLK', '2024-09-08 16:00:00'),
(10, 'james', 'ce202201322@wmsu.edu.ph', '$2y$10$f4gYD.T0TrB2fGlo9US4QuAPDBAC76K95dHkUDWS7VwqMCNszVoyK', '2024-09-08 16:00:00'),
(11, 'alfaisal', 'eh202201122@wmsu.edu.ph', '$2y$10$75FJMXnw6vzO0/iXvSdGWemzOWdRLnusO0GMesoWHUP3SiWnp0//W', '2024-09-08 16:00:00'),
(13, 'SMARTYPANTS', 'qr202201322@wmsu.edu.ph', '$2y$10$Ol9kxJ6H42SJzMDCIYo.4OjIJ2GTm9nFS2LvbVKqnBBqTDPhiGsIO', '2024-09-08 16:00:00'),
(14, 'crimson sky', 'cs202105423@wmsu.edu.ph', '$argon2i$v=19$m=65536,t=4,p=1$UURZSS44RnF3NzNKR3dIeQ$t8CeheFzNkGt7j5a7LPeG4BcSL2RUAJlYPb3FWGkgSA', '2024-09-08 16:00:00'),
(15, 'studentako', 'ps202201322@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$SDZwSVI1RWowb1hLc2lpTA$xObIlwO8IwhI9UV+vYZfBW/WEUCcsfEI50No0wCP0BU', '2024-09-08 16:00:00'),
(16, 'archiGirl', 'ty202201328@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$d21iYjh0UjBCeGRyQVVpcw$DAb+tBljh7AxYItfYNpPOLl1YMbvADXFUqvzxU6dH7s', '2024-09-08 16:00:00'),
(17, 'MICH', 'cv201903321@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$OUpnQVRUMmUwTkZ4aEZybw$56llL0cGQaLZYJo9wMS3i9GKd31CdpW5XUH8A9vCf3A', '2024-09-08 16:00:00'),
(18, 'squshy', 'eh201901122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$eldWZUp6S1dEbW8uUXZLYw$cFWCv3eqfUiuR/pxwTPh4u4pFHjTwdXqHR+KpSkGlb8', '2024-09-08 16:00:00'),
(19, 'fahad', 'eh202201822@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$bC5lZ0Q3MFJxWDg3YlVpUg$hWQDdUl1c5bysf40vMHTzLvYeMQRKBNEt5rj5vLo8Fw', '2024-09-08 16:00:00'),
(21, 'fahads', 'eh201901132@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$Q1J2blQuYWxxSkFTL3M3cA$vd/RshnW2pWci+e86XrX2eGwinNWzQ+D05WWG+AXNUQ', '2024-09-08 16:00:00'),
(22, 'amongus', 'eh201901298@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$Smk1OHNOd3hQLlFkZHdqSw$ML04YYW1ri/+Ei2uTO0uWl+Ebq8SgI6M5ATpnsdw8QA', '2024-09-08 16:00:00'),
(23, 'newpeep', 'eh202201177@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$RmZKemRHSnE4RUNEancyZQ$ef1q+3JboASWlbiBrITT1z6Ua/OyRc7R8s8VcG3uFaM', '2024-09-08 16:00:00'),
(24, 'michelle', 'eh202001122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$aWF1Y0hIazRqT1k3MGNiRg$oOHEdEIZpt7YLjzfJvuwcsi0V+enyzIOB4yjfTd/glU', '2024-09-08 16:00:00'),
(25, 'ahaboy', 'eh202501234@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$MXVNVkFsejU4SDF3MlZLdQ$whMIYCmLnN14w8aPegsc5DOO++o1A+Pc3nLmfeBCt0U', '2024-09-08 16:00:00'),
(26, 'may', 'op202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$cVBCSldJQzhNYVByQWxueA$dmZCGz+qTv4IYlZoLluF2jaL8vc+Kb3T55oBAfXQUDI', '2024-09-08 16:00:00'),
(27, 'aldrikz', 'eh202201120@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$VjFYaHUwdFpWbEphZ1ViNA$QvAtvUiyOvD9fCGO47uiqqkXPb9nkWSoSxRQV+eDpBE', '2024-09-08 16:00:00'),
(28, 'new user', 'ox202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$dXFHVFFHcHBULlJhdlVWTQ$W0tVYy7n6tCPQFpRvoQjWgIKk1k9VLO/U5F2MrZoFv4', '2024-09-08 16:00:00'),
(29, 'ronald_2222', 'oq202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$VG8yUkVGR1ZLS3MuWVN5Yw$OYA644z1CgXl7BPU/GJA7xwbZEosUuIjbBVoKRnPAhI', '2024-09-09 04:39:32'),
(30, 'wmsu_boy', 'ex202201122@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$bHZmMVNrNEFjWGJXZmZIUg$LSSx4kvHLzIgeG/T7tl3OIdP1iiF5A3XFZTP5rjoSCk', '2024-09-12 12:07:52'),
(31, 'jimmy', 'om202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$OUNRU2ozcnhkZTl2OGJ6bg$1rOfAu284J6IHDCaICPF0o9T84eorjM1Yi5ADk0OlWU', '2024-09-12 12:17:14'),
(32, 'jeoffrey', 'ox202201623@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$QnJmRERLaGJER0xQZ085aA$Y7ONEA8EMw3fog8z2e47NPFb4OLY206u5VCcIQ3OR+M', '2024-09-12 12:33:37'),
(35, 'geralt', 'op202201623@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$TkZRdEIydENzakpKUHJDMQ$y2OIqUVhA5WvetEVCctM07LMsjUYOR+zTMkkhPXEbqo', '2024-09-12 12:51:19'),
(36, 'batista', 'ob202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$dXljeGU5cXpQYmRRZ25MSQ$N0npeUazzA52Kz9EE3b8F6NdSLMnFGo1drRky4fk+Ok', '2024-09-15 13:37:13'),
(37, 'drikzz', 'eh202201066@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$QUU5QVBueFJYSUpzaXNtVA$1aRRKhPIU5v5sWcajchnG3kSbhoyHJ8Mb6knEzCZGKM', '2024-09-19 02:28:41'),
(38, 'jude', 'od202201623@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$eVF2Lk9xV2UzRHdQa3FiNA$WVMsIGiKUq/tus5kCMV9e6ZCJ1Urth044Hnz2zwSLeg', '2024-09-25 02:48:00'),
(41, 'boy', 'eh202201162@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$QW9TM0x5RWtpMHUuMHQvZA$y3Z95Yztpv6kcd2T20OVI5Fr5DrF2nScnD/bqbjJ9jc', '2024-11-19 10:14:35'),
(42, 'change', 'op202201128@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$ZVlwRmlmYXNHWXVhdTQydg$e7JlJQYq1ezWIjJCuTyrotScRpFTpCDp6Qtdr7G7jQU', '2024-11-19 10:42:24'),
(43, 'malakas', 'lp202201123@wmsu.edu.ph', '$argon2id$v=19$m=65536,t=4,p=1$RGc3UDNQSWZPRVpodkFheA$09rIVnCF6g146Kpx65oMPj+U+OEtW7WgVNXFszf542c', '2024-11-19 11:00:46');

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
(145, 35, '2024-11-23 03:00:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`CommunityID`),
  ADD KEY `CreatorID` (`CreatorID`);

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
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `CommunityID` (`CommunityID`),
  ADD KEY `UserID` (`UserID`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`ReplyID`),
  ADD KEY `CommentID` (`CommentID`),
  ADD KEY `UserID` (`UserID`);

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
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`SessionID`),
  ADD KEY `UserID` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `CommunityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `community_join_requests`
--
ALTER TABLE `community_join_requests`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `community_likes`
--
ALTER TABLE `community_likes`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `community_replies`
--
ALTER TABLE `community_replies`
  MODIFY `ReplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `FollowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `ReplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `userprofiles`
--
ALTER TABLE `userprofiles`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `SessionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `posts` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `communities`
--
ALTER TABLE `communities`
  ADD CONSTRAINT `communities_ibfk_1` FOREIGN KEY (`CreatorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

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
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`CommunityID`) REFERENCES `communities` (`CommunityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_posts_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

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
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`CommentID`) REFERENCES `comments` (`CommentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD CONSTRAINT `userprofiles_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 05:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mynotes`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `sno` int(7) NOT NULL,
  `uid` text NOT NULL,
  `pfp` text NOT NULL,
  `user` text NOT NULL,
  `passid` text NOT NULL,
  `phase` int(7) NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `last_profile_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`sno`, `uid`, `pfp`, `user`, `passid`, `phase`, `date`, `time`, `last_profile_edit`) VALUES
(1, 'abra', '', '235424', 'dabra', 0, '', '2025-04-20 22:33:10', NULL),
(2, '968067', '', 'lcoalhost', '', 0, '', '0000-00-00 00:00:00', NULL),
(3, '529085', '', '1', '1', 0, '', '12:34pm', NULL),
(5, '847730', '', '12', '12', 0, '', '12:54am', NULL),
(6, '698141', '', '123', '12', 0, '', '09:01pm', NULL),
(7, '462722', '', '21', '$2y$10$mTc80Dz4gyueO1Z6NMyh1edVDyuuQEAdhIBkMDXudVOzelI243wS.', 0, '', '09:06pm', NULL),
(8, '841798', '', '', '$2y$10$UfjJDXoMkDYm5j.oYqwzqOUF6oM/p3FPeOCVQk5MPs87w6QnBnBCK', 0, '24/06/2025', '03:42pm', NULL),
(9, '303848', '', '', '$2y$10$UnPp.VfcVcjfqiWAA/VHqeE9Z0NOHjbRtAW1mrlFy5bQKtw2ZQati', 0, '24/06/2025', '03:44pm', NULL),
(10, '436535', '', 'ab', '$2y$10$lftcJ47Iy0G0w/870YRZWef2SygUEc8cz7dGFBpOT86xtboCePPgu', 0, '24/06/2025', '03:45pm', NULL),
(11, '962161', '', 'fg', '$2y$10$UDIoWJGoRl92DZi8.PGGh.44rBqvTdFkACUhCv6Ewac.e.iALSEHu', 0, '24/06/2025', '03:46pm', NULL),
(12, '590353', '', '', '$2y$10$f.MSKdiilarNBhMbg0fWQ.RZu95qF25YMRs7wow8iA.jTnTAX0M32', 0, '24/06/2025', '03:47pm', NULL),
(13, '299149', '', 'avc', '$2y$10$ZzB7JZVQu0LQPth2kYZTK.j19YG6f9v8oEKbZvwrZHWcaIGIqi02e', 0, '24/06/2025', '03:49pm', NULL),
(14, '569058', '', '\'', '$2y$10$52bLNwt905d2WajJkMT2CuTN1YdJ5GSXi5YhNbhh2jKPJcceW3wra', 0, '24/06/2025', '03:50pm', NULL),
(15, '958200', '', '\"', '$2y$10$q09FWyQQffaShYkYZivFSO7y6DxKR2xq5Ua67g4vlZu1yKfKzdQCq', 0, '24/06/2025', '03:50pm', NULL),
(16, '218710', '', 'a', '$2y$10$.kgGF9HaX0rALKYZsvuVcurRYtJcRHtqnod6UZ0buVRnaw299gi3W', 0, '24/06/2025', '05:05pm', NULL),
(17, '456958', 'uploads/profile_456958_1751449841.jpeg', 'admin', '$2y$10$ipJuo.El19VUMc6fnfzN3uHtJcxdUgjOG4LyDXK3Dd6oUpfONrA2W', 0, '29/06/2025', '10:52am', '2025-07-05 08:57:42'),
(18, '541129', '', '123123', '$2y$10$kMwULCVR4UQfhhdM33dndOxdI1aHFf195.NucBFmAaT1MamWUTcJq', 0, '29/06/2025', '04:09pm', NULL),
(19, '759414', '', '12312333', '$2y$10$ocZTwRW4b8C5wSTw3wP0xuZFQLDd/yFJ.DhWFFdf9eIRpx4xUbObi', 0, '29/06/2025', '04:09pm', NULL),
(20, '987374', '', 'uifngifjghoing', '$2y$10$d1fcW5qtwf4gByn1xTj3fe1Yn9HhmJPUNQBHXF9BcFfRQXeMQt1Sq', 0, '29/06/2025', '04:11pm', NULL),
(21, '471080', '', 'normie', '$2y$10$JXuLWehwUeD4/RTs127BrOerGmcNWAvDjFdPxj959IUvq53Hn13tO', 0, '01/07/2025', '11:55pm', NULL),
(22, '539653', '', 'daku', '$2y$10$ufm587uKDx.4p2Rp8NAtZey23lkGmJqYPas5fOB6BQJFT9pu6QTPK', 0, '08/10/2025', '11:56am', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `sno` int(9) NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(7) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `nof_char` int(8) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`sno`, `uid`, `id`, `title`, `content`, `nof_char`, `date`, `time`) VALUES
(1, 0, 69, 'my_gt', 'its awesome', 4564, '0000-00-00 00:00:00', '22:12:00'),
(2, 0, 6, 'hehe', 'huhu', 456456, '0000-00-00 00:00:00', '00:49:00'),
(3, 462722, 5881197, 'hyya', 'ttttttttt', 1, '0000-00-00 00:00:00', '04:30:00'),
(4, 462722, 3357168, 'hyya', 'ttttttttt', 1, '0000-00-00 00:00:00', '04:47:00'),
(5, 462722, 1065887, 'a', 'a a a', 3, '0000-00-00 00:00:00', '05:04:00'),
(6, 218710, 2103002, 'a', 'a a a', 3, '0000-00-00 00:00:00', '05:06:00'),
(7, 218710, 3110934, 'lorem', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptates quis esse velit omnis eaque reiciendis perspiciatis enim necessitatibus voluptate accusamus suscipit aspernatur doloribus corporis cupiditate illo fugit voluptatibus dolorem?Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptates quis esse velit omnis eaque reiciendis perspiciatis enim necessitatibus voluptate accusamus suscipit aspernatur doloribus corporis cupiditate illo fugit voluptatibus dolorem?Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptates quis esse velit omnis eaque reiciendis perspiciatis enim necessitatibus voluptate accusamus suscipit aspernatur doloribus corporis cupiditate illo fugit voluptatibus dolorem?Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptates quis esse velit omnis eaque reiciendis perspiciatis enim necessitatibus voluptate accusamus suscipit aspernatur doloribus corporis cupiditate illo fugit voluptatibus dolorem? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptates quis esse velit omnis eaque reiciendis perspiciatis enim necessitatibus voluptate accusamus suscipit aspernatur doloribus corporis cupiditate illo fugit voluptatibus dolorem?', 150, '0000-00-00 00:00:00', '06:19:00'),
(26, 456958, 8428294, 'ggg', 'gggg', 1, '2025-07-02 08:25:57', '01:55pm'),
(27, 456958, 8329349, 'w', 'hehehehhehhhhhhhhhhheeeeeeeeeeeee', 1, '2025-07-02 08:29:54', '01:59pm'),
(28, 456958, 6585430, '6', '6', 0, '2025-07-02 08:30:06', '02:00pm'),
(29, 456958, 6192424, '7587', '5675', 0, '2025-07-02 08:30:09', '02:00pm'),
(30, 456958, 9699193, '8', '8', 0, '2025-07-02 08:30:22', '02:00pm'),
(31, 456958, 1775272, 'ffffffff', 'ffffffffff', 1, '2025-07-02 08:30:28', '02:00pm'),
(32, 456958, 8657564, 'fdhfh', 'fjfj', 1, '2025-07-02 08:30:34', '02:00pm'),
(33, 456958, 4264313, 'asasdadas', 'adasdadad', 1, '2025-06-02 08:31:05', '02:01pm'),
(34, 456958, 3520471, 'ffffffff', 'fffffffff', 1, '2025-06-23 08:31:40', '02:01pm'),
(35, 456958, 3212315, 'test', 'asda asd 4 asda', 3, '2025-06-23 08:32:22', '02:02pm'),
(36, 539653, 9986684, 'first and foremost', 'whether the market is in an uptrend, downtrend or a consolidation phase ??', 12, '2025-10-08 06:28:59', '11:58am');

-- --------------------------------------------------------

--
-- Table structure for table `updated_notes`
--

CREATE TABLE `updated_notes` (
  `sno` int(11) NOT NULL,
  `id` int(9) NOT NULL,
  `uid` int(9) NOT NULL,
  `old_title` text NOT NULL,
  `old_content` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `nof_char` int(11) NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `updated_notes`
--
ALTER TABLE `updated_notes`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `sno` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `sno` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `updated_notes`
--
ALTER TABLE `updated_notes`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 08:31 AM
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
-- Database: `online_voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` varchar(25) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `photo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `photo`) VALUES
('admin', 'Abhay', 'abc@gmail.com', 'admin', 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` varchar(25) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `about` varchar(500) NOT NULL,
  `position_id` int(11) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `email`, `password`, `gender`, `about`, `position_id`, `photo`, `status`) VALUES
('C2025091607071132', 'Shekhar Prajapati', 'shekhar@gmail.com', '1', 'Male', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 8, 'C2025091607071132.jpg', 'approved'),
('C2025091607384244', 'Aarav Sharma', 'arav@gmail.com', '1', 'Male', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,', 8, 'default.jpg', 'approved'),
('C2025091607395129', 'Aditya Reddy', 'aditya@gmail.com', '1', 'Male', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book', 9, 'C2025091607395129.jpg', 'approved'),
('C202509160746363', 'Neil panday', 'neil@gmail.com', '1', 'Male', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', 8, 'default.jpg', 'pending'),
('C2025091607475245', 'Simran Kaur', 'simran@gmail.com', '1', 'Female', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words ', 9, 'C2025091607475245.png', 'approved'),
('C2025091607482139', 'test', 'test@gmail.com', '1', 'Other', 'test', 10, 'default.jpg', 'rejected'),
('C2025091608252729', 'Tanvi Joshi', 'tanvi@gmail.com', '1', 'Female', 'The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 0, 'default.jpg', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `title`, `description`) VALUES
(8, 'President', 'The position of President is the highest executive role within our governance structure, carrying the responsibility of leading with vision, accountability, and dedication to public service. As outlined by our constitution and election guidelines, the President serves as the primary representative of the people, overseeing the implementation of policies, safeguarding institutional values, and ensuring the effective functioning of the government or organization.'),
(9, 'Vice President', 'The position of Vice President holds a crucial role in the leadership structure, serving as a key support to the President and an active participant in shaping the direction and success of the administration.'),
(10, 'CR', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `id` varchar(25) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `photo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `name`, `email`, `password`, `photo`) VALUES
('V202509160708368', 'Aarav Sharma', 'arav@gmail.com', '1', 'default.jpg'),
('V2025091607090284', 'Ananya Iyer', 'ananya@gmail.com', '1', 'default.jpg'),
('V2025091607092028', 'Rohan Patel', 'rohan@gmail.com', '1', 'default.jpg'),
('V202509160712308', 'Meera Nair', 'meera@gmail.com', '1', 'V202509160712308.png'),
('V2025091607131680', 'Siddharth Reddy', 'sid@gmail.com', '1', 'V2025091607131680.jpg'),
('V2025091607141682', 'Karan Mehta', 'karan@gmail.com', '1', 'default.jpg'),
('V2025091607143168', 'Divya Menon', 'divya@gmail.com', '1', 'default.jpg'),
('V2025091607144712', 'Arjun Singh', 'arjun@gmail.com', '1', 'default.jpg'),
('V2025091607363734', 'Harpreet Singh', 'harpreet@gmail.com', '1', 'default.jpg'),
('V2025091607371655', 'Pranav Mukherjee', 'pranav@gmail.com', '1', 'default.jpg'),
('V2025091608234798', 'Lucky singh', 'lucky@gmail.com', '1', 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `position_id` int(11) NOT NULL,
  `candidate_id` varchar(25) NOT NULL,
  `voter_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`position_id`, `candidate_id`, `voter_id`) VALUES
(8, 'C2025091607071132', 'V202509160708368'),
(9, 'C2025091607475245', 'V202509160708368'),
(8, 'C2025091607071132', 'V2025091607090284'),
(9, 'C2025091607475245', 'V2025091607090284'),
(8, 'C2025091607384244', 'V2025091607092028'),
(9, 'C2025091607395129', 'V2025091607092028'),
(8, 'C2025091607071132', 'V202509160712308'),
(8, 'C2025091607071132', 'V2025091607141682');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

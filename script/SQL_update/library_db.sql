-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 08:47 AM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`, `remember_token`) VALUES
(2, 'Admin', '$2y$10$GUhRRJIP20HNEBKgmxZ2s.GRKc0c1LIz5BkpFSsYea9BWi5x9wfMG', '2025-04-03 06:16:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `picture` varchar(255) DEFAULT 'images/default-book.jpg',
  `isbn` varchar(13) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `copyright_year` year(4) DEFAULT NULL,
  `stocks` int(11) DEFAULT 0,
  `publisher` varchar(255) DEFAULT NULL,
  `status` enum('Available','Not Available') DEFAULT 'Available',
  `category` varchar(100) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `picture`, `isbn`, `title`, `author`, `copyright_year`, `stocks`, `publisher`, `status`, `category`, `date_added`) VALUES
(1, 'images/books/9780132350884.jpg', '9780132350884', 'Clean Code', 'Robert C. Martin', '2008', 4, 'Prentice Hall', 'Available', 'Programming', '2025-04-04 07:40:36'),
(2, 'images/default-book.jpg', '9780134685991', 'Effective Java', 'Joshua Bloch', '2018', 4, 'Addison-Wesley', 'Available', 'Programming', '2025-04-04 07:40:36');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('Borrowed','Returned') DEFAULT 'Borrowed',
  `fine_amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `student_id`, `book_isbn`, `borrow_date`, `due_date`, `return_date`, `status`, `fine_amount`) VALUES
(1, 'EQX0875936100', '9780132350884', '2025-04-08', '2025-04-15', '2025-04-10', 'Returned', 0.00),
(2, 'EQX0875936100', '9780134685991', '2025-04-08', '2025-04-15', '2025-04-15', 'Returned', 0.00),
(5, 'EQX0875936100', '9780132350884', '2025-04-10', '2025-04-17', '2025-04-15', 'Returned', 0.00),
(10, 'EQX087593147', '9780134685991', '2025-04-10', '2025-04-17', '2025-04-10', 'Returned', 0.00),
(12, 'EAX221770785', '9780134685991', '2025-04-10', '2025-04-17', '2025-04-15', 'Returned', 0.00),
(13, 'EAX221770785', '9780134685991', '2025-04-10', '2025-04-17', '2025-04-15', 'Returned', 0.00),
(14, 'EAX221770785', '9780132350884', '2025-04-10', '2025-04-17', '2025-04-15', 'Returned', 0.00),
(15, 'EAX221770785', '9780134685991', '2025-04-10', '2025-04-17', '2025-04-15', 'Returned', 0.00),
(20, 'EAX221770785', '9780132350884', '2025-04-15', '2025-04-22', '2025-04-23', 'Returned', 0.00),
(21, 'EAX221770785', '9780132350884', '2025-04-15', '2025-04-22', NULL, 'Borrowed', 0.00),
(22, 'EAX221770785', '9780134685991', '2025-04-15', '2025-04-22', NULL, 'Borrowed', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `date_added`) VALUES
(1, 'Programming', '2025-04-04 07:47:52'),
(2, 'Database', '2025-04-04 07:47:52'),
(3, 'Networking', '2025-04-04 07:47:52'),
(4, 'Nursing', '2025-04-04 08:07:29'),
(5, 'asd', '2025-04-07 03:30:32'),
(6, 'Hello world', '2025-04-07 03:36:30'),
(7, 'my world', '2025-04-07 05:37:54'),
(8, 'Hello world3', '2025-04-07 05:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`) VALUES
(1, 'BSCS', 'Bachelor of Science in Computer Science'),
(2, 'BSIS', 'Bachelor of Science in Information Systems'),
(8, 'BSIT', 'Bachelor of Science in Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `date_return` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `course` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT 'images/default.jpg',
  `student_id` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `year_level` enum('1st Year','2nd Year','3rd Year','4th Year') NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `course`, `photo`, `student_id`, `firstname`, `lastname`, `mobile_no`, `gender`, `year_level`, `date_registered`, `status`) VALUES
(2, 'BSCS', 'images/default.jpg', 'EQX087593147', 'Jane', 'Smith', '09198765432', 'Female', '1st Year', '2025-04-04 07:12:03', 'Active'),
(6, 'BSCS', 'images/default.jpg', 'EQX0875936100', 'Jundel', 'Malazarte', '09198764151', 'Male', '4th Year', '2025-04-04 08:40:58', 'Active'),
(11, 'BSIT', 'images/default.jpg', 'EAX221770785', 'Ryan', 'Gabutin', '09198764151', 'Male', '4th Year', '2025-04-10 02:48:25', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `fine_amount` decimal(10,2) DEFAULT 10.00,
  `max_borrow_days` int(11) DEFAULT 7,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `fine_amount`, `max_borrow_days`, `updated_at`) VALUES
(1, 10.00, 5, '2025-04-15 06:20:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

-- ALTER TABLE for admin
ALTER TABLE `admin`
ADD COLUMN `firstname` varchar(100) DEFAULT NULL AFTER `password`,
ADD COLUMN `lastname` varchar(100) DEFAULT NULL AFTER `firstname`,
ADD COLUMN `email` varchar(100) DEFAULT NULL AFTER `lastname`,
ADD COLUMN `photo` varchar(255) DEFAULT 'images/default.jpg' AFTER `email`,
ADD COLUMN `status` enum('Active','Inactive') DEFAULT 'Active' AFTER `photo`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

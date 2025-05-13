
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

-- ALTER TABLE for admin
ALTER TABLE `admin`
ADD COLUMN `firstname` varchar(100) DEFAULT NULL AFTER `password`,
ADD COLUMN `lastname` varchar(100) DEFAULT NULL AFTER `firstname`,
ADD COLUMN `email` varchar(100) DEFAULT NULL AFTER `lastname`,
ADD COLUMN `photo` varchar(255) DEFAULT 'images/default.jpg' AFTER `email`,
ADD COLUMN `status` enum('Active','Inactive') DEFAULT 'Active' AFTER `photo`;


--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`, `remember_token`) VALUES
(2, 'Admin', '$2y$10$GUhRRJIP20HNEBKgmxZ2s.GRKc0c1LIz5BkpFSsYea9BWi5x9wfMG', '2025-04-03 06:16:43', NULL);

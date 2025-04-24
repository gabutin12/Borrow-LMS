ALTER TABLE `admin`
ADD COLUMN `firstname` varchar(100) DEFAULT NULL AFTER `password`,
ADD COLUMN `lastname` varchar(100) DEFAULT NULL AFTER `firstname`,
ADD COLUMN `email` varchar(100) DEFAULT NULL AFTER `lastname`,
ADD COLUMN `photo` varchar(255) DEFAULT 'images/default.jpg' AFTER `email`,
ADD COLUMN `status` enum('Active','Inactive') DEFAULT 'Active' AFTER `photo`;
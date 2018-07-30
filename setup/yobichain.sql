-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 26, 2018 at 09:34 AM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.31-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yobichain-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `asset_masterlist`
--

CREATE TABLE `asset_masterlist` (
  `id` int(11) NOT NULL,
  `asset_name` varchar(200) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `url` text,
  `ip` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `browser` text,
  `ref` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `browser` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_masterlist`
--

CREATE TABLE `message_masterlist` (
  `message_id` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `alert` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message_masterlist`
--

INSERT INTO `message_masterlist` (`message_id`, `message`, `alert`) VALUES
(3, 'New user has been created and an activation email has been sent.', 'success'),
(4, 'A user with this email address already exists.', 'danger'),
(6, 'A new administrator has been created.', 'success'),
(7, 'An admin with this email address already exists.', 'danger'),
(8, 'Your password has been emailed to you.', 'info'),
(11, 'User has been approved.', 'success'),
(12, 'Password has been reset.', 'success'),
(13, 'You have logged out.', 'info'),
(14, 'Password reset instructions have been emailed to you.', 'success'),
(20, 'Invalid credentials.', 'danger'),
(23, 'The email address associated with this administrator is invalid', 'danger'),
(27, 'New passwords must match.', 'danger'),
(29, 'You are not authorized to access this resource.', 'danger'),
(30, 'Your password has been changed.', 'success'),
(31, 'Existing password entered by you is incorrect.', 'danger'),
(32, 'You must upload a file.', 'danger'),
(35, 'This user is already verified.', 'danger'),
(36, 'Activation code has been emailed to the user. ', 'success'),
(37, 'Enter your email and password to login.', 'info'),
(38, 'Invalid email address', 'danger'),
(39, 'Invalid user', 'danger'),
(40, 'A new blockchain asset has been created successfully', 'success'),
(41, 'Asset creation failed', 'danger'),
(42, 'Asset updated successfully', 'success'),
(43, 'Asset re-issue succeeded', 'success'),
(44, 'Asset name entered is wrong. Please enter the correct asset name.', 'danger'),
(45, 'Asset deleted successfully.', 'success'),
(46, 'An error occurred while deleting the asset. Please try again later!', 'danger'),
(47, 'User deleted successfully.', 'success'),
(48, 'An error occurred while deleting the user. Please try again later!', 'danger'),
(49, 'User name entered is wrong. Please enter the correct user name.', 'danger'),
(51, 'Asset not found', 'danger'),
(52, 'Asset details updated successfully.', 'success'),
(53, 'Error updating asset details.', 'danger'),
(54, 'Please select an asset to edit.', 'info'),
(55, 'Please select an asset to delete.', 'info'),
(56, 'Error processing request. Please check the server logs.', 'danger'),
(68, 'Invalid User.', 'danger'),
(72, 'You do not have permission to manage this user.', 'danger'),
(81, 'An error occurred while updating the event.', 'danger'),
(84, 'Invalid role category code!', 'danger'),
(89, 'You do not have permission to manage alerts for this event.', 'danger'),
(91, 'Alert deleted successfully.', 'success'),
(92, 'Error changing password!', 'danger'),
(94, 'Insufficient balance!', 'danger'),
(96, 'Offer created successfully!', 'success'),
(98, 'Recipient address is invalid!!', 'danger'),
(100, 'Quantity cannot be negative!!', 'danger'),
(101, 'You do not own this asset!!', 'danger'),
(103, 'Asset redeemed successfully! Your transaction ID is:', 'success'),
(104, 'Asset sold successfully. Your transaction ID is:', 'success'),
(105, 'Offer accepted.', 'success'),
(106, 'You do not own the asset demanded by the offer.', 'danger'),
(107, 'No exchange asset(s) provided.', 'danger'),
(108, 'Invalid transaction. No asset(s) offered.', 'danger'),
(109, 'You do not own the offered asset.', 'danger'),
(110, 'Transaction Successful. Your transaction ID is:', 'success'),
(111, 'Invalid asset !', 'danger'),
(112, 'User updated successfully.', 'success'),
(113, 'You cannot issue additional quantities of this asset !', 'danger'),
(114, 'An asset with this name already exists!', 'danger'),
(115, 'File size exceeded limit!', 'danger'),
(116, 'Invalid Request!', 'danger'),
(117, 'Asset name cannot exceed 32 characters!!', 'danger'),
(118, 'Asset measuring unit cannot exceed 50 characters!!', 'danger'),
(119, 'Description cannot exceed 2000 characters!!', 'danger'),
(120, 'Please enter a valid asset name!!', 'danger'),
(121, 'Quantity has to be numeric.', 'danger'),
(122, 'Minimum divisible unit has to be numeric.', 'danger'),
(123, 'Minimum Divisible Unit should be between 0.00000001 & 1 !', 'danger'),
(124, 'Quantity should be greater than threshold value!', 'danger'),
(125, 'Minimum quantity should be greater than 0!', 'danger'),
(126, 'Quantity cannot be less than minimum divisible unit!!', 'danger'),
(127, 'An error occured while issuing asset. Please try again later!', 'danger'),
(128, 'Error uploading user details!!', 'danger'),
(129, 'Quantity cannot be zero or negative!!', 'danger'),
(130, 'Asset does not exist!!', 'danger'),
(131, 'This asset is not re-issuable!!', 'danger'),
(132, 'Transaction failed!!', 'danger');

-- --------------------------------------------------------

--
-- Table structure for table `role_category`
--

CREATE TABLE `role_category` (
  `role_category_id` int(11) NOT NULL,
  `role_category_code` varchar(25) DEFAULT NULL,
  `role_category_title` varchar(25) DEFAULT NULL,
  `role_category_icon` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_category`
--

INSERT INTO `role_category` (`role_category_id`, `role_category_code`, `role_category_title`, `role_category_icon`) VALUES
(7, 'sam', 'SAM (Smart Asset Manager)', 'bar-chart-o'),
(14, 'dave', 'DAVE (Data Authentication', 'money');

-- --------------------------------------------------------

--
-- Table structure for table `role_masterlist`
--

CREATE TABLE `role_masterlist` (
  `role_id` int(11) NOT NULL,
  `role_category` varchar(25) DEFAULT NULL,
  `role_code` varchar(255) NOT NULL,
  `role_display` varchar(1) DEFAULT 'y',
  `role_title` varchar(255) DEFAULT NULL,
  `role_icon` varchar(25) DEFAULT NULL,
  `role_detail` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_masterlist`
--

INSERT INTO `role_masterlist` (`role_id`, `role_category`, `role_code`, `role_display`, `role_title`, `role_icon`, `role_detail`) VALUES
(100, 'general', 'view_dashboard', 'y', 'Yobichain Dashboard', 'dashboard', 'View notifications and stats'),
(610, 'sam', 'create_asset', 'y', 'Create a blockchain asset', '', 'Create a new asset'),
(620, 'sam', 'view_asset', 'y', 'View blockchain assets', NULL, 'View all active and inactive assets'),
(650, 'sam', 'reissue_asset', 'y', 'Re-issue blockchain asset', NULL, 'Re-issue more units of an existing asset'),
(910, 'general', 'user_profile', 'y', 'User Profile', NULL, 'Display details of loggedin user - User id, Email id, Role Name, Session Start Time, Registered Phone Number, Logins'),
(930, 'general', 'logout', 'y', 'Logout', NULL, 'Allow logged in user to logout'),
(1215, 'general', 'password_change', 'y', 'Change password', NULL, 'Change password'),
(1220, 'sam', 'send_asset', 'y', 'Send blockchain asset', NULL, NULL),
(1224, 'sam', 'create_offer', 'y', 'Create an Offer', NULL, NULL),
(1225, 'sam', 'accept_offer', 'y', 'Accept an Offer', NULL, NULL),
(1226, 'dave', 'upload_document', 'y', 'Upload document to blockchain', NULL, NULL),
(1227, 'dave', 'transaction_details', 'n', 'Transaction Details', NULL, NULL),
(1228, 'dave', 'view_uploads', 'y', 'View Uploads', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_masterlist`
--

CREATE TABLE `user_masterlist` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_cell` varchar(13) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT '',
  `checked` enum('y','n') NOT NULL DEFAULT 'n',
  `user_public_address` varchar(255) DEFAULT NULL,
  `user_public_key` varchar(255) DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `random` varchar(40) DEFAULT NULL,
  `is_deleted` enum('y','n') NOT NULL DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_uploads`
--

CREATE TABLE `user_uploads` (
  `upload_id` int(11) NOT NULL,
  `file_hash` varchar(200) NOT NULL,
  `description` text,
  `transaction_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asset_masterlist`
--
ALTER TABLE `asset_masterlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_masterlist`
--
ALTER TABLE `message_masterlist`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `role_category`
--
ALTER TABLE `role_category`
  ADD PRIMARY KEY (`role_category_id`),
  ADD UNIQUE KEY `role_category_code` (`role_category_code`);

--
-- Indexes for table `role_masterlist`
--
ALTER TABLE `role_masterlist`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_code` (`role_code`);

--
-- Indexes for table `user_masterlist`
--
ALTER TABLE `user_masterlist`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- Indexes for table `user_uploads`
--
ALTER TABLE `user_uploads`
  ADD PRIMARY KEY (`upload_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asset_masterlist`
--
ALTER TABLE `asset_masterlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_masterlist`
--
ALTER TABLE `message_masterlist`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `role_category`
--
ALTER TABLE `role_category`
  MODIFY `role_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `role_masterlist`
--
ALTER TABLE `role_masterlist`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1231;

--
-- AUTO_INCREMENT for table `user_masterlist`
--
ALTER TABLE `user_masterlist`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_uploads`
--
ALTER TABLE `user_uploads`
  MODIFY `upload_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

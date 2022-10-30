-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2022 at 07:14 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indiahealth`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_subject` varchar(100) NOT NULL,
  `contact_message` varchar(500) NOT NULL,
  `contact_status` int(11) NOT NULL,
  `contact_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `doctor_user` int(11) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `doctor_mobile` varchar(10) NOT NULL,
  `doctor_email` varchar(200) NOT NULL,
  `doctor_address` varchar(500) NOT NULL,
  `doctor_pincode` int(11) NOT NULL,
  `doctor_recent` varchar(200) NOT NULL DEFAULT '[]',
  `doctor_license` int(11) NOT NULL DEFAULT 0,
  `doctor_status` int(11) NOT NULL DEFAULT 0,
  `doctor_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `doctor_user`, `doctor_name`, `doctor_mobile`, `doctor_email`, `doctor_address`, `doctor_pincode`, `doctor_recent`, `doctor_license`, `doctor_status`, `doctor_timestamp`) VALUES
(1, 3, 'BK Hospital', '9988776655', 'bhumir@gmail.com', 'Luhar Street', 365220, '[]', 1, 1, '2022-07-29 07:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `forgots`
--

CREATE TABLE `forgots` (
  `forgot_id` int(11) NOT NULL,
  `forgot_user` int(11) NOT NULL,
  `forgot_hash` varchar(500) NOT NULL,
  `forgot_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_user` int(11) NOT NULL,
  `group_text` varchar(200) NOT NULL,
  `group_dtext` varchar(200) NOT NULL,
  `group_unit` varchar(1000) NOT NULL,
  `group_status` int(11) NOT NULL DEFAULT 1,
  `group_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_user`, `group_text`, `group_dtext`, `group_unit`, `group_status`, `group_timestamp`) VALUES
(1, 1, 'General Blood Test', 'General Blood Test', '[\"1\",\"2\",\"8\",\"9\"]', 1, '2020-08-30 07:27:00'),
(2, 1, 'Diffrenntial Leukocyte Count', 'Diffrenntial Leukocyte Count', '[\"3\",\"4\",\"5\",\"6\",\"7\"]', 1, '2020-08-30 07:40:39'),
(3, 1, 'Blood Diseases Test', 'Blood Diseases Test', '[\"11\",\"10\",\"12\",\"13\",\"14\"]', 1, '2020-08-30 07:52:44');

-- --------------------------------------------------------

--
-- Table structure for table `laboratorys`
--

CREATE TABLE `laboratorys` (
  `laboratory_id` int(11) NOT NULL,
  `laboratory_user` int(11) NOT NULL,
  `laboratory_name` varchar(100) NOT NULL,
  `laboratory_mobile` varchar(10) NOT NULL,
  `laboratory_email` varchar(200) NOT NULL,
  `laboratory_address` varchar(500) NOT NULL,
  `laboratory_pincode` int(11) NOT NULL,
  `laboratory_license` int(11) NOT NULL DEFAULT 0,
  `laboratory_status` int(11) NOT NULL DEFAULT 0,
  `laboratory_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laboratorys`
--

INSERT INTO `laboratorys` (`laboratory_id`, `laboratory_user`, `laboratory_name`, `laboratory_mobile`, `laboratory_email`, `laboratory_address`, `laboratory_pincode`, `laboratory_license`, `laboratory_status`, `laboratory_timestamp`) VALUES
(1, 4, 'Jay Labs', '5566887799', 'jay@gmail.com', 'Aman Nagar', 365220, 1, 1, '2022-07-29 07:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE `mail` (
  `mail_id` int(11) NOT NULL,
  `mail_user` int(11) NOT NULL,
  `mail_from` varchar(50) NOT NULL,
  `mail_text` mediumtext NOT NULL,
  `mail_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `querys`
--

CREATE TABLE `querys` (
  `query_id` int(11) NOT NULL,
  `query_user` int(11) NOT NULL,
  `query_handel` int(11) NOT NULL,
  `query_subject` varchar(300) NOT NULL,
  `query_details` varchar(1000) NOT NULL,
  `query_message` varchar(500) NOT NULL,
  `query_solved` int(11) NOT NULL,
  `query_status` int(11) NOT NULL,
  `query_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `querys`
--

INSERT INTO `querys` (`query_id`, `query_user`, `query_handel`, `query_subject`, `query_details`, `query_message`, `query_solved`, `query_status`, `query_timestamp`) VALUES
(1, 3, 0, 'Apply For Doctor Registration', 'Doctor id is : 1', '', 0, 1, '2022-07-29 07:29:58'),
(2, 4, 0, 'Apply For Laboratory Registration', 'Laboratory id is : 1', '', 0, 1, '2022-07-29 07:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_sid` varchar(20) NOT NULL,
  `report_laboratory` int(11) DEFAULT NULL,
  `report_doctor` int(11) DEFAULT NULL,
  `report_patient` int(11) NOT NULL,
  `report_details` mediumtext NOT NULL,
  `report_auth` varchar(1000) NOT NULL,
  `report_status` int(11) NOT NULL DEFAULT 1,
  `report_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `report_sid`, `report_laboratory`, `report_doctor`, `report_patient`, `report_details`, `report_auth`, `report_status`, `report_timestamp`) VALUES
(1, 'aaaaaab', 1, 1, 1, '[{\"type\":\"template\",\"id\":\"3\",\"element\":[{\"type\":\"unit\",\"id\":\"1\",\"value\":\"14.50\",\"msg\":\"\"},{\"type\":\"group\",\"id\":\"3\",\"unit\":[{\"type\":\"unit\",\"id\":\"11\",\"value\":\"8\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"10\",\"value\":\"Nagative\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"12\",\"value\":\"3\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"13\",\"value\":\"5\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"14\",\"value\":\"45\",\"msg\":\"\"}]}]}]', 'QSVm5g#AOH7VFNtoSvG4Cj3bz9N9kuuv671GgejpBvmXkxrVGa9alFK9##nzTK#CI@yP16zM73kNz6LSrkY4H0rtYCZKdKtVccfEyr@m@fFPTN0DSvGH$fW93TWhtRW6kKDCtPe56Lu@w%@SUuQpqzk%t%c1NLl7bXbQIw%$AkLpjcdwgO2XdMT0tlC#EmhrqRQKk#CUQ1eFoXvYkYLclVLmhtZI9a9V24cBYc40tGu@t2bi1x5karKDl#b@TITqEbbcAhqz@JC%x7sU6W$M%qUW1rNp8NhsDP@tT#RLUjjj6i2Y52l90fGWurMbC$NPPG%Uh$25Hl#XiKLQtB8VAQNIHonBZL8IYrZ@o$udbXYSI3fXyDb0dvJSMyooJMhedA6Av$KA@I3UrjD$1ACM0mF$zDXI9olF$0Qx7gWPjWcI80M%1@RHqfFIZq@M7mbJ$%YdqQpbMniEpsMH2GQgIoj#vMcusAzDn5nJI8%xGl4707yr2P1mv42Levl7T1g7JHrtAMAm53KKyb2Yg3NX9LcAml2SZmyg0RXa7z27%vKbu@YlmO%FVd1EserN0mTWzufJUBDPtxTbioyEwl2t9aM$SAATXcqym81KhwOB#sB4HvXb3l', 3, '2022-07-29 07:31:48'),
(2, 'aaaaaac', 1, NULL, 4, '[{\"type\":\"group\",\"id\":\"1\",\"unit\":[{\"type\":\"unit\",\"id\":\"1\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"2\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"8\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"9\",\"value\":\"\",\"msg\":\"\"}]}]', 'QiB63llBXzyOeH2P#kVFHmX#VfEdlEDOqQxX6zfdWNDQ#CIpADavjrODdsQ2%GjP%E$GRZgSJPV7ggwi91HZm3FtsWkS3oPHu88OJGGXprqENIRlRZSGO7fkqS4VLb5P5@GVrEhGhPmloe$oQrjMZl3CTq4eS0ZFD5lMJLjcdqk7V9#LPVs#aGDgp0d5CE4KUQy1tqCfE4ME6OmIjb9ry3sPMJ0V#eTpO51UJ5L8XbVRv#YPXJlSU68KNOsUtRjzDnDlRB$GjkbjIMggS8jpycNy0c4vT7h1kTNQzvqFKcTBvUPJvBwQaGqbmAJEdyTbCohx@tsHrCw9Jbj8MUamgysEevtRr2ZWfTvvYTOBho@#v8sIMpgtMP#Jv3galZPGJLA$gXy@9geGXidpNKAtaj3JAOKlU6P$ZZhKB#7Zm90viSappF8CFj5gDfOYHxtyAz4fgXCZRoQUWQ#8xGjarS1%Kza%HP3DpXjoVGwikpVQOsPTC%C8lz0YhMSpBITHjH8cUeOZDFUeLNYQWqwmsPBDKT382vdXb$6%qJsGSK73CdhZj72FfuZtQUGcMVmLYca0#9FfAjkTcI%KzWQ9uesZk7OPZkMgW8iBGLOkr2YaMVvxlf7B3iT4ff9$TgaNKcL3', 1, '2022-09-08 22:42:47'),
(3, 'aaaaaad', 1, 1, 4, '[{\"type\":\"group\",\"id\":\"2\",\"unit\":[{\"type\":\"unit\",\"id\":\"3\",\"value\":\"60\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"4\",\"value\":\"50\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"5\",\"value\":\"4\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"6\",\"value\":\"3\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"7\",\"value\":\"0.2\",\"msg\":\"\"}]}]', 'GDGKZtxJC3IDLiCJDoVpqdgir3l@iP$3MbkuW#gAkkvQzfxjNcr25k0m1XNbLl35tpHM6$LmGQB296fVcsZmvGUo2l9BVG69IoKcmJEdpm9Fe#RM57Lcd@M$mW2gsn2eLORtWH3rA6bMC6h7SgnIdhJlIETO8N8r$2%C2kzH4VBx9B7@b9MbORieox93xpXO7@O87yelTvTL30Ru3sCL5ZQZmI@AELFn6D1hnthU2%@he0hpWMCGBRYNo$E5r$WQhIb8btr16dKORfQ#CuD@sYy#NBvq8Lx0MZlh6fk2#0bBWUT2SeL9VFH72QUl08iAKPeJ2pWqXGndwts9nil7HarHvSdcAwBdXYW88$V9uDwJWVMDNzu84YrdskWq9%spw7OYFMYMmv9x%cRB5ptUCeAcqTRST4KvF%mYX3dA%yEQeSBw@ag4D1rSJdmRaMz8m#WeOtl6QRR#q9KycnbVk@X619vr6@i$akEtTynJ6H%76AReXve@Q43hWKvYjf6vkSTiAVOi7pjIO9PN6C71s$Ey56NP4qm5f2txmkdl82Dc#v0z2Hwm4%@GS3LEABirV1J6srynrMQ9YBVIQjKV3eV4p2KzVnWg7WT@0SaScDU%puu9TK%Z5M2ckuiX$nLVbiwQrg6y6sxRJ932%ieW1TTH9lrsACiitGD9QgcnRZot1KbrAPJhFobpB@De', 3, '2022-09-09 06:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` int(11) NOT NULL,
  `template_user` int(11) NOT NULL,
  `template_text` varchar(200) NOT NULL,
  `template_element` varchar(5000) NOT NULL,
  `template_status` int(1) NOT NULL DEFAULT 1,
  `template_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`template_id`, `template_user`, `template_text`, `template_element`, `template_status`, `template_timestamp`) VALUES
(1, 1, 'Complate Blood Test', '[{\"type\":\"group\",\"id\":\"1\"},{\"type\":\"group\",\"id\":\"2\"},{\"type\":\"group\",\"id\":\"3\"}]', 1, '2020-08-30 07:40:57'),
(2, 1, 'Blood Test', '[{\"type\":\"unit\",\"id\":\"10\"},{\"type\":\"group\",\"id\":\"1\"},{\"type\":\"group\",\"id\":\"2\"}]', 1, '2020-08-31 08:03:05'),
(3, 1, 'Himoglobin & Blood Diseases Test', '[{\"type\":\"unit\",\"id\":\"1\"},{\"type\":\"group\",\"id\":\"3\"}]', 1, '2020-08-31 08:15:46');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_user` int(11) NOT NULL DEFAULT 1,
  `unit_text` varchar(100) NOT NULL,
  `unit_dtext` varchar(100) NOT NULL,
  `unit_option` varchar(500) NOT NULL,
  `unit_symbol` varchar(25) NOT NULL,
  `unit_range` varchar(400) NOT NULL,
  `unit_general` int(11) NOT NULL DEFAULT 0,
  `unit_status` int(11) NOT NULL,
  `unit_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_user`, `unit_text`, `unit_dtext`, `unit_option`, `unit_symbol`, `unit_range`, `unit_general`, `unit_status`, `unit_timestamp`) VALUES
(1, 1, 'Hemoglobin (Hb)', 'Hemoglobin (Hb)', '[]', 'gm%', '{\"status\":true,\"fix\":false,\"diff\":true,\"range\":{\"male\":{\"start\":14,\"end\":17},\"female\":{\"start\":12,\"end\":16}}}', 0, 1, '2020-08-30 07:12:02'),
(2, 1, 'White Blood Cells (WBC)', 'White Blood Cells (WBC)', '[]', '/cm', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":4000,\"end\":10000}}}', 0, 1, '2020-08-30 07:13:51'),
(3, 1, 'DLC - Neutophil', 'Neutophil', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":50,\"end\":70}}}', 0, 1, '2020-08-30 07:15:19'),
(4, 1, 'DLC - Lymphocytes', 'Lymphocytes', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":20,\"end\":40}}}', 0, 1, '2020-08-30 07:16:06'),
(5, 1, 'DLC - Eosinophil', 'Eosinophil', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":1,\"end\":5}}}', 0, 1, '2020-08-30 07:16:47'),
(6, 1, 'DLC - Monocyte', 'Monocyte', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":2,\"end\":5}}}', 0, 1, '2020-08-30 07:17:30'),
(7, 1, 'DLC - Basophine', 'Basophine', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0.5}}}', 0, 1, '2020-08-30 07:18:16'),
(8, 1, 'Red Blood Cells (RBC)', 'Red Blood Cells (RBC)', '[]', 'm/cu.mm', '{\"status\":true,\"fix\":false,\"diff\":true,\"range\":{\"male\":{\"start\":4,\"end\":5},\"female\":{\"start\":4,\"end\":6}}}', 0, 1, '2020-08-30 07:38:15'),
(9, 1, 'Platelet Count', 'Platelet Count', '[]', 'lacs/cu.mm', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":1.5,\"end\":4.5}}}', 0, 1, '2020-08-30 07:39:01'),
(10, 1, 'Mtalaria Test', 'Mtalaria Test', '[\"Positive\",\"Nagative\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-08-30 07:42:06'),
(11, 1, 'Erythrocyte Sedimentation Rate', 'Erythrocyte Sedimentation Rate', '[]', 'mm/1 hour', '{\"status\":true,\"fix\":false,\"diff\":true,\"range\":{\"male\":{\"start\":0,\"end\":9},\"female\":{\"start\":0,\"end\":12}}}', 0, 1, '2020-08-30 07:44:23'),
(12, 1, 'Bleeding Time', 'Bleeding Time', '[]', 'min', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":2,\"end\":7}}}', 0, 1, '2020-08-30 07:47:00'),
(13, 1, 'Clotting Time', 'Clotting Time', '[]', 'min', '{\"status\":true,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":4,\"end\":10}}}', 0, 1, '2020-08-30 07:47:24'),
(14, 1, 'Packed Cell Volume (PCV)', 'Packed Cell Volume (PCV)', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":true,\"range\":{\"male\":{\"start\":41,\"end\":50},\"female\":{\"start\":35,\"end\":44}}}', 0, 1, '2020-08-30 07:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_dob` date NOT NULL,
  `user_gender` varchar(10) NOT NULL,
  `user_mobile` varchar(10) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_profile` varchar(200) NOT NULL,
  `user_admin` int(11) NOT NULL DEFAULT 0,
  `user_doctor` int(11) NOT NULL DEFAULT 0,
  `user_laboratory` int(11) NOT NULL DEFAULT 0,
  `user_helper` int(11) NOT NULL DEFAULT 0,
  `user_patient` int(11) NOT NULL DEFAULT 1,
  `user_last` varchar(30) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 1,
  `user_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_profile`, `user_admin`, `user_doctor`, `user_laboratory`, `user_helper`, `user_patient`, `user_last`, `user_status`, `user_timestamp`) VALUES
(1, 'aaaab', 'India Health', '2001-01-01', 'male', '1122334455', 'smtcodes@gmail.com', 'aaaab', '', 1, 0, 0, 0, 1, 'admin', 1, '2020-11-29 20:31:34'),
(2, 'aaaac', 'Jishan', '2001-01-01', 'male', '1122334455', 'jishan@gmail.com', 'jishan', '', 0, 0, 0, 0, 1, '', 1, '2022-07-29 07:28:06'),
(3, 'aaaad', 'Bhumir', '2001-01-01', 'male', '9988776655', 'bhumir@gmail.com', 'bhumir', '', 0, 1, 0, 0, 1, 'doctor', 1, '2022-07-29 07:28:57'),
(4, 'aaaae', 'Jay', '2001-01-01', 'male', '5566887799', 'jay@gmail.com', 'jay', '', 0, 0, 1, 0, 1, 'laboratory', 1, '2022-07-29 07:32:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `forgots`
--
ALTER TABLE `forgots`
  ADD PRIMARY KEY (`forgot_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `laboratorys`
--
ALTER TABLE `laboratorys`
  ADD PRIMARY KEY (`laboratory_id`);

--
-- Indexes for table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`mail_id`);

--
-- Indexes for table `querys`
--
ALTER TABLE `querys`
  ADD PRIMARY KEY (`query_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forgots`
--
ALTER TABLE `forgots`
  MODIFY `forgot_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laboratorys`
--
ALTER TABLE `laboratorys`
  MODIFY `laboratory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mail`
--
ALTER TABLE `mail`
  MODIFY `mail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `querys`
--
ALTER TABLE `querys`
  MODIFY `query_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

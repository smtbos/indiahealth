-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2020 at 09:32 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.22

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

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `contact_name`, `contact_email`, `contact_subject`, `contact_message`, `contact_status`, `contact_timestamp`) VALUES
(1, 'Urmil Rupareliya', 'urmilrupareliya14@gmail.com', 'I Want to Contribute', 'I See The All Features of This INDIA HEALTH Website,\r\nIt\'s Amazing Work Your Organization Done.\r\n\r\nI Want to Do Any Help, That I Can Do For This Website.\r\nMy Mobile Numbers is +91 6354634577\r\n\r\nYou Can Contact Me Any Time.\r\n\r\nThank You For This Useful Website. ', 0, '2020-12-02 13:00:39');

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
(1, 2, 'Dr. SMT', '8128389164', 'smtbos@gmail.com', 'Luhar Street, Main Bazar,\r\nDamnagar', 365220, '[1]', 1, 1, '2020-12-02 13:24:32');

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

--
-- Dumping data for table `forgots`
--

INSERT INTO `forgots` (`forgot_id`, `forgot_user`, `forgot_hash`, `forgot_timestamp`) VALUES
(3, 5, 'DFwOh$@pwU0YlW7JHdgTMku#YqkGFB2E8n1Wmn9x0d#BbAAbH7I4nUDzhehS0%fq2tmjQal85$Evm$oTUdqEq4lZ@MySw2An934YV4uMe20UcZ8$KUFFUr', '2020-12-02 15:55:18');

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
(3, 1, 'Blood Diseases Test', 'Blood Diseases Test', '[\"11\",\"10\",\"12\",\"13\",\"14\"]', 1, '2020-08-30 07:52:44'),
(4, 1, 'UA - Physical Examination', 'Physical Examination', '[\"23\"]', 1, '2020-12-02 13:54:48'),
(5, 1, 'UA - Chemical Examination', 'Chemical Examination', '[\"19\",\"20\",\"21\",\"22\"]', 1, '2020-12-02 13:55:45'),
(6, 1, 'UA - Microscopic Examination', 'Microscopic Examination', '[\"8\",\"18\",\"17\",\"15\",\"16\"]', 1, '2020-12-02 13:57:17');

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
(1, 4, 'BK Laboratory', '9429873327', 'bklaboratory@gmail.com', 'Opp. Patel Vadi,\r\nDamnagar', 365220, 1, 1, '2020-12-02 13:31:15');

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
  `query_message` varchar(500) NOT NULL DEFAULT '',
  `query_solved` int(11) NOT NULL,
  `query_status` int(11) NOT NULL,
  `query_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `querys`
--

INSERT INTO `querys` (`query_id`, `query_user`, `query_handel`, `query_subject`, `query_details`, `query_message`, `query_solved`, `query_status`, `query_timestamp`) VALUES
(1, 2, 0, 'I Want To Register as Doctor.', 'Hello Sir/Madam, \r\n    \r\n        I Am Doctor At Lathi (State - Gujarat), I Just See Your Digital Website I Want Get Register at But I Don\'t Know The Procedure.\r\n\r\nPlease Guide me To Accomplish This\r\n\r\n    - Dr. SMT', 'Just Visit The FAQ Pages Where You Can Get Your Answers', 1, 1, '2020-12-02 13:11:04'),
(2, 2, 0, 'Apply For Doctor Registration', 'Doctor id is : 1', 'Application Approved, Thank You For Registration in Our Digital System.', 1, 1, '2020-12-02 13:24:32'),
(3, 4, 0, 'Apply For Laboratory Registration', 'Laboratory id is : 1', 'Application Approved, Thank You For Registration in Our Digital System.', 1, 1, '2020-12-02 13:31:15'),
(4, 4, 1, 'I Want To Change Laboratory Mobile Number', 'My Laboratory Name is : BK Laboratory\r\nMy Old Number is 9429873327\r\nI Want to Replace it By 7016853984', 'We Are Passing The Request to Higher Authority, it Take Few Days to Solve.', 0, 1, '2020-12-02 13:38:38'),
(5, 2, 0, 'My Neighborhood Forgot Their Password and User id', 'Here Name in Mustakim Kureshi,\r\nMobile Number 8200157662,\r\nPlease help..', 'Here Username is aaaaf, New Password is Sended to Registered Email id', 1, 1, '2020-12-02 15:30:25');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_sid` varchar(20) NOT NULL DEFAULT 'aaaaaaa',
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
(1, 'aaaaaab', 1, 1, 3, '[{\"type\":\"template\",\"id\":\"2\",\"element\":[{\"type\":\"unit\",\"id\":\"10\",\"value\":\"Nagative\",\"msg\":\"\"},{\"type\":\"group\",\"id\":\"1\",\"unit\":[{\"type\":\"unit\",\"id\":\"1\",\"value\":\"15.5\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"2\",\"value\":\"6000\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"8\",\"value\":\"5\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"9\",\"value\":\"3.5\",\"msg\":\"\"}]},{\"type\":\"group\",\"id\":\"2\",\"unit\":[{\"type\":\"unit\",\"id\":\"3\",\"value\":\"60\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"4\",\"value\":\"30\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"5\",\"value\":\"4\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"6\",\"value\":\"2\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"7\",\"value\":\"0.33\",\"msg\":\"\"}]}]}]', '7W#4$QEtf5SwSGM8LmdZhFUj3cJVY0HPzhDeurz41g2HGRJ5#WJyybfWx@RP2Y%d#ewJ@TA7tCow#50oqAKvW0IQ7qQdZbMJi5DuIKrxQAy$h3404k3XYM7H51K#3PTwsXVdAVv9oS25wbcTgl$11z#acBz04X@$eMggL$h%sFMFLbT@FuDU3noLzWcI4eJcGuNRZsGw10N$dmmZWbPsu#H8I#erPKeR11sOAurfgs2L$gG3Ka%h9LaVhtP5pZSMPGjJJtC9jU%11lOOzgLfc8fzsa4apj45wzeBOJ3FgI$n$Fq4dxCwvR@B26fjloH5EuRtNHS%Kc0r1f6MtKis%6$MyqcAETexeHh86tyX1lcWN4y%D%f$SHSMBhv8@r7EC$ddQGuWl1BEAm$WNVN$6BJ7k0EP1wVumz2tOjj#0DZGoXPt@6Ke$dXit@@K$V@AuwT7eO0fwtXfH1paKMk$MPrs7qTHhGVAfwN32E3EFJE@7$qsaOr8m1Vju@rzw4hD3Ud4f4ZucXCy4g1ZjX5ltMxuZ@pJmuD3MxHCQgPg73Ux$ugnw5tRixxHnRa@I$UhGkxLAf6ghF9PJEjTsla6Fynytv9Ei0XtuxQWtQa9Y5Bs5E', 3, '2020-12-02 15:01:18'),
(2, 'aaaaaac', 1, NULL, 5, '[{\"type\":\"template\",\"id\":\"3\",\"element\":[{\"type\":\"unit\",\"id\":\"1\",\"value\":\"15\",\"msg\":\"\"},{\"type\":\"group\",\"id\":\"3\",\"unit\":[{\"type\":\"unit\",\"id\":\"11\",\"value\":\"8\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"10\",\"value\":\"Nagative\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"12\",\"value\":\"5\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"13\",\"value\":\"6\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"14\",\"value\":\"55\",\"msg\":\"\"}]}]}]', 'xHC%SyO$4Q98NS69rL5LIS#j#3myGrKQM5p5p@wxWo5Wxu5k73%%neCu9EXIYZS5gbB4$5se9iPr8PGiT#Xgn7G4e19%GqiXGsfcEUG6Xfm66ju@8ngag7fY11Jpxda2C$Lmna6@9LMEHKleGcalyQS1QqK4N3IJKBLc3cIJ6VIkZrKlp8a@W#Jaz0QdQ$JdrK@mhqh%Nj1LCdpc2t7hGRLr3EdODQ9lPMxBWWAdC$rvukk%QJTmV61zaNhVUauEUmLlqDoxPhgEd#ooXy3aQWDG6xtiO@AzmMSySGA2#h#zAk1XDuKUR6yWCEEeYWsMTUTQk3VpnQbXMwZWw%MYPa6rIDHW3LRclODAsN8S%VyRBl478Bq5jdtssL0QuV5K@vTXXqDiGW9R85HN5HcEKiUYPYY57X0AG#YN2DG#bG7bJNNVkKSwYQYLTB5nyLv3K5KdiiFoM%E44bqPNfJaYZhQ9v1KBwV1wZ5Petuzan3i@okAs1gW5XoeuY9UianiEztap3lM21NNM9sEhR9U6$2toeRZKA3Yb85anU2ptoE03MqR#dyOcwBlZv2nnoI5KL34UNCbty5MGhsBSQ0U0wXwH3VhRv$jFr$mS@wLlOzzG@UPgq$U8bwQmQ', 3, '2020-12-02 15:14:00'),
(3, 'aaaaaad', NULL, 1, 5, '[{\"type\":\"template\",\"id\":\"4\",\"element\":[{\"type\":\"group\",\"id\":\"4\",\"unit\":[{\"type\":\"unit\",\"id\":\"23\",\"value\":\"\",\"msg\":\"\"}]},{\"type\":\"group\",\"id\":\"5\",\"unit\":[{\"type\":\"unit\",\"id\":\"19\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"20\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"21\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"22\",\"value\":\"\",\"msg\":\"\"}]},{\"type\":\"group\",\"id\":\"6\",\"unit\":[{\"type\":\"unit\",\"id\":\"8\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"18\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"17\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"15\",\"value\":\"\",\"msg\":\"\"},{\"type\":\"unit\",\"id\":\"16\",\"value\":\"\",\"msg\":\"\"}]}]}]', 'TOuIGNk3GdZkDZKH#4tM0E6Gd21E0ihFk$zwr#Afm@Jcp7rGU4XOiMYj5IuVNgglM0kHk1jNmD%g5BwxHZpn@dY6ce3RaerQM8yjlPwYFNsgfWqrP8cowx2pb6XVMnlfmtz8BMAoJHh00CI6fJX6OJLeSJ9iHUNIc7WfMreNa5w%ptHQTw0CMJuWqguqvHWhQTnIvvb0ypWIoo9$%UVN40gEb6uJO$gQ3OeTH$43iLCVh1BimSBGUodAkfCFpPd9rEzOuB%6U$U0hEZcdrv41YhUjXVJb9T9JSnWF@ZMSb2N6GlXgq#vz#1xfDI9vv4JJmjc4HHgP#CrLjUjKSC8F89vMDpN5bQ76T%L$mlsI3eSl@VXFb94jTgA9qEpPL5kxKVeOTF#7eeX0$6HVZcKmyG#yMyhd45Q19PPSsY9Ixduz$s@Pdh0%1I5hPp2roKE$0htWBCiJnzL%ar6sqdVgL1FS$5YfENN#R2JZtPvj4EixuzGIo810VROX0nIwvXAJiBZFd@XjAuHgavAJ0Fkr5aLfczJUQE2Jy@jMl', 1, '2020-12-02 15:15:30');

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
(3, 1, 'Himoglobin & Blood Diseases Test', '[{\"type\":\"unit\",\"id\":\"1\"},{\"type\":\"group\",\"id\":\"3\"}]', 1, '2020-08-31 08:15:46'),
(4, 1, 'Urine Analysis', '[{\"type\":\"group\",\"id\":\"4\"},{\"type\":\"group\",\"id\":\"5\"},{\"type\":\"group\",\"id\":\"6\"}]', 1, '2020-12-02 14:18:22');

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
(14, 1, 'Packed Cell Volume (PCV)', 'Packed Cell Volume (PCV)', '[]', '%', '{\"status\":true,\"fix\":false,\"diff\":true,\"range\":{\"male\":{\"start\":41,\"end\":50},\"female\":{\"start\":35,\"end\":44}}}', 0, 1, '2020-08-30 07:51:09'),
(15, 1, 'UA - Mucus', 'Mucus', '[\"NIL\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:42:56'),
(16, 1, 'UA - Bacteria', 'Bacteria', '[\"NIL\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:43:30'),
(17, 1, 'UA - Cast', 'Cast', '[\"NIL\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:44:11'),
(18, 1, 'UA - Crystals', 'Crystals', '[\"NIL\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:46:37'),
(19, 1, 'UA - Albumin', 'Albumin', '[\"ABSENT\",\"PRESENT\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:47:43'),
(20, 1, 'UA - Sugar', 'Sugar', '[\"ABSENT\",\"PRESENT\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:48:10'),
(21, 1, 'UA - Acetone', 'Acetone', '[\"ABSENT\",\"PRESENT\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:48:40'),
(22, 1, 'UA - Bile Salts', 'Bile Salts', '[\"ABSENT\",\"PRESENT\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:49:22'),
(23, 1, 'UA - Transperancy', 'Transperancy', '[\"CLEAR\",\"CLOUDY\"]', '', '{\"status\":false,\"fix\":false,\"diff\":false,\"range\":{\"all\":{\"start\":0,\"end\":0}}}', 0, 1, '2020-12-02 13:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(100) NOT NULL DEFAULT 'aaaaa',
  `user_name` varchar(100) NOT NULL,
  `user_dob` date NOT NULL,
  `user_gender` varchar(10) NOT NULL,
  `user_mobile` varchar(10) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_profile` varchar(200) NOT NULL DEFAULT '',
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
(1, 'aaaab', 'India Health', '2000-01-01', 'male', '1122334455', 'smtcodes@gmail.com', 'baaaa', '', 1, 0, 0, 0, 1, 'admin', 1, '2020-11-29 20:31:34'),
(2, 'aaaac', 'Smit Bosamiya', '2001-01-01', 'male', '8128389164', 'smtbos@gmail.com', 'OsOKtvV#', '', 0, 1, 0, 0, 1, '', 1, '2020-12-02 13:10:04'),
(3, 'aaaad', 'Jay Lathigara', '2001-01-01', 'male', '8200459124', 'sonijaydip7600@gmail.com', 'jaydip', '', 0, 0, 0, 1, 1, 'helper', 1, '2020-12-02 13:21:57'),
(4, 'aaaae', 'Bhumir Bosamiya', '2001-01-01', 'male', '9429873327', 'bhumirbosamiya007@gmail.com', 'bhumir', '', 0, 0, 1, 0, 1, 'laboratory', 1, '2020-12-02 13:29:53'),
(5, 'aaaaf', 'Mustakim Kureshi', '2001-01-01', 'male', '8200157662', 'smitteck@gmail.com', 'QmhjiQtS', '', 0, 0, 0, 0, 1, '', 1, '2020-12-02 15:11:21');

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
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forgots`
--
ALTER TABLE `forgots`
  MODIFY `forgot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `laboratorys`
--
ALTER TABLE `laboratorys`
  MODIFY `laboratory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `querys`
--
ALTER TABLE `querys`
  MODIFY `query_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

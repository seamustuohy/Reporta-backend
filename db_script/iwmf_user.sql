-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2015 at 10:07 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `iwmf_user`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `firstname` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastname` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gender` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `gender_type` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 = male,2 = female,3 = other',
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'English',
  `language_code` enum('EN','AR','ES','FR','IW','TR') NOT NULL DEFAULT 'EN' COMMENT 'EN(English) , AR(Arabic), ES(Spanish), FR(Franch), IW(Hebrew), TR(Turkish)',
  `phone` varchar(30) NOT NULL,
  `jobtitle` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `affiliation_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `freelancer` int(11) NOT NULL,
  `origin_country` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `working_country` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sendmail` enum('0','1') NOT NULL COMMENT '0=False, 1=True',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '-1=Locked, 1= Active, 0= InActive',
  `forgotpassword_code` varchar(500) DEFAULT NULL,
  `type` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 = Admin, 2 = user',
  `send_update_repota_email` int(1) NOT NULL DEFAULT '0' COMMENT 'information mail sent or not about any update   0= not,  1=sent ',
  `devicetoken` text,
  `device_type` int(1) DEFAULT NULL COMMENT '1= ios 2= android ',
  `lock_admin` int(11) NOT NULL DEFAULT '0' COMMENT '0= not lock by admin , 1 = lock by admin',
  `login_attempt_number` int(11) NOT NULL DEFAULT '0' COMMENT 'if is larger then 6 then lock user',
  `last_login_time` datetime DEFAULT NULL,
  `app_encryption_key` varchar(50) DEFAULT NULL,
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '0 = not delete, 1= delete',
  `salt` varchar(10) NOT NULL,
  `password_change` datetime DEFAULT '0000-00-00 00:00:00',
  `checkin_count` int(11) DEFAULT '0',
  `alert_count` int(11) DEFAULT '0',
  `last_checkin_time` datetime DEFAULT '0000-00-00 00:00:00',
  `checkin_status` int(2) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  `islogin` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0= not login ,  1= login',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14504398280658 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

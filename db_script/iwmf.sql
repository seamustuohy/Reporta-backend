-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2015 at 10:06 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `iwmf`
--

-- --------------------------------------------------------

--
-- Table structure for table `affiliations`
--

CREATE TABLE IF NOT EXISTS `affiliations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  ` affiliation` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `situation` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timezone_id` varchar(50) DEFAULT '0',
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `latitude` varchar(50) DEFAULT '0',
  `longitude` varchar(50) DEFAULT '0',
  `safetycheckin` enum('0','1') DEFAULT '0' COMMENT '0=False, 1=True',
  `fb_token` varchar(300) DEFAULT NULL,
  `twitter_token` varchar(300) DEFAULT NULL,
  `twitter_token_secret` varchar(300) DEFAULT NULL,
  `is_mediasend` int(1) DEFAULT '0',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '0= not deleted, 1= deleted',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  `username` varchar(500) DEFAULT ' ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `associated_contacts`
--

CREATE TABLE IF NOT EXISTS `associated_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactlist_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=500 ;

-- --------------------------------------------------------

--
-- Table structure for table `broadcast`
--

CREATE TABLE IF NOT EXISTS `broadcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(25) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `table_id` enum('1','2','3') NOT NULL COMMENT '1 = checkin, 2 = alerts, 3 = sos',
  `timezone_id` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=596 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE IF NOT EXISTS `checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `timezone_id` varchar(50) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL COMMENT 'Save in UTC',
  `endtime` datetime DEFAULT NULL COMMENT 'Save in UTC',
  `message_sms` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `message_email` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `message_social` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `receiveprompt` varchar(11) NOT NULL COMMENT '1-Email, 2-SMS, 3-Social',
  `frequency` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0-Pending, 1-Started, 2-Confirmed, 3-Deleted, 4-Closed, 5-Missed',
  `laststatustime` datetime NOT NULL,
  `nextconfirmationtime` datetime NOT NULL,
  `fb_token` varchar(300) DEFAULT NULL,
  `twitter_token` varchar(300) DEFAULT NULL,
  `twitter_token_secret` varchar(300) DEFAULT NULL,
  `checkin_enabled` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0= dissable(checkin is not missed)  1 = Enabled',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '''0= not deleted, 1= deleted''',
  `is_mailsend` tinyint(1) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `devicetoken` varchar(1000) NOT NULL,
  `type` int(1) DEFAULT NULL,
  `username` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=351 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkincontactlist`
--

CREATE TABLE IF NOT EXISTS `checkincontactlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foreign_id` int(11) NOT NULL,
  `contactlist_id` int(11) NOT NULL,
  `table_id` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 = Checkin, 2 = Alerts',
  PRIMARY KEY (`id`),
  KEY `checkin_id` (`foreign_id`),
  KEY `contactlist_id` (`contactlist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=926 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkinhistory`
--

CREATE TABLE IF NOT EXISTS `checkinhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checkin_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0-Pending, 1-Started, 2-Confirmed, 3-Deleted, 4-Closed, 5-Missed',
  `latitude` varchar(15) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `timezone_id` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8301 ;

-- --------------------------------------------------------

--
-- Table structure for table `contactlists`
--

CREATE TABLE IF NOT EXISTS `contactlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(16) DEFAULT NULL,
  `circle` enum('1','2','3') NOT NULL COMMENT '1- Private, 2- Public, 3-Social',
  `listname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `defaultstatus` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=False, 1=True',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=321 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `emails` text NOT NULL,
  `sos_enabled` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0-disable, 1-confirmed, 2-pending',
  `status` enum('-1','0','1','2') NOT NULL DEFAULT '-1' COMMENT '-1= RequestNotSent, 0= RequestSent,1=Accepted, 2=Decline',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=423 ;

-- --------------------------------------------------------

--
-- Table structure for table `devicetoken`
--

CREATE TABLE IF NOT EXISTS `devicetoken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `devicetoken` varchar(300) DEFAULT NULL,
  `type` enum('1','2') DEFAULT '1' COMMENT '1=ios, 2=android',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=353 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `broadcast_id` int(11) DEFAULT '0',
  `contact_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `otp` varchar(15) NOT NULL,
  `otp_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1=Enable, 0=Disable',
  `timezone_id` varchar(50) DEFAULT '0',
  `time` datetime NOT NULL,
  `latitude` varchar(15) DEFAULT '0',
  `longitude` varchar(15) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4455175 ;

-- --------------------------------------------------------

--
-- Table structure for table `headertoken`
--

CREATE TABLE IF NOT EXISTS `headertoken` (
  `headertoken_id` int(15) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `token` text NOT NULL,
  `devicetoken` text NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`headertoken_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempt`
--

CREATE TABLE IF NOT EXISTS `login_attempt` (
  `login_attempt_id` int(15) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `devicetoken` text NOT NULL,
  `login_attempt_number` int(2) NOT NULL,
  `last_attempt_time` datetime NOT NULL,
  PRIMARY KEY (`login_attempt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `table_id` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 = Checkin, 2 = Alerts',
  `medianame` varchar(100) NOT NULL,
  `mediatype` enum('1','2','3') NOT NULL COMMENT '1-Audio, 2-Video, 3-Picture',
  `timezone_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` datetime NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oldpassword`
--

CREATE TABLE IF NOT EXISTS `oldpassword` (
  `oldpassword_id` int(15) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `password` varchar(500) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`oldpassword_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `sos`
--

CREATE TABLE IF NOT EXISTS `sos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `timezone_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` datetime NOT NULL,
  `latitude` varchar(15) DEFAULT '0',
  `longitude` varchar(15) DEFAULT '0',
  `sos_enabled` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 = Disabled, 1 = Enabled',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '''0= not deleted, 1= deleted''',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
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
  `devicetoken` text NOT NULL,
  `device_type` int(1) NOT NULL COMMENT '1= ios 2= android ',
  `lock_admin` int(11) NOT NULL DEFAULT '0' COMMENT '0= not lock by admin , 1 = lock by admin',
  `salt` varchar(10) NOT NULL,
  `password_change` datetime NOT NULL,
  `login_attempt_number` int(11) NOT NULL DEFAULT '0' COMMENT 'if is larger then 6 then lock user',
  `last_login_time` datetime DEFAULT NULL,
  `app_encryption_key` varchar(50) DEFAULT NULL,
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '0 = not delete, 1= delete',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `islogin` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0= not login ,  1= login',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=157 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkincontactlist`
--
ALTER TABLE `checkincontactlist`
  ADD CONSTRAINT `checkincontactlist_ibfk_2` FOREIGN KEY (`contactlist_id`) REFERENCES `contactlists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

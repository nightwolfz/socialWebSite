-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2011 at 11:19 PM
-- Server version: 5.5.13
-- PHP Version: 5.4.0alpha3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ccrush`
--
CREATE DATABASE `ccrush` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ccrush`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `login_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `passwd` varchar(20) NOT NULL,
  `signed_up` int(11) NOT NULL,
  `birthday` int(10) NOT NULL,
  `email` varchar(40) NOT NULL,
  `last_ip` varchar(20) NOT NULL,
  `login_count` mediumint(8) unsigned NOT NULL,
  `login_status` tinyint(1) NOT NULL DEFAULT '0',
  `invite_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`login_id`, `username`, `passwd`, `signed_up`, `birthday`, `email`, `last_ip`, `login_count`, `login_status`, `invite_status`) VALUES
(1, 'nightwolfz', 'aaaaaa', 1190743625, 606999933, 'nightwolfz@nightwolfz.com', '127.0.0.1', 293, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL DEFAULT '',
  `to` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `from`, `to`, `message`, `sent`, `recd`) VALUES
(1, 'nightwolfz', 'janedoe', 'test', '2010-09-18 18:17:18', 0),
(2, 'nightwolfz', 'janedoe', 'test', '2010-09-18 18:17:52', 0),
(3, 'nightwolfz', 'janedoe', 'yo nigga', '2010-09-18 18:17:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `confirm`
--

CREATE TABLE IF NOT EXISTS `confirm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Login_id` varchar(128) NOT NULL DEFAULT '',
  `key` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL DEFAULT '0',
  `login_name` varchar(20) NOT NULL DEFAULT '',
  `friend_id` int(11) NOT NULL DEFAULT '0',
  `friend_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `login_id`, `login_name`, `friend_id`, `friend_name`) VALUES
(1, 1, 'nightwolfz', 1, 'nightwolfz');

-- --------------------------------------------------------

--
-- Table structure for table `guestbook`
--

CREATE TABLE IF NOT EXISTS `guestbook` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `poster_id` int(11) NOT NULL,
  `poster_name` varchar(30) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `guestbook`
--

INSERT INTO `guestbook` (`id`, `owner_id`, `poster_id`, `poster_name`, `content`, `timestamp`) VALUES
(1, 1, 1, 'Guest_05613', 'testt', 1148602119),
(41, 1, 1, 'nightwolfz', 'some more', 1148609119),
(42, 1, 1, 'nightwolfz', 'lastest test, even moreeeee', 1148609119),
(44, 1, 1, 'nightwolfz', 'Testing some more', 1286979492),
(60, 1, 1, 'nightwolfz', 'Hellooo', 1315837757),
(58, 1, 1, 'nightwolfz', 'jtyjrtyj', 1315353140),
(59, 1, 1, 'nightwolfz', 'oooo', 1315353184);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_id`, `to_id`, `content`, `timestamp`) VALUES
(1, 1, 1, 'hello :)', '2011-09-20 20:33:21'),
(2, 1, 1, 'what''s up <br>\r\n\r\nyo <b>\\r\\n lol', '2011-09-20 21:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `msg`
--

CREATE TABLE IF NOT EXISTS `msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` varchar(30) NOT NULL DEFAULT '',
  `subject` varchar(50) NOT NULL DEFAULT '',
  `timestamp` varchar(18) DEFAULT NULL,
  `readed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `msg`
--

INSERT INTO `msg` (`id`, `from_id`, `to_id`, `subject`, `timestamp`, `readed`) VALUES
(1, 1, '1', 'Test message', '1284479735', 0),
(2, 1, '1', 'Second msg', '1284479735', 0),
(3, 1, '1', 'testttt', '1285963421', 0);

-- --------------------------------------------------------

--
-- Table structure for table `msg_convo`
--

CREATE TABLE IF NOT EXISTS `msg_convo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `msg_convo`
--

INSERT INTO `msg_convo` (`id`, `message_id`, `from_id`, `text`, `timestamp`) VALUES
(1, 1, 1, 'Really short ONEEEEE, well actually might be as short as some think.\r\n\r\n\r\nEither way, it''s awesome. and Lolz are due.', 1284479735),
(3, 1, 1, 'This is the second one \\o/ \r\nça va?\r\n\r\nla vie est belle!', 1284833883),
(4, 2, 1, 'Premier lol', 1284834399),
(5, 2, 1, 'Deuxième lol', 1284834399),
(6, 3, 1, 'loooool', 1285963421);

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL DEFAULT '0',
  `thumb` varchar(150) NOT NULL DEFAULT '',
  `avatar` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `login_id`, `thumb`, `avatar`) VALUES
(28, 1, '09/12/nightwolfz_1315840399_432.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `login_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(80) NOT NULL DEFAULT '',
  `mood` varchar(255) NOT NULL,
  `city` varchar(80) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `ori` varchar(80) NOT NULL DEFAULT '',
  `wants_to` varchar(200) NOT NULL DEFAULT 'meet',
  `with_who` tinyint(200) NOT NULL DEFAULT '0',
  `ethnics` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(80) NOT NULL DEFAULT 'Belgium',
  `location` varchar(80) NOT NULL DEFAULT '',
  `like` mediumtext NOT NULL,
  `profile` mediumtext NOT NULL,
  `msg_if` text NOT NULL,
  `notice_about` text NOT NULL,
  `good_at` text NOT NULL,
  `hair` varchar(80) NOT NULL DEFAULT '',
  `eyes` varchar(80) NOT NULL DEFAULT '',
  `bodytype` varchar(80) NOT NULL DEFAULT '',
  `face_hair` varchar(80) NOT NULL DEFAULT '',
  `body_hair` varchar(80) NOT NULL DEFAULT '',
  `height` varchar(20) NOT NULL DEFAULT '',
  `smoke` varchar(30) NOT NULL,
  `drinks` varchar(30) NOT NULL,
  `religion` varchar(30) NOT NULL,
  `zodiac` varchar(30) NOT NULL,
  `education` varchar(30) NOT NULL,
  `work` varchar(30) NOT NULL,
  `kids` varchar(50) NOT NULL,
  `animals` varchar(50) NOT NULL,
  `speaks` varchar(255) NOT NULL,
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `photo` varchar(70) NOT NULL DEFAULT '',
  `msn` varchar(80) NOT NULL DEFAULT '',
  `icq` varchar(80) NOT NULL DEFAULT '',
  `webcam` char(1) NOT NULL DEFAULT '',
  `last_online` int(10) NOT NULL,
  `plugins` varchar(255) NOT NULL,
  `skin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`login_id`, `username`, `mood`, `city`, `gender`, `ori`, `wants_to`, `with_who`, `ethnics`, `country`, `location`, `like`, `profile`, `msg_if`, `notice_about`, `good_at`, `hair`, `eyes`, `bodytype`, `face_hair`, `body_hair`, `height`, `smoke`, `drinks`, `religion`, `zodiac`, `education`, `work`, `kids`, `animals`, `speaks`, `photo_id`, `photo`, `msn`, `icq`, `webcam`, `last_online`, `plugins`, `skin`) VALUES
(1, 'nightwolfz', 'feeling dizzy ...', '', 0, 'Straight', 'meet', 0, 'Africain', 'Be', 'Bruxelles', 'About 1 m 85\nDark Brown Longish Hair\nBrown eyes\n\nI Like Listining to Music, watching Movies n such\nAlso like relaxing, working out n so\n\nJust your average guy next door', 'ça va les gens ?\r\n\r\nje suis à l''aise!!', '', '', 'ppppppp\r\n\r\nCa vaà  l''aise yooo', '', '', 'Normal', '', '', '1.50m-1.60m', 'Jamais', 'Oui', 'Budhiste', 'Lion', 'Universié', 'Etudiant(e)', 'Non ou peut être un jour', 'Oui j''aime bien et j''en ai', 'a:3:{i:0;s:9:"Français";i:1;s:9:"Portugais";i:2;s:8:"Espagnol";}', 8, '03/1284837594_287.jpg', '', '', '', 1311195455, 'a:1:{s:9:"guestbook";i:1;}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `unitalk`
--

CREATE TABLE IF NOT EXISTS `unitalk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_link` varchar(200) NOT NULL,
  `visits` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

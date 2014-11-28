-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 10, 2013 at 09:35 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `timeline`
--

-- --------------------------------------------------------

--
-- Table structure for table `biblical-book`
--

CREATE TABLE IF NOT EXISTS `biblical-book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `biblical-book`
--

INSERT INTO `biblical-book` (`id`, `name`) VALUES
(1, 'Genesis'),
(2, 'Jasher'),
(3, 'Jubilees');

-- --------------------------------------------------------

--
-- Table structure for table `biblical-month`
--

CREATE TABLE IF NOT EXISTS `biblical-month` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `biblical-month`
--

INSERT INTO `biblical-month` (`id`, `name`) VALUES
(1, 'Nisan'),
(2, 'Iyyar'),
(3, 'Sivan'),
(4, 'Tammuz'),
(5, 'Av'),
(6, 'Elul'),
(7, 'Tishri'),
(8, 'Kheshvan'),
(9, 'Kislev'),
(10, 'Tevet'),
(11, 'Shevat'),
(12, 'Adar'),
(13, 'Adar II');

-- --------------------------------------------------------

--
-- Table structure for table `biblical-reference`
--

CREATE TABLE IF NOT EXISTS `biblical-reference` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookId` int(11) unsigned NOT NULL,
  `chapter` int(11) unsigned NOT NULL,
  `verse` int(11) unsigned NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'verse quote if appropriate',
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueRef` (`bookId`,`chapter`,`verse`),
  KEY `comment` (`comment`),
  KEY `bookId` (`bookId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='biblical references' AUTO_INCREMENT=73 ;

--
-- Dumping data for table `biblical-reference`
--

INSERT INTO `biblical-reference` (`id`, `bookId`, `chapter`, `verse`, `text`, `comment`) VALUES
(1, 1, 1, 1, 'In the beginning God created the heaven and the earth', NULL),
(52, 1, 23, 32, 'Text of verse', NULL),
(55, 1, 2, 4, 'And there was light.', NULL),
(59, 2, 4, 4, 'this is jasher 4:4', NULL),
(60, 2, 1, 1, 'This is Jasher 1:1', NULL),
(64, 3, 1, 1, 'this is jubilees 1:1', NULL),
(65, 3, 2, 7, 'dgffdgdf', NULL),
(67, 2, 2, 6, 'this is jasher 2:6', NULL),
(68, 2, 2, 7, 'this is jasher 2:7', NULL),
(69, 1, 3, 6, 'Try inserting this.', NULL),
(70, 1, 3, 7, 'And another verse!!!', NULL),
(72, 1, 4, 1, 'Add this verse if you dare!', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Used to annotate other entries' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `text`) VALUES
(7, 'New Comment'),
(8, 'New Comment with whiskers');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'group name',
  `reference` int(10) unsigned DEFAULT NULL,
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment` (`comment`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `thing`
--

CREATE TABLE IF NOT EXISTS `thing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` int(10) unsigned DEFAULT NULL,
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reference` (`reference`),
  KEY `comment` (`comment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `what`
--

CREATE TABLE IF NOT EXISTS `what` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Common name for the event',
  `format` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ex: who1 is the father of who2',
  `inv-format` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ex: who2 is a son of who1',
  `what-type` int(10) unsigned NOT NULL,
  `who1` int(10) unsigned DEFAULT NULL,
  `who2` int(10) unsigned DEFAULT NULL,
  `group1` int(10) unsigned DEFAULT NULL,
  `group2` int(10) unsigned DEFAULT NULL,
  `whenStart1` int(10) unsigned DEFAULT NULL,
  `whenEnd1` int(10) unsigned DEFAULT NULL,
  `whenStart2` int(10) unsigned DEFAULT NULL,
  `whenEnd2` int(10) unsigned DEFAULT NULL,
  `where1` int(10) unsigned DEFAULT NULL,
  `where2` int(10) unsigned DEFAULT NULL,
  `thing1` int(10) unsigned DEFAULT NULL,
  `thing2` int(10) unsigned DEFAULT NULL,
  `reference` int(11) unsigned DEFAULT NULL COMMENT 'biblical references for event',
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reference` (`reference`),
  KEY `comment` (`comment`),
  KEY `what-type` (`what-type`),
  KEY `obj-who` (`who1`),
  KEY `obj-group` (`group1`),
  KEY `obj-when` (`whenStart1`),
  KEY `obj-where` (`where1`),
  KEY `obj-whenEnd` (`whenEnd1`),
  KEY `obj-thing` (`thing1`),
  KEY `who2` (`who2`),
  KEY `group2` (`group2`),
  KEY `whenStart2` (`whenStart2`),
  KEY `whenEnd2` (`whenEnd2`),
  KEY `where2` (`where2`),
  KEY `thing2` (`thing2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This is the key table that ties all other data together.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `what-type`
--

CREATE TABLE IF NOT EXISTS `what-type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'describes class of event',
  `desc` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `what-type`
--

INSERT INTO `what-type` (`id`, `desc`) VALUES
(1, 'birth'),
(2, 'death'),
(3, 'marriage'),
(4, 'war'),
(5, 'prophecy'),
(6, 'fullfilment'),
(7, 'feast'),
(8, 'fact'),
(9, 'quote');

-- --------------------------------------------------------

--
-- Table structure for table `when`
--

CREATE TABLE IF NOT EXISTS `when` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ex: Birth of Abram',
  `biblical-year` int(11) unsigned NOT NULL COMMENT 'creation-based year',
  `gregorian-date` date DEFAULT NULL,
  `biblical-Month` tinyint(3) unsigned DEFAULT NULL COMMENT 'FK to biblicalMonth',
  `biblical-day` tinyint(4) DEFAULT NULL COMMENT 'day of biblical month',
  `relative-year` int(11) DEFAULT NULL COMMENT 'If this is  not 0 then the when field is the time this relative year applies to',
  `when-basis` int(11) unsigned DEFAULT NULL COMMENT 'when that relative time is based on',
  `biblical-reference` int(10) unsigned DEFAULT NULL,
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment` (`comment`),
  KEY `biblical-Month` (`biblical-Month`),
  KEY `biblical-reference` (`biblical-reference`),
  KEY `when-basis` (`when-basis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `where`
--

CREATE TABLE IF NOT EXISTS `where` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'location name (city, country, etc.)',
  `lat` int(11) DEFAULT NULL,
  `long` int(11) DEFAULT NULL,
  `reference` int(11) unsigned DEFAULT NULL COMMENT 'biblical reference to place',
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reference` (`reference`),
  KEY `comment` (`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `where`
--

INSERT INTO `where` (`id`, `name`, `lat`, `long`, `reference`, `comment`) VALUES
(1, 'Earth', NULL, NULL, 1, NULL),
(2, 'heaven', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `who`
--

CREATE TABLE IF NOT EXISTS `who` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'individuals in history',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'common name of person',
  `whenBorn` int(11) unsigned DEFAULT NULL,
  `whenDied` int(11) unsigned DEFAULT NULL,
  `whereBorn` int(10) unsigned DEFAULT NULL,
  `whereDied` int(10) unsigned DEFAULT NULL,
  `reference` int(11) unsigned DEFAULT NULL COMMENT 'biblical reference of person',
  `comment` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment` (`comment`),
  KEY `reference` (`reference`),
  KEY `whenBorn` (`whenBorn`),
  KEY `whenDied` (`whenDied`),
  KEY `whereBorn` (`whereBorn`),
  KEY `whereDied` (`whereDied`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `who`
--

INSERT INTO `who` (`id`, `name`, `whenBorn`, `whenDied`, `whereBorn`, `whereDied`, `reference`, `comment`) VALUES
(1, 'God', NULL, NULL, NULL, NULL, 1, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biblical-reference`
--
ALTER TABLE `biblical-reference`
  ADD CONSTRAINT `biblical@002dreference_ibfk_4` FOREIGN KEY (`bookId`) REFERENCES `biblical-book` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `biblical@002dreference_ibfk_5` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`);

--
-- Constraints for table `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_4` FOREIGN KEY (`reference`) REFERENCES `biblical-reference` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `group_ibfk_5` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`);

--
-- Constraints for table `thing`
--
ALTER TABLE `thing`
  ADD CONSTRAINT `thing_ibfk_3` FOREIGN KEY (`reference`) REFERENCES `biblical-reference` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `thing_ibfk_4` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`);

--
-- Constraints for table `what`
--
ALTER TABLE `what`
  ADD CONSTRAINT `what_ibfk_100` FOREIGN KEY (`group2`) REFERENCES `group` (`id`),
  ADD CONSTRAINT `what_ibfk_101` FOREIGN KEY (`whenStart1`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `what_ibfk_102` FOREIGN KEY (`whenEnd1`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `what_ibfk_103` FOREIGN KEY (`whenStart2`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `what_ibfk_104` FOREIGN KEY (`whenEnd2`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `what_ibfk_105` FOREIGN KEY (`where1`) REFERENCES `where` (`id`),
  ADD CONSTRAINT `what_ibfk_106` FOREIGN KEY (`where2`) REFERENCES `where` (`id`),
  ADD CONSTRAINT `what_ibfk_107` FOREIGN KEY (`thing1`) REFERENCES `thing` (`id`),
  ADD CONSTRAINT `what_ibfk_108` FOREIGN KEY (`thing2`) REFERENCES `thing` (`id`),
  ADD CONSTRAINT `what_ibfk_109` FOREIGN KEY (`reference`) REFERENCES `biblical-reference` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `what_ibfk_110` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `what_ibfk_96` FOREIGN KEY (`what-type`) REFERENCES `what-type` (`id`),
  ADD CONSTRAINT `what_ibfk_97` FOREIGN KEY (`who1`) REFERENCES `who` (`id`),
  ADD CONSTRAINT `what_ibfk_98` FOREIGN KEY (`who2`) REFERENCES `who` (`id`),
  ADD CONSTRAINT `what_ibfk_99` FOREIGN KEY (`group1`) REFERENCES `group` (`id`);

--
-- Constraints for table `when`
--
ALTER TABLE `when`
  ADD CONSTRAINT `when_ibfk_11` FOREIGN KEY (`biblical-Month`) REFERENCES `biblical-month` (`id`),
  ADD CONSTRAINT `when_ibfk_12` FOREIGN KEY (`when-basis`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `when_ibfk_13` FOREIGN KEY (`biblical-reference`) REFERENCES `biblical-reference` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `when_ibfk_14` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`);

--
-- Constraints for table `where`
--
ALTER TABLE `where`
  ADD CONSTRAINT `where_ibfk_8` FOREIGN KEY (`reference`) REFERENCES `biblical-reference` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `where_ibfk_9` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`);

--
-- Constraints for table `who`
--
ALTER TABLE `who`
  ADD CONSTRAINT `who_ibfk_22` FOREIGN KEY (`whenBorn`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `who_ibfk_23` FOREIGN KEY (`whenDied`) REFERENCES `when` (`id`),
  ADD CONSTRAINT `who_ibfk_24` FOREIGN KEY (`whereBorn`) REFERENCES `where` (`id`),
  ADD CONSTRAINT `who_ibfk_25` FOREIGN KEY (`whereDied`) REFERENCES `where` (`id`),
  ADD CONSTRAINT `who_ibfk_26` FOREIGN KEY (`reference`) REFERENCES `biblical-reference` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `who_ibfk_27` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

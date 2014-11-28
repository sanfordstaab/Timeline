-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2013 at 12:47 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

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
-- Table structure for table `biblebook`
--

CREATE TABLE IF NOT EXISTS `biblebook` (
  `bookId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`bookId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `biblebook`
--

INSERT INTO `biblebook` (`bookId`, `name`) VALUES
(1, 'Genesis'),
(2, 'Jasher'),
(3, 'Jubilees');

-- --------------------------------------------------------

--
-- Table structure for table `biblemonth`
--

CREATE TABLE IF NOT EXISTS `biblemonth` (
  `bibleMonthId` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`bibleMonthId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `biblemonth`
--

INSERT INTO `biblemonth` (`bibleMonthId`, `name`) VALUES
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
-- Table structure for table `bibleref`
--

CREATE TABLE IF NOT EXISTS `bibleref` (
  `bibleRefId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookId` int(11) unsigned NOT NULL,
  `chapter` int(11) unsigned NOT NULL,
  `verse` int(11) unsigned NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'verse quote if appropriate',
  PRIMARY KEY (`bibleRefId`),
  UNIQUE KEY `uniqueRef` (`bookId`,`chapter`,`verse`),
  KEY `bookId` (`bookId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='biblical references' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bibleref`
--

INSERT INTO `bibleref` (`bibleRefId`, `bookId`, `chapter`, `verse`, `text`) VALUES
(1, 1, 1, 1, 'In the beginning God created the heaven and the earth');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `commentId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Used to annotate other entries' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `groupId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'group name',
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `relationship`
--

CREATE TABLE IF NOT EXISTS `relationship` (
  `relId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `relTypeId` int(11) unsigned NOT NULL,
  `whoId1` int(11) unsigned DEFAULT NULL,
  `groupId1` int(11) unsigned DEFAULT NULL,
  `thingId1` int(11) unsigned NOT NULL,
  `whatId1` int(11) unsigned NOT NULL,
  `whenId1` int(11) unsigned NOT NULL,
  `whereId1` int(11) unsigned NOT NULL,
  `commentId1` int(11) unsigned NOT NULL,
  `bibleRefId1` int(11) unsigned NOT NULL,
  `whoId2` int(11) unsigned NOT NULL,
  `groupId2` int(11) unsigned NOT NULL,
  `thingId2` int(11) unsigned NOT NULL,
  `whatId2` int(11) unsigned NOT NULL,
  `whenId2` int(11) unsigned NOT NULL,
  `whereId2` int(11) unsigned NOT NULL,
  `commentId2` int(11) unsigned NOT NULL,
  `bibleRefId2` int(11) unsigned NOT NULL,
  PRIMARY KEY (`relId`),
  KEY `relTypeId` (`relTypeId`),
  KEY `whoId1` (`whoId1`),
  KEY `groupId1` (`groupId1`),
  KEY `thingId1` (`thingId1`),
  KEY `whatId1` (`whatId1`),
  KEY `whenId1` (`whenId1`),
  KEY `whereId1` (`whereId1`),
  KEY `commentId1` (`commentId1`),
  KEY `bibleRefId1` (`bibleRefId1`),
  KEY `whoId2` (`whoId2`),
  KEY `groupId2` (`groupId2`),
  KEY `thingId2` (`thingId2`),
  KEY `whatId2` (`whatId2`),
  KEY `whenId2` (`whenId2`),
  KEY `whereId2` (`whereId2`),
  KEY `commentId2` (`commentId2`),
  KEY `bibleRefId2` (`bibleRefId2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='relates two things together' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reltype`
--

CREATE TABLE IF NOT EXISTS `reltype` (
  `relTypeId` int(11) unsigned NOT NULL,
  `fmtForward` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Relats ID1 to ID2',
  `fmtReverse` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Relates ID2 to ID1',
  `comment` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`relTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thing`
--

CREATE TABLE IF NOT EXISTS `thing` (
  `thingId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`thingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `what`
--

CREATE TABLE IF NOT EXISTS `what` (
  `whatId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Common name for the event',
  `whatTypeId` int(11) unsigned NOT NULL,
  PRIMARY KEY (`whatId`),
  KEY `what_ibfk_96` (`whatTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This is the key table that ties all other data together.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `whattype`
--

CREATE TABLE IF NOT EXISTS `whattype` (
  `whatTypeId` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'describes class of event',
  `desc` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`whatTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `whattype`
--

INSERT INTO `whattype` (`whatTypeId`, `desc`) VALUES
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
  `whenId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ex: Birth of Abram',
  `biblical-year` int(11) unsigned NOT NULL COMMENT 'creation-based year',
  `gregorian-date` date DEFAULT NULL,
  `bibleMonthId` tinyint(3) unsigned DEFAULT NULL COMMENT 'FK to bibleMonth',
  `bibleDay` tinyint(4) DEFAULT NULL COMMENT 'day of biblical month',
  `relative-year` int(11) DEFAULT NULL COMMENT 'If this is  not 0 then the when field is the time this relative year applies to',
  `whenBasisId` int(11) unsigned DEFAULT NULL COMMENT 'when that relative time is based on',
  PRIMARY KEY (`whenId`),
  KEY `bibleMonth` (`bibleMonthId`),
  KEY `when` (`whenBasisId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `where`
--

CREATE TABLE IF NOT EXISTS `where` (
  `whereId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'location name (city, country, etc.)',
  `Location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`whereId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `who`
--

CREATE TABLE IF NOT EXISTS `who` (
  `whoId` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'individuals in history',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'common name of person',
  PRIMARY KEY (`whoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `relationship`
--
ALTER TABLE `relationship`
  ADD CONSTRAINT `relationship_ibfk_17` FOREIGN KEY (`relTypeId`) REFERENCES `reltype` (`relTypeId`),
  ADD CONSTRAINT `relationship_ibfk_18` FOREIGN KEY (`whoId1`) REFERENCES `who` (`whoId`),
  ADD CONSTRAINT `relationship_ibfk_19` FOREIGN KEY (`groupId1`) REFERENCES `group` (`groupId`),
  ADD CONSTRAINT `relationship_ibfk_20` FOREIGN KEY (`thingId1`) REFERENCES `thing` (`thingId`),
  ADD CONSTRAINT `relationship_ibfk_21` FOREIGN KEY (`whatId1`) REFERENCES `what` (`whatId`),
  ADD CONSTRAINT `relationship_ibfk_22` FOREIGN KEY (`whenId1`) REFERENCES `when` (`whenId`),
  ADD CONSTRAINT `relationship_ibfk_23` FOREIGN KEY (`whereId1`) REFERENCES `where` (`whereId`),
  ADD CONSTRAINT `relationship_ibfk_24` FOREIGN KEY (`commentId1`) REFERENCES `comment` (`commentId`),
  ADD CONSTRAINT `relationship_ibfk_25` FOREIGN KEY (`bibleRefId1`) REFERENCES `bibleref` (`bibleRefId`),
  ADD CONSTRAINT `relationship_ibfk_26` FOREIGN KEY (`whoId2`) REFERENCES `who` (`whoId`),
  ADD CONSTRAINT `relationship_ibfk_27` FOREIGN KEY (`groupId2`) REFERENCES `group` (`groupId`),
  ADD CONSTRAINT `relationship_ibfk_28` FOREIGN KEY (`thingId2`) REFERENCES `thing` (`thingId`),
  ADD CONSTRAINT `relationship_ibfk_29` FOREIGN KEY (`whatId2`) REFERENCES `what` (`whatId`),
  ADD CONSTRAINT `relationship_ibfk_30` FOREIGN KEY (`whenId2`) REFERENCES `when` (`whenId`),
  ADD CONSTRAINT `relationship_ibfk_31` FOREIGN KEY (`whereId2`) REFERENCES `where` (`whereId`),
  ADD CONSTRAINT `relationship_ibfk_32` FOREIGN KEY (`commentId2`) REFERENCES `comment` (`commentId`),
  ADD CONSTRAINT `relationship_ibfk_33` FOREIGN KEY (`bibleRefId2`) REFERENCES `bibleref` (`bibleRefId`);

--
-- Constraints for table `what`
--
ALTER TABLE `what`
  ADD CONSTRAINT `what_ibfk_96` FOREIGN KEY (`whatTypeId`) REFERENCES `whattype` (`whatTypeId`);

--
-- Constraints for table `when`
--
ALTER TABLE `when`
  ADD CONSTRAINT `when_ibfk_11` FOREIGN KEY (`bibleMonthId`) REFERENCES `biblemonth` (`bibleMonthId`),
  ADD CONSTRAINT `when_ibfk_12` FOREIGN KEY (`whenBasisId`) REFERENCES `when` (`whenId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

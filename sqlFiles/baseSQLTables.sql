-- MySQL dump 10.13  Distrib 5.1.71, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: rotatingImageAds
-- ------------------------------------------------------
-- Server version	5.1.71-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ImageAds`
--

DROP TABLE IF EXISTS `imageAds`;
CREATE TABLE `imageAds` (
  `ID` tinyint(1) unsigned AUTO_INCREMENT NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `enabled` boolean DEFAULT FALSE, 
  `priority` boolean DEFAULT FALSE, 
  `altText` varchar(200) DEFAULT NULL,
  `actionURL` varchar(200) DEFAULT NULL,
  `imageAd` blob DEFAULT NULL, 
  ## LINK DIPSLAY CONDITIONS IN THIS TABLE 
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `displayConditions`;
CREATE TABLE `displayConditions` (
  `imageAdID` tinyint(1) DEFAULT NULL,
  `ID` tinyint(1) unsigned AUTO_INCREMENT NOT NULL, 
  `dateStart` tinyint(8) DEFAULT NULL,
  `dateEnd` tinyint(8) DEFAULT NULL,
  `weekdays` varchar(100) DEFAULT NULL,
  `timeStart` tinyint(4) DEFAULT NULL,
  `timeEnd` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


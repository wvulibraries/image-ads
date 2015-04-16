## MENDING THE DB A LITTLE BIT
## ============================ 

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
  `timeStart` varchar(7) DEFAULT NULL,
  `timeEnd` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


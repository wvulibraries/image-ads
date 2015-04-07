## MENDING THE DB A LITTLE BIT
## ============================ 

DROP TABLE IF EXISTS `imageAds`;
CREATE TABLE `imageAds` (
  `ID` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
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
  `ID` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `weekdays` varchar(50) DEFAULT NULL,
  `months` varchar(50) DEFAULT NULL,
  `day` tinyint(2) DEFAULT NULL,
  `timeStart` tinyint(4) DEFAULT NULL,
  `year` tinyint(4) DEFAULT NULL
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


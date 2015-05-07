## MENDING THE DB A LITTLE BIT
## ============================

DROP TABLE IF EXISTS `imageAds`;
CREATE TABLE `imageAds` (
  `ID` tinyint(1) unsigned AUTO_INCREMENT NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `imageAd` mediumblob DEFAULT NULL,
  `enabled` boolean DEFAULT FALSE,
  `priority` boolean DEFAULT FALSE,
  `altText` varchar(200) DEFAULT NULL,
  `actionURL` varchar(200) DEFAULT NULL,
  ## LINK DIPSLAY CONDITIONS IN THIS TABLE
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `displayConditions`;
CREATE TABLE `displayConditions` (
  `imageAdID` tinyint(1) DEFAULT NULL,
  `ID` tinyint(1) unsigned AUTO_INCREMENT NOT NULL,
  `dateStart` int unsigned DEFAULT NULL,
  `dateEnd` int unsigned DEFAULT NULL,
  `monday` boolean DEFAULT FALSE,
  `tuesday` boolean DEFAULT FALSE,
  `wednesday` boolean DEFAULT FALSE,
  `thursday` boolean DEFAULT FALSE,
  `friday` boolean DEFAULT FALSE,
  `saturday` boolean DEFAULT FALSE,
  `sunday` boolean DEFAULT FALSE,
  `timeStart` int unsigned DEFAULT NULL,
  `timeEnd` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


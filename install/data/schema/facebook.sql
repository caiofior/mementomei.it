CREATE TABLE `facebook` (
  `userID` varchar(20) NOT NULL,
  `accessToken` text,
  `signedRequest` text,
  `creation_datetime` datetime DEFAULT NULL,
  `last_login_datetime` datetime DEFAULT NULL,
  `expires_datetime` datetime DEFAULT NULL,
  `profile_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  KEY `profile` (`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
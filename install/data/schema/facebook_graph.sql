CREATE TABLE `facebook_graph` (
  `userID` varchar(20) NOT NULL,
  `label` varchar(50) NOT NULL,
  `value` text,
  `last_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`userID`,`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
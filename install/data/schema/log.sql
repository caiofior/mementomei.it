CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `url` text,
  `action` varchar(10) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`),
  KEY `email` (`email`),
  KEY `datetime` (`datetime`),
  KEY `action` (`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
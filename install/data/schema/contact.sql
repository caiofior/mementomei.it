CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `message` text,
  `mail` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL COMMENT 'Contant site',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`) USING BTREE,
  KEY `datetime` (`datetime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8
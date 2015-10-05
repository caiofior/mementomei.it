CREATE TABLE `deceased` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `first_name` varchar(100) DEFAULT NULL COMMENT 'First name',
  `last_name` varchar(100) DEFAULT NULL COMMENT 'Last name',
  `description` varchar(300) DEFAULT NULL COMMENT 'Full description with titles, attributes and nicknames',
  `date_of_birth` date DEFAULT NULL COMMENT 'Date of birth',
  `date_of_death` date DEFAULT NULL COMMENT 'Date of death',
  `epitaph` text COMMENT 'Epitaph',
  `main_place` geometry NOT NULL COMMENT 'Main place',
  `main_photo` varchar(100) DEFAULT NULL COMMENT 'Main photo',
  PRIMARY KEY (`id`),
  KEY `first_name` (`first_name`) COMMENT 'Names search',
  KEY `date_of_birth` (`date_of_birth`),
  KEY `date_of_death` (`date_of_death`),
  KEY `last_name` (`last_name`),
  SPATIAL KEY `place` (`main_place`),
  FULLTEXT KEY `full_text_name` (`description`,`epitaph`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Deceased table'
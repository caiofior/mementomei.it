CREATE TABLE `beloved_agency` (
  `beloved_id` int(11) NOT NULL COMMENT 'Beloved ID',
  `agency_id` int(11) NOT NULL COMMENT 'Agency ID',
  `datetime` datetime NOT NULL COMMENT 'Data e ore',
  PRIMARY KEY (`beloved_id`,`agency_id`),
  KEY `beloved_id` (`beloved_id`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Association between beeloved and agency'
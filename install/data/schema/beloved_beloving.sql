CREATE TABLE `beloved_beloving` (
  `beloved_id` int(11) NOT NULL COMMENT 'Beloved ID',
  `profile_id` int(11) NOT NULL COMMENT 'Beloving ID',
  UNIQUE KEY `unique` (`beloved_id`,`profile_id`),
  KEY `beloved` (`beloved_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Association between beeloved and beloving'
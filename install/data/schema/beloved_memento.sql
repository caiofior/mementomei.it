CREATE TABLE `beloved_memento` (
 `beloved_id` int(11) NOT NULL COMMENT 'Beeloved id',
 `memento_code` varchar(20) NOT NULL COMMENT 'Memento code',
 `user_id` int(11) DEFAULT NULL COMMENT 'User id',
 `agency_id` int(11) DEFAULT NULL COMMENT 'Agency id',
 `datetime` datetime NOT NULL COMMENT 'Date time',
 `data` text NOT NULL COMMENT 'Memento data',
 `filename` varchar(40) NOT NULL COMMENT 'Memento filename',
 PRIMARY KEY (`beloved_id`,`memento_code`,`datetime`),
 KEY `beloved_id` (`beloved_id`),
 KEY `memento_id` (`memento_code`),
 KEY `user_id` (`user_id`),
 KEY `agency_id` (`agency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Association between beeloved and memento';

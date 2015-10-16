CREATE TABLE `agency` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo',
  `type` enum('graveyard','parlour') NOT NULL COMMENT 'Agency type',
  `name` varchar(100) NOT NULL COMMENT 'Name',
  `description` text NOT NULL COMMENT 'Description',
  `point` geometry NOT NULL COMMENT 'Point',
  `cod_istat_n` mediumint(6) NOT NULL COMMENT 'Comune istat code',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Agency, both graveyard and parlouars'
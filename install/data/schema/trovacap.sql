CREATE TABLE `trovacap` (
  `id_cap` int(5) NOT NULL DEFAULT '0',
  `prov_cap` varchar(2) DEFAULT NULL,
  `comu_cap` varchar(42) DEFAULT NULL,
  `com2_cap` varchar(59) DEFAULT NULL,
  `fraz_cap` varchar(38) DEFAULT NULL,
  `fra2_cap` varchar(45) DEFAULT NULL,
  `topo_cap` varchar(44) DEFAULT NULL,
  `top2_cap` varchar(128) DEFAULT NULL,
  `dugt_cap` varchar(34) DEFAULT NULL,
  `nciv_cap` varchar(35) DEFAULT NULL,
  `capi_cap` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_cap`),
  KEY `prov_cap` (`prov_cap`),
  KEY `comu_cap` (`comu_cap`),
  KEY `com2_cap` (`com2_cap`),
  KEY `fraz_cap` (`fraz_cap`),
  KEY `fra2_cap` (`fra2_cap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
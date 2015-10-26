CREATE TABLE `province` (
  `rip_codice` varchar(89) DEFAULT NULL COMMENT 'Codice ripartizione',
  `nuts1_2006` varchar(3) DEFAULT NULL COMMENT 'Codice NUTS1 2006',
  `nuts1_2010` varchar(3) DEFAULT NULL COMMENT 'Codice NUTS1 2010',
  `rip_nome_m` varchar(10) DEFAULT NULL COMMENT 'Ripartizione geografica (Maiuscolo)',
  `rip_nome` varchar(10) DEFAULT NULL COMMENT 'Ripartizione geografica',
  `regione_cod` tinyint(2) DEFAULT NULL COMMENT 'Codice regione',
  `nuts2_2006` varchar(4) DEFAULT NULL COMMENT 'Codice NUTS2 2006 (a)',
  `nuts2_2010` varchar(4) DEFAULT NULL COMMENT 'Codice NUTS2 2010 (a)',
  `regione_nome_m` varchar(21) DEFAULT NULL COMMENT 'Denominazione regione (Maiuscolo)',
  `regione_nome` varchar(21) DEFAULT NULL COMMENT 'Denominazione regione',
  `provincia_cod` tinyint(3) unsigned DEFAULT NULL COMMENT 'Codice provincia',
  `citta_metropolitana_cod` varchar(3) DEFAULT NULL COMMENT 'Codice Città Metropolitana',
  `nuts3_2006` varchar(5) DEFAULT NULL COMMENT 'Codice NUTS3 2006',
  `nuts3_2010` varchar(5) DEFAULT NULL COMMENT 'Codice NUTS3 2010',
  `provincia_nome` varchar(21) DEFAULT NULL COMMENT 'Denominazione provincia',
  `citta_metropolitana_nome` varchar(7) DEFAULT NULL COMMENT 'Denominazione                  Città metropolitana',
  `provincia_sigla` varchar(2) DEFAULT NULL COMMENT 'Sigla automobilistica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
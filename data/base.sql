-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoria` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categorie` (`id`, `categoria`) VALUES
(3,	'abbigliamento'),
(2,	'alimentari'),
(4,	'automezzi'),
(5,	'fabbricati'),
(8,	'giacenza'),
(6,	'istruzione'),
(1,	'stipendi'),
(7,	'viaggi');

DROP TABLE IF EXISTS `movimenti`;
CREATE TABLE `movimenti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `datamovimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `movimento` varchar(255) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `importo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` longtext,
  `straordinaria` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `datamovimento` (`datamovimento`),
  KEY `importo` (`importo`),
  KEY `movimento` (`movimento`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `movimenti_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `movimenti` (`id`, `datamovimento`, `movimento`, `categorie_id`, `importo`, `note`, `straordinaria`) VALUES
(1,	'2017-01-17 12:28:05',	'Giacenza iniziale',	8,	20000.00,	NULL,	NULL),
(2,	'2017-01-17 12:28:28',	'stipendio mese di gennaio',	1,	1500.00,	NULL,	NULL),
(3,	'2017-01-17 12:29:17',	'spesa supermercato',	2,	-120.00,	NULL,	NULL);

-- 2017-01-17 13:06:40

-- --------------------------------------------------------
-- Server Version:               5.6.15 - MySQL Community Server (GPL)
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle calls
CREATE TABLE IF NOT EXISTS `calls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` int(10) unsigned NOT NULL,
  `ct` int(10) DEFAULT NULL,
  `wt` int(10) DEFAULT NULL,
  `cpu` int(10) DEFAULT NULL,
  `mu` int(10) DEFAULT NULL,
  `pmu` int(10) DEFAULT NULL,
  `caller_id` int(10) DEFAULT NULL,
  `callee_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `calls_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle players
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle requests
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_host_id` int(10) unsigned NOT NULL,
  `request_uri_id` int(10) unsigned NOT NULL,
  `request_method_id` int(10) unsigned NOT NULL,
  `request_caller_id` int(10) unsigned DEFAULT NULL,
  `https` tinyint(3) unsigned NOT NULL,
  `request_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `request_host_id` (`request_host_id`),
  KEY `request_method_id` (`request_method_id`),
  KEY `request_timestamp` (`request_timestamp`),
  KEY `request_uri_id` (`request_uri_id`,`request_caller_id`),
  KEY `temporary_request_data` (`request_host_id`,`request_uri_id`,`request_method_id`,`request_caller_id`),
  CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`request_method_id`) REFERENCES `request_methods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `requests_ibfk_5` FOREIGN KEY (`request_uri_id`) REFERENCES `request_uris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle request_data
CREATE TABLE IF NOT EXISTS `request_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(40) NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle request_hosts
CREATE TABLE IF NOT EXISTS `request_hosts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `host` (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle request_methods
CREATE TABLE IF NOT EXISTS `request_methods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `method` (`method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle request_uris
CREATE TABLE IF NOT EXISTS `request_uris` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  KEY `id` (`id`,`uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

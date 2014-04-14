-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2014 at 12:16 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `totalparlay`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `metadata` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `league_id` int(11) unsigned NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Table structure for table `games_teams`
--

CREATE TABLE IF NOT EXISTS `games_teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `team_id` int(11) unsigned NOT NULL,
  `team_type_id` int(2) unsigned NOT NULL,
  `reference` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mteam_match` (`game_id`),
  KEY `fk_mteam_team` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=216 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `game_details`
--
CREATE TABLE IF NOT EXISTS `game_details` (
`id` int(11) unsigned
,`date` date
,`time` time
,`team_type_id` int(2) unsigned
,`ref` varchar(6)
,`name` varchar(50)
,`default_type` int(1)
,`league_id` int(11) unsigned
);
-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passkey` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '123',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `location`, `passkey`) VALUES
(1, 'Principal', 'Caracas', '123'),
(2, 'Otro Grupo', 'Guarico', '123'),
(3, 'Extra', 'Bolivar', '123'),
(4, 'Quinta Crespo', 'Caracas', '123'),
(5, 'Otro Grupo', 'Maturin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

CREATE TABLE IF NOT EXISTS `leagues` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `sport_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_league_sport` (`sport_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`id`, `name`, `enable`, `sport_id`) VALUES
(1, 'MLB', 1, 1),
(2, 'LVBP', 1, 1),
(3, 'NBA', 1, 3),
(4, 'Rep Dominicana', 1, 1),
(5, 'Inglesa', 1, 2),
(6, 'Italiana', 1, 2),
(7, 'NHL', 1, 4),
(8, 'La Rinconada', 1, 6),
(9, 'Santa Rita', 1, 6),
(10, 'Numeros Caballos', 1, 6),
(11, 'NFL', 1, 5),
(12, 'Presidenciales', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `odds`
--

CREATE TABLE IF NOT EXISTS `odds` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `odd_type_id` int(11) unsigned NOT NULL,
  `team_type_id` int(11) unsigned NOT NULL,
  `final` int(1) unsigned NOT NULL DEFAULT '1',
  `odd` int(11) NOT NULL,
  `factor` float(10,2) DEFAULT '0.00',
  `odd_status_id` int(10) unsigned NOT NULL DEFAULT '1',
  `actual` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `until` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_odds_match` (`game_id`),
  KEY `fk_odd_type` (`odd_type_id`),
  KEY `fk_team_type` (`team_type_id`),
  KEY `fk_odd_status` (`odd_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=670 ;

-- --------------------------------------------------------

--
-- Table structure for table `odds_tickets`
--

CREATE TABLE IF NOT EXISTS `odds_tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `odd_id` int(11) unsigned NOT NULL,
  `ticket_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_oddtik_odd` (`odd_id`),
  KEY `fk_oddtik_ticket` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `odd_statuses`
--

CREATE TABLE IF NOT EXISTS `odd_statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `odd_statuses`
--

INSERT INTO `odd_statuses` (`id`, `name`) VALUES
(1, 'Pendiente'),
(2, 'Ganador'),
(3, 'Perdedor'),
(4, 'Devuelto'),
(5, 'Empatado');

-- --------------------------------------------------------

--
-- Table structure for table `odd_types`
--

CREATE TABLE IF NOT EXISTS `odd_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uses_factor` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `odd_types`
--

INSERT INTO `odd_types` (`id`, `name`, `uses_factor`) VALUES
(1, 'Moneyline', 0),
(2, 'Runline', 1),
(3, 'AltaBaja', 1),
(4, 'Empate', 0),
(5, 'Hay Carrera 1er Inn', 0),
(6, 'Quien Anota Primero', 0),
(7, 'Total C H E', 1),
(8, 'Super Runline', 1),
(9, 'AltaBaja Individual', 1);

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

CREATE TABLE IF NOT EXISTS `operations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `operation_type_id` int(11) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `metainf` varchar(255) NOT NULL COMMENT 'mayormente nombre de la tabla o modulo',
  `model_id` int(11) unsigned DEFAULT NULL COMMENT 'campo generico que puede almancenar el id de un ticket o x vaina',
  `metadata` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_oper_ty` (`operation_type_id`),
  KEY `FK_oper_prof` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `operation_types`
--

CREATE TABLE IF NOT EXISTS `operation_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `operation_types`
--

INSERT INTO `operation_types` (`id`, `name`) VALUES
(1, 'LOGIN'),
(2, 'LOGOUT'),
(3, 'CREACION'),
(4, 'EDICION'),
(5, 'ELIMINACION');

-- --------------------------------------------------------

--
-- Table structure for table `pitchers`
--

CREATE TABLE IF NOT EXISTS `pitchers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `team_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pitcher_team` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pitchers`
--

INSERT INTO `pitchers` (`id`, `name`, `team_id`) VALUES
(2, 'O Vegas 2.15 Z 9-3', 2),
(3, 'Pitch Yankees 1', 1),
(4, 'Pitch Yankees 2', 1),
(5, 'O. Vegas', 4),
(6, 'Lanza boston 2', 4),
(7, 'S. Marcano', 17),
(8, 'pitcher Mets', 18);

-- --------------------------------------------------------

--
-- Table structure for table `prefixes`
--

CREATE TABLE IF NOT EXISTS `prefixes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `def` int(10) unsigned NOT NULL,
  `odd_type_id` int(11) unsigned NOT NULL,
  `team_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `final` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `factor` tinyint(1) DEFAULT '0',
  `prefix` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

--
-- Dumping data for table `prefixes`
--

INSERT INTO `prefixes` (`id`, `def`, `odd_type_id`, `team_type_id`, `final`, `factor`, `prefix`) VALUES
(1, 1, 1, 0, 1, 0, '0'),
(2, 1, 1, 0, 0, 0, '5'),
(3, 1, 2, 0, 1, 1, '1'),
(4, 1, 2, 0, 0, 1, '6'),
(5, 2, 1, 0, 1, 0, '1'),
(6, 2, 1, 0, 0, 0, '6'),
(7, 2, 2, 0, 1, 1, '0'),
(8, 2, 2, 0, 0, 1, '5'),
(9, 1, 8, 0, 1, 1, '2'),
(10, 0, 3, 4, 1, 1, 'A'),
(11, 0, 3, 5, 1, 1, 'B'),
(12, 0, 4, 3, 1, 0, 'E'),
(13, 0, 5, 6, 1, 0, 'S'),
(14, 0, 5, 7, 1, 0, 'N'),
(15, 0, 6, 1, 1, 0, 'V'),
(16, 0, 6, 2, 1, 0, 'H'),
(17, 0, 7, 8, 1, 1, 'O'),
(18, 0, 7, 9, 1, 1, 'U'),
(19, 0, 3, 4, 0, 1, 'A5'),
(20, 0, 3, 5, 0, 1, 'B5'),
(21, 0, 4, 3, 0, 0, 'E5'),
(22, 0, 9, 10, 1, 1, 'AV'),
(23, 0, 9, 11, 1, 1, 'BV'),
(24, 0, 9, 12, 1, 1, 'AH'),
(25, 0, 9, 13, 1, 1, 'BH'),
(26, 0, 10, 1, 1, 0, 'V2'),
(27, 0, 10, 2, 1, 0, 'H2'),
(28, 2, 2, 0, 2, 1, '4'),
(29, 0, 3, 4, 2, 1, 'A4'),
(30, 0, 3, 5, 2, 1, 'B4'),
(31, 2, 1, 0, 2, 0, '7'),
(32, 1, 14, 0, 1, 1, '3'),
(33, 0, 12, 24, 1, 0, 'P'),
(34, 0, 12, 25, 1, 0, 'I'),
(35, 0, 13, 1, 1, 0, 'VJ'),
(36, 0, 13, 2, 1, 0, 'HJ'),
(37, 0, 15, 1, 1, 0, 'SV'),
(38, 0, 15, 2, 1, 0, 'SH'),
(39, 0, 16, 1, 1, 0, 'MV'),
(40, 0, 16, 2, 1, 0, 'MH');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `odd_type_id` int(10) unsigned NOT NULL,
  `team_type_id` int(10) unsigned NOT NULL,
  `score` int(6) NOT NULL,
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `final` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_games` (`game_id`),
  KEY `fk_odd_types` (`odd_type_id`),
  KEY `fk_team_types` (`team_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'ADMIN'),
(2, 'SUBADM'),
(3, 'TAQUILLA');

-- --------------------------------------------------------

--
-- Table structure for table `sports`
--

CREATE TABLE IF NOT EXISTS `sports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `get_draw` tinyint(1) NOT NULL DEFAULT '0',
  `default_type` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sports`
--

INSERT INTO `sports` (`id`, `name`, `enable`, `get_draw`, `default_type`) VALUES
(1, 'Baseball', 1, 0, 1),
(2, 'Futbol Soccer', 1, 1, 1),
(3, 'Basketball', 1, 0, 2),
(4, 'Hockey', 1, 0, 1),
(5, 'American Football', 1, 0, 2),
(6, 'Hipismo', 1, 0, 1),
(7, 'Elecciones', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abrev` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alt_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `league_id` int(11) unsigned NOT NULL,
  `win_loses` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_team_league` (`league_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `abrev`, `alt_name`, `league_id`, `win_loses`, `enable`) VALUES
(1, 'New York Yankees', 'NYY', 'YANKEES', 1, '52-33', 1),
(2, 'Leones del Caracas', 'LEO', NULL, 2, '', 1),
(3, 'Chicago Bulls', 'CHI', NULL, 3, '', 1),
(4, 'Boston Red Sox', 'BOS', NULL, 1, '', 1),
(5, 'Navegantes del Magallanes', 'NAV', NULL, 2, '', 1),
(6, 'Los Angeles Lakers', 'LAL', NULL, 3, '', 1),
(7, 'Tigres de Aragua', 'TIG', NULL, 2, '', 1),
(8, 'Tiburones de la Guaira', 'TIB', NULL, 2, '', 1),
(9, 'West Ham', 'WEH', NULL, 5, '', 1),
(10, 'Manchester United', 'MAN', NULL, 5, '', 1),
(11, 'Fiorentina', 'FIO', NULL, 6, '', 1),
(12, 'Milan', 'MIL', NULL, 6, '', 1),
(13, 'Indiana Pacers', 'IND', NULL, 3, '', 1),
(14, 'New York  Knicks', 'NYN', NULL, 3, '', 1),
(15, 'Montreal Canadiens', 'MON', NULL, 7, '', 1),
(16, 'Toronto Maple Leafs', 'TOR', NULL, 7, '', 1),
(17, 'Chicago White Sox', 'CHW', NULL, 1, '', 1),
(18, 'New York Mets', 'NYM', NULL, 1, '', 1),
(19, '01', '1', '1o', 10, '', 1),
(20, '02', '2', '2o', 10, '', 1),
(21, '03', '3', '3o', 10, '', 1),
(22, '04', '4', '4o', 10, '', 0),
(23, '05', '5', '5o', 10, '', 1),
(24, '06', '6', '6o', 10, '', 1),
(25, '07', '7', '7o', 10, '', 1),
(26, '08', '8', '8o', 10, '', 1),
(27, '09', '9', '9o', 10, '', 1),
(28, '10', '10', '10o', 10, '', 1),
(29, 'Otros', 'otr', NULL, 10, '', 1),
(30, 'Hugo Chavez', 'CHA', NULL, 12, '', 1),
(31, 'Henrique Capriles', 'RAD', NULL, 12, '', 1),
(32, 'Tampa Bay Devil Rays', 'TPB', NULL, 1, '', 1),
(33, 'Texas Rangers', 'TXR', NULL, 1, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_types`
--

CREATE TABLE IF NOT EXISTS `team_types` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- Dumping data for table `team_types`
--

INSERT INTO `team_types` (`id`, `name`, `prefix`) VALUES
(1, 'Visitante', ''),
(2, 'HomeClub', ''),
(3, 'Empate', ''),
(4, 'Alta', 'A'),
(5, 'Baja', 'B'),
(6, 'Si', 'S'),
(7, 'No', 'N'),
(8, 'Over', 'O'),
(9, 'Under', 'U'),
(10, 'Alta Visitante', ''),
(11, 'Baja Visitante', ''),
(12, 'Alta HomeClub', ''),
(13, 'Baja HomeClub', '');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `confirm` char(7) DEFAULT NULL,
  `created` datetime NOT NULL,
  `amount` float(10,2) NOT NULL,
  `prize` float(10,2) NOT NULL,
  `bets` int(2) NOT NULL DEFAULT '1',
  `user_id` int(11) unsigned NOT NULL,
  `ticket_status_id` int(10) unsigned NOT NULL DEFAULT '1',
  `payed` tinyint(1) unsigned DEFAULT '0',
  `printed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_ticket_profile` (`user_id`),
  KEY `fk_ticket_status` (`ticket_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=162 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `ticket_details`
--
CREATE TABLE IF NOT EXISTS `ticket_details` (
`ticket_id` int(11) unsigned
,`odd_id` int(11) unsigned
,`default_type` int(1)
,`game_id` int(10) unsigned
,`odd_type_id` int(11) unsigned
,`team_type_id` int(11) unsigned
,`stat` varchar(100)
,`prefix` varchar(3)
,`oth_pref` varchar(3)
,`final` int(1) unsigned
,`uses_factor` tinyint(1) unsigned
,`aref` varchar(6)
,`away` varchar(50)
,`awayab` varchar(4)
,`amet` varchar(255)
,`href` varchar(6)
,`home` varchar(50)
,`homeab` varchar(4)
,`hmet` varchar(255)
,`odd` int(11)
,`factor` float(10,2)
,`type_name` varchar(255)
,`team_name` varchar(20)
);
-- --------------------------------------------------------

--
-- Table structure for table `ticket_statuses`
--

CREATE TABLE IF NOT EXISTS `ticket_statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ticket_statuses`
--

INSERT INTO `ticket_statuses` (`id`, `name`) VALUES
(1, 'PENDIENTE'),
(2, 'GANADOR'),
(3, 'PERDEDOR'),
(4, 'DEVUELTO'),
(5, 'REPORTADO'),
(6, 'ANULADO'),
(7, 'VENCIDO');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) unsigned NOT NULL DEFAULT '3',
  `group_id` int(10) unsigned NOT NULL DEFAULT '1',
  `enable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `min_parlays` int(11) NOT NULL DEFAULT '1',
  `max_parlays` int(11) NOT NULL DEFAULT '0',
  `max_amount_straight` float(10,2) NOT NULL DEFAULT '0.00',
  `max_amount_parlay` float(10,2) NOT NULL DEFAULT '0.00',
  `max_prize` float(10,2) NOT NULL DEFAULT '0.00',
  `pct_sales_str` float(10,2) NOT NULL DEFAULT '0.00',
  `pct_sales_par` float(10,2) NOT NULL DEFAULT '0.00',
  `pct_won` float(10,2) NOT NULL DEFAULT '0.00',
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `balance` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `role_id`, `group_id`, `enable`, `created`, `email`, `password`, `phone_number`, `min_parlays`, `max_parlays`, `max_amount_straight`, `max_amount_parlay`, `max_prize`, `pct_sales_str`, `pct_sales_par`, `pct_won`, `online`, `balance`) VALUES
(1, 'Florencio DeGois', 'florencio', 1, 1, 1, '2014-04-13 04:16:16', '', 'ce0f0bec18767d01438696e77c949635dcfb42d8', NULL, 1, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `ZZusers`
--

CREATE TABLE IF NOT EXISTS `ZZusers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enable` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `ZZusers`
--

INSERT INTO `ZZusers` (`id`, `created`, `username`, `password`, `email`, `enable`) VALUES
(1, '2010-12-01 00:00:00', 'adminuno', 'ce0f0bec18767d01438696e77c949635dcfb42d8', 'mel@hotmail.com', 1),
(2, '2011-01-10 12:34:58', 'florencio', 'ce0f0bec18767d01438696e77c949635dcfb42d8', 'jrudas@gmail.com', 1),
(3, '2011-01-12 16:45:31', 'taqcatia', 'ce0f0bec18767d01438696e77c949635dcfb42d8', 'jrudas@gmail.com', 1),
(4, '2011-01-22 15:10:33', 'taqcentro', 'ce0f0bec18767d01438696e77c949635dcfb42d8', 'jrudas@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ZZ_accounts`
--

CREATE TABLE IF NOT EXISTS `ZZ_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `add` tinyint(1) NOT NULL DEFAULT '0',
  `amount` float(10,2) NOT NULL,
  `metainf` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accoprofs` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `ZZ_profiles`
--

CREATE TABLE IF NOT EXISTS `ZZ_profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `phone_number` varchar(100) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT '1',
  `min_parlays` int(11) NOT NULL DEFAULT '1',
  `max_parlays` int(11) NOT NULL DEFAULT '0',
  `max_amount_straight` float(10,2) NOT NULL DEFAULT '0.00',
  `max_amount_parlay` float(10,2) NOT NULL DEFAULT '0.00',
  `max_prize` float(10,2) NOT NULL DEFAULT '0.00',
  `pct_sales_str` float(10,2) NOT NULL DEFAULT '0.00',
  `pct_sales_par` float(10,2) NOT NULL DEFAULT '0.00',
  `pct_won` float(10,2) NOT NULL DEFAULT '0.00',
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `balance` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_profile_user` (`user_id`),
  KEY `fk_profile_role` (`role_id`),
  KEY `fk_profile_group` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ZZ_profiles`
--

INSERT INTO `ZZ_profiles` (`id`, `name`, `role_id`, `phone_number`, `user_id`, `group_id`, `min_parlays`, `max_parlays`, `max_amount_straight`, `max_amount_parlay`, `max_prize`, `pct_sales_str`, `pct_sales_par`, `pct_won`, `online`, `balance`) VALUES
(1, 'User Admin', 1, '1234567', 1, 1, 1, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00),
(2, 'Florencio DeGois', 1, '544545', 2, 1, 1, 324, 34.00, 43.00, 324.00, 3.00, 0.00, 4.00, 0, 0.00),
(3, 'Taquilla Catia', 3, '8885566', 3, 1, 1, 5, 150.00, 200.00, 10000.00, 2.00, 0.00, 1.00, 0, 0.00),
(4, 'Taquilla Centro', 3, '8885566', 4, 1, 1, 3, 50.00, 80.00, 500.00, 0.00, 0.00, 0.00, 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `ZZ_profile_backs`
--
-- in use(#1356 - View 'totalparlay.ZZ_profile_backs' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)
-- Error reading data: (#1356 - View 'totalparlay.ZZ_profile_backs' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

-- --------------------------------------------------------

--
-- Table structure for table `ZZ_profile_sales`
--
-- in use(#1356 - View 'totalparlay.ZZ_profile_sales' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)
-- Error reading data: (#1356 - View 'totalparlay.ZZ_profile_sales' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

-- --------------------------------------------------------

--
-- Table structure for table `ZZ_profile_wins`
--
-- in use(#1356 - View 'totalparlay.ZZ_profile_wins' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)
-- Error reading data: (#1356 - View 'totalparlay.ZZ_profile_wins' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

-- --------------------------------------------------------

--
-- Stand-in structure for view `ZZ_regular_odds`
--
CREATE TABLE IF NOT EXISTS `ZZ_regular_odds` (
`date` date
,`time` time
,`ref` varchar(6)
,`name` varchar(50)
,`def` int(11) unsigned
,`1` int(11) unsigned
,`5` int(11) unsigned
,`6` int(11) unsigned
,`2` int(11) unsigned
,`3` int(11) unsigned
,`default_type` int(1)
);
-- --------------------------------------------------------

--
-- Structure for view `game_details`
--
DROP TABLE IF EXISTS `game_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `game_details` AS (select `g`.`id` AS `id`,`g`.`date` AS `date`,`g`.`time` AS `time`,`gt`.`team_type_id` AS `team_type_id`,`gt`.`reference` AS `ref`,`t`.`name` AS `name`,`s`.`default_type` AS `default_type`,`g`.`league_id` AS `league_id` from ((((`games` `g` join `leagues` `l` on((`g`.`league_id` = `l`.`id`))) join `sports` `s` on((`l`.`sport_id` = `s`.`id`))) left join `games_teams` `gt` on((`g`.`id` = `gt`.`game_id`))) left join `teams` `t` on((`gt`.`team_id` = `t`.`id`))) where (`g`.`enable` = 1));

-- --------------------------------------------------------

--
-- Structure for view `ticket_details`
--
DROP TABLE IF EXISTS `ticket_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ticket_details` AS (select `ot`.`ticket_id` AS `ticket_id`,`ot`.`odd_id` AS `odd_id`,`s`.`default_type` AS `default_type`,`o`.`game_id` AS `game_id`,`o`.`odd_type_id` AS `odd_type_id`,`o`.`team_type_id` AS `team_type_id`,`os`.`name` AS `stat`,`pre`.`prefix` AS `prefix`,`pr`.`prefix` AS `oth_pref`,`o`.`final` AS `final`,`ty`.`uses_factor` AS `uses_factor`,`aw`.`reference` AS `aref`,`awt`.`name` AS `away`,`awt`.`abrev` AS `awayab`,`aw`.`metadata` AS `amet`,`ho`.`reference` AS `href`,`hot`.`name` AS `home`,`hot`.`abrev` AS `homeab`,`ho`.`metadata` AS `hmet`,`o`.`odd` AS `odd`,`o`.`factor` AS `factor`,`ty`.`name` AS `type_name`,`tt`.`name` AS `team_name` from (((((((((((((`odds_tickets` `ot` join `odds` `o` on((`ot`.`odd_id` = `o`.`id`))) join `games` `g` on((`o`.`game_id` = `g`.`id`))) join `leagues` `l` on((`g`.`league_id` = `l`.`id`))) join `sports` `s` on((`l`.`sport_id` = `s`.`id`))) join `odd_statuses` `os` on((`o`.`odd_status_id` = `os`.`id`))) join `odd_types` `ty` on((`o`.`odd_type_id` = `ty`.`id`))) join `team_types` `tt` on((`o`.`team_type_id` = `tt`.`id`))) left join `prefixes` `pre` on(((`o`.`odd_type_id` = `pre`.`odd_type_id`) and (`o`.`final` = `pre`.`final`) and (`pre`.`def` = `s`.`default_type`)))) left join `prefixes` `pr` on(((`o`.`odd_type_id` = `pr`.`odd_type_id`) and (`o`.`team_type_id` = `pr`.`team_type_id`) and (`o`.`final` = `pr`.`final`)))) join `games_teams` `aw` on(((`o`.`game_id` = `aw`.`game_id`) and (`aw`.`team_type_id` = 1)))) join `teams` `awt` on((`aw`.`team_id` = `awt`.`id`))) join `games_teams` `ho` on(((`o`.`game_id` = `ho`.`game_id`) and (`ho`.`team_type_id` = 2)))) join `teams` `hot` on((`ho`.`team_id` = `hot`.`id`))) order by `ot`.`ticket_id`);

-- --------------------------------------------------------

--
-- Structure for view `ZZ_regular_odds`
--
DROP TABLE IF EXISTS `ZZ_regular_odds`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ZZ_regular_odds` AS (select `g`.`date` AS `date`,`g`.`time` AS `time`,`gt`.`reference` AS `ref`,`t`.`name` AS `name`,`od`.`id` AS `def`,`oo`.`id` AS `1`,`oh`.`id` AS `5`,`ol`.`id` AS `6`,`os`.`id` AS `2`,`oa`.`id` AS `3`,`s`.`default_type` AS `default_type` from ((((((((((`games` `g` join `leagues` `l` on((`g`.`league_id` = `l`.`id`))) join `sports` `s` on((`l`.`sport_id` = `s`.`id`))) left join `games_teams` `gt` on((`g`.`id` = `gt`.`game_id`))) left join `teams` `t` on((`gt`.`team_id` = `t`.`id`))) left join `odds` `od` on(((`g`.`id` = `od`.`game_id`) and (`od`.`odd_type_id` = `s`.`default_type`) and (`od`.`odd_type_id` in (1,2)) and (`od`.`team_type_id` = `gt`.`team_type_id`) and (`od`.`final` = 1) and (`od`.`actual` = 1)))) left join `odds` `oo` on(((`g`.`id` = `oo`.`game_id`) and (`oo`.`odd_type_id` <> `s`.`default_type`) and (`oo`.`odd_type_id` in (1,2)) and (`oo`.`team_type_id` = `gt`.`team_type_id`) and (`oo`.`final` = 1) and (`oo`.`actual` = 1)))) left join `odds` `oh` on(((`g`.`id` = `oh`.`game_id`) and (`oh`.`odd_type_id` = `s`.`default_type`) and (`od`.`odd_type_id` in (1,2)) and (`oh`.`team_type_id` = `gt`.`team_type_id`) and (`oh`.`final` = 0) and (`oh`.`actual` = 1)))) left join `odds` `ol` on(((`g`.`id` = `ol`.`game_id`) and (`ol`.`odd_type_id` <> `s`.`default_type`) and (`ol`.`odd_type_id` in (1,2)) and (`ol`.`team_type_id` = `gt`.`team_type_id`) and (`ol`.`final` = 0) and (`ol`.`actual` = 1)))) left join `odds` `os` on(((`g`.`id` = `os`.`game_id`) and (`os`.`odd_type_id` = 8) and (`os`.`team_type_id` = `gt`.`team_type_id`) and (`os`.`actual` = 1)))) left join `odds` `oa` on(((`g`.`id` = `oa`.`game_id`) and (`oa`.`odd_type_id` = 14) and (`oa`.`team_type_id` = `gt`.`team_type_id`) and (`oa`.`actual` = 1)))) where (`g`.`enable` = 1));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games_teams`
--
ALTER TABLE `games_teams`
  ADD CONSTRAINT `fk_game_team` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mteam_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leagues`
--
ALTER TABLE `leagues`
  ADD CONSTRAINT `fk_league_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `odds`
--
ALTER TABLE `odds`
  ADD CONSTRAINT `fk_game_odd` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_odd_status` FOREIGN KEY (`odd_status_id`) REFERENCES `odd_statuses` (`id`),
  ADD CONSTRAINT `fk_odd_type` FOREIGN KEY (`odd_type_id`) REFERENCES `odd_types` (`id`),
  ADD CONSTRAINT `fk_team_type` FOREIGN KEY (`team_type_id`) REFERENCES `team_types` (`id`);

--
-- Constraints for table `odds_tickets`
--
ALTER TABLE `odds_tickets`
  ADD CONSTRAINT `fk_oddtik_odd` FOREIGN KEY (`odd_id`) REFERENCES `odds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oddtik_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `operations`
--
ALTER TABLE `operations`
  ADD CONSTRAINT `operations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `opers_typs` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_types` (`id`);

--
-- Constraints for table `pitchers`
--
ALTER TABLE `pitchers`
  ADD CONSTRAINT `fk_pitcher_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `fk_games` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_odd_types` FOREIGN KEY (`odd_type_id`) REFERENCES `odd_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_team_types` FOREIGN KEY (`team_type_id`) REFERENCES `team_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `fk_team_league` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_ticket_status` FOREIGN KEY (`ticket_status_id`) REFERENCES `ticket_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

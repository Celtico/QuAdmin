-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-11-2012 a las 05:28:20
-- Versión del servidor: 5.5.9
-- Versión de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bvland`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `QuAdmin`
--

CREATE TABLE IF NOT EXISTS `QuAdmin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_lang` bigint(20) NOT NULL,
  `lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order` int(11) NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'publish',
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `resum` text COLLATE utf8_unicode_ci,
  `text` longtext COLLATE utf8_unicode_ci,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `imatge` text COLLATE utf8_unicode_ci NOT NULL,
  `parametres` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `type_status_date` (`type`,`status`,`date`,`id`),
  KEY `id_parent` (`id_parent`),
  KEY `author` (`id_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=129 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

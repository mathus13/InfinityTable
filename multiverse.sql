--
-- DbNinja v3.2.6 for MySQL
--
-- Dump date: 2016-01-23 01:31:49 (UTC)
-- Server version: 10.0.22-MariaDB-0ubuntu0.15.10.1
-- Database: gb01_testing
--

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `gb01_testing` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `gb01_testing`;

--
-- Structure for table: campaign_md
--
CREATE TABLE `campaign_md` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_id` char(60) DEFAULT NULL,
  `md_name` varchar(50) DEFAULT NULL,
  `md_value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `one_per` (`ref_id`,`md_name`),
  KEY `ref` (`ref_id`),
  FULLTEXT KEY `search` (`md_name`,`md_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: campaign_users
--
CREATE TABLE `campaign_users` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure for table: campaigns
--
CREATE TABLE `campaigns` (
  `id` char(60) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` char(60) NOT NULL,
  `owner` char(60) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `owner` (`owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure for table: character_data
--
CREATE TABLE `character_data` (
  `id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `md_name` varchar(255) NOT NULL,
  `md_value` text NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure for table: characters
--
CREATE TABLE `characters` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `closed` int(1) NOT NULL,
  `closed_date` datetime NOT NULL,
  `note` text NOT NULL,
  `bio` text NOT NULL,
  `image` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure for table: group_users
--
CREATE TABLE `group_users` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure for table: groups
--
CREATE TABLE `groups` (
  `id` int(100) NOT NULL,
  `name` varchar(120) NOT NULL,
  `moderator` int(11) NOT NULL,
  `members` text NOT NULL,
  `description` text NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure for table: links
--
CREATE TABLE `links` (
  `id` char(50) NOT NULL DEFAULT '',
  `to` varchar(50) DEFAULT NULL,
  `to_id` varchar(50) DEFAULT NULL,
  `from` varchar(50) DEFAULT NULL,
  `from_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `to` (`to`,`to_id`),
  KEY `from` (`from`,`from_id`),
  KEY `to_by_type` (`to`,`to_id`,`from`),
  KEY `from_by_type` (`to`,`from`,`from_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: oauth_access_tokens
--
CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: oauth_authorization_codes
--
CREATE TABLE `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: oauth_clients
--
CREATE TABLE `oauth_clients` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) NOT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(100) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: oauth_jwt
--
CREATE TABLE `oauth_jwt` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: oauth_refresh_tokens
--
CREATE TABLE `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: oauth_scopes
--
CREATE TABLE `oauth_scopes` (
  `scope` text,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: user_md
--
CREATE TABLE `user_md` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_id` char(60) DEFAULT NULL,
  `md_name` varchar(50) DEFAULT NULL,
  `md_value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `one_per` (`ref_id`,`md_name`),
  KEY `ref` (`ref_id`),
  FULLTEXT KEY `search` (`md_name`,`md_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: users
--
CREATE TABLE `users` (
  `id` char(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activated` int(1) NOT NULL,
  `banned` int(1) NOT NULL,
  `ban_reason` varchar(255) NOT NULL,
  `last_ip` varchar(40) NOT NULL,
  `last_login` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `active` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

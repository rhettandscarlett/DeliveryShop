/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `file` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `size` int(11) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `file_type` varchar(5) NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `hash` varchar(40) NOT NULL DEFAULT '',
  `status` tinyint(3) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  `created_user_id` int(11) unsigned DEFAULT NULL,
  `updated_user_id` int(11) unsigned DEFAULT NULL,
  `deleted_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `file_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  `created_user_id` int(11) unsigned DEFAULT NULL,
  `updated_user_id` int(11) unsigned DEFAULT NULL,
  `deleted_user_id` int(11) unsigned DEFAULT NULL,
  `order` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `file_category_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned DEFAULT NULL,
  `file_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `file_category_file_category` (`category_id`),
  KEY `file_category_file` (`file_id`),
  CONSTRAINT `file_category_file` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
  CONSTRAINT `file_category_file_category` FOREIGN KEY (`category_id`) REFERENCES `file_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `file_model` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` bigint(20) unsigned NOT NULL,
  `model` varchar(255) NOT NULL DEFAULT '',
  `model_id` int(11) unsigned DEFAULT NULL,
  `category_code` varchar(60) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `description` text,
  `order` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `multilanguage_file_model` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_model_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar (3) NOT NULL,
  `file_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255),
  `description` text,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  `created_user_id` int(11) unsigned DEFAULT NULL,
  `updated_user_id` int(11) unsigned DEFAULT NULL,
  `deleted_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `file_model_id` (`file_model_id`),
  KEY `lang_code` (`lang_code`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `multilanguage_file_model_ibfk_1` FOREIGN KEY (`file_model_id`) REFERENCES `file_model` (`id`),
  CONSTRAINT `multilanguage_file_model_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

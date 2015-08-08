SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `deli_default_setting`;
CREATE TABLE `deli_default_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_schedule`;
CREATE TABLE `deli_schedule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `activated` BOOLEAN DEFAULT 1,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_location`;
CREATE TABLE `deli_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) unsigned,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_to_schedule` (`schedule_id`),
  CONSTRAINT `location_to_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `deli_schedule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_default_location_procedure`;
CREATE TABLE `deli_default_location_procedure` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` TEXT DEFAULT '',
  `order` int(11),
  `duration` FLOAT,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `procedure_to_location` (`location_id`),
  CONSTRAINT `procedure_to_location` FOREIGN KEY (`location_id`) REFERENCES `deli_location` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_billing`;
CREATE TABLE `deli_billing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `default_location_procedure_id` int(11) unsigned NOT NULL,
  `bill_code` varchar(255) NOT NULL DEFAULT '',
  `bill_name` varchar(255) NOT NULL DEFAULT '',
  `description` TEXT DEFAULT '',
  `order` int(11),
  `status` varchar(255) DEFAULT '',
  `init_time` datetime DEFAULT NULL COMMENT 'shipment picked up',
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `billing_to_default_location_procedure` (`default_location_procedure_id`),
  CONSTRAINT `billing_to_default_location_procedure` FOREIGN KEY (`default_location_procedure_id`) REFERENCES `deli_default_location_procedure` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_billing_product`;
CREATE TABLE `deli_billing_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `billing_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `order` int(11),
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `billing_product_to_billing` (`billing_id`),
  CONSTRAINT `billing_product_to_billing` FOREIGN KEY (`billing_id`) REFERENCES `deli_billing` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_billing_runtime_procedure`;
CREATE TABLE `deli_billing_runtime_procedure` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `billing_id` int(11) unsigned,
  `default_location_procedure_id` int(11) unsigned,
  `duration` FLOAT,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `runtime_procedure_to_billing` (`billing_id`),
  CONSTRAINT `runtime_procedure_to_billing` FOREIGN KEY (`billing_id`) REFERENCES `deli_billing` (`id`),
  KEY `runtime_procedure_to_default_location_procedure` (`default_location_procedure_id`),
  CONSTRAINT `runtime_procedure_to_default_location_procedure` FOREIGN KEY (`default_location_procedure_id`) REFERENCES `deli_default_location_procedure` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;








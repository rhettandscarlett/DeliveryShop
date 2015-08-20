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
  `timezone` varchar(255) NOT NULL DEFAULT '',
  `order` int(11),
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
  `visible` tinyint(1) DEFAULT '1',
  `description` text,
  `order` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `plus_day` int(11) DEFAULT '0',
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
  `time` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `runtime_procedure_to_billing` (`billing_id`),
  CONSTRAINT `runtime_procedure_to_billing` FOREIGN KEY (`billing_id`) REFERENCES `deli_billing` (`id`),
  KEY `runtime_procedure_to_default_location_procedure` (`default_location_procedure_id`),
  CONSTRAINT `runtime_procedure_to_default_location_procedure` FOREIGN KEY (`default_location_procedure_id`) REFERENCES `deli_default_location_procedure` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `deli_page`;
CREATE TABLE `deli_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` BLOB,
  `order` int(11),
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table `deli_billing` add `estimate_date` int(5) after `init_time`;
alter table `deli_default_location_procedure` add visible boolean default 1 after `name`;
alter table `deli_default_location_procedure` add plus_day int (11) default 0 after `time`;


# saturday, 15/08/2015
DROP TABLE IF EXISTS `deli_runtime_procedure`;
CREATE TABLE `deli_runtime_procedure` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `order` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `plus_day` int(11) DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `runtime_procedure_to_location` (`location_id`),
  CONSTRAINT `runtime_procedure_to_location` FOREIGN KEY (`location_id`) REFERENCES `deli_location` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table `deli_billing` modify column `bill_name` varchar(255) default '';

DROP TABLE IF EXISTS `deli_billing_runtime_location`;
CREATE TABLE `deli_billing_runtime_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `billing_id` int(11) unsigned NOT NULL,
  `location_id` int(11) unsigned,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `runtime_location_to_billing` (`billing_id`),
  CONSTRAINT `runtime_location_to_billing` FOREIGN KEY (`billing_id`) REFERENCES `deli_billing` (`id`),
  KEY `runtime_location_to_location` (`location_id`),
  CONSTRAINT `runtime_location_to_location` FOREIGN KEY (`location_id`) REFERENCES `deli_location` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table deli_location add `is_default` boolean default 1 after `schedule_id`;

# sunday, 16/08/2015
alter table `deli_runtime_procedure` add visible boolean default 0 after `name`;

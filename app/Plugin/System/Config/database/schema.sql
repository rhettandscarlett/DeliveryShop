CREATE TABLE `system_database_update` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(255) DEFAULT NULL,
  `updated_script` varchar(255) NOT NULL DEFAULT '',
  `start_updated_time` datetime NOT NULL,
  `end_updated_time` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `log` text,
  PRIMARY KEY (`id`),
  KEY `plugin` (`plugin`),
  KEY `updated_script` (`updated_script`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


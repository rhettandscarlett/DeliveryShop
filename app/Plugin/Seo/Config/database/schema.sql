CREATE TABLE `seo_route` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT '',
  `action` varchar(255) DEFAULT '',
  `params` text,
  `slug` text,
  `meta_data` text,
  PRIMARY KEY (`id`),
  KEY `plugin` (`plugin`),
  KEY `controller` (`controller`),
  KEY `action` (`action`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

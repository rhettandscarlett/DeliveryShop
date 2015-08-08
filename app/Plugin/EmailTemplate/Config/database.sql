
CREATE TABLE `email_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `template_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_template_template_type` (`type`),
  KEY `email_template_template_key` (`template_key`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `email_template` VALUES (1,'user_forgot_password','account_type','SF- Forgot Password Instruction','Hello <strong>[:account_name]</strong>!<br /><br />\r\nYou have just requested forgot password. Please click link below to change your password <br/> <a href=\"[:reset_url]\">Reset Password</a><br /><br />\r\nBest Regards,<br />\r\nSF Team','2014-02-27 16:18:23','2014-03-03 10:09:32',NULL),(2,'user_registration_active','account_type','SF- Active Account','Hello <strong>[:account_name]</strong>!<br /><br />\r\nYou have just requested registration at SF. <br />Please click link below to active <br/> <a href=\"[:active_url]\">Active Account</a><br /><br />\r\n\r\nBest Regards,<br />\r\nSF Team','2014-02-27 16:18:23','2014-03-14 14:51:53',NULL),(3,'user_registration_welcome','account_type','SF- Welcome','Hello <strong>[:account_name]</strong>!<br /><br />\r\nYou have just registered successfully an account. Please click link below to login <br/> <a href=\"[:login_url]\">Login</a><br /><br />\r\nBest Regards,<br />\r\nSF Team','2014-02-27 16:18:23','2014-03-14 15:04:25',NULL);
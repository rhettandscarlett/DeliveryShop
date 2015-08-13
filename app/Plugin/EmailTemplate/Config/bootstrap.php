<?php
define('EMAIL_FROM', 'test@example.com');
define('EMAIL_FROM_NAME', 'SF');

define('EMAIL_TEMPLATE', 0);
Configure::write('EMAIL_TEMPLATE_REQUIRE_FIELDS', array(
  'user_forgot_password' => array('[:account_name]', '[:reset_url]'),
  'user_registration_active' => array('[:account_name]', '[:active_url]'),
  'user_registration_welcome' => array('[:account_name]', '[:login_url]'),
));

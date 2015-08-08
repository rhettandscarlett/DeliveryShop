<?php
Configure::write('SystemDatabase.NonBackupTable', array(
  'user',
  'user_role',
  'user_account',
  'user_admin',
  'user_data_access',
  'user_data_access_detail',
  'user_login_history',
  'user_role_access',
  'user_role_right',
  'logs',
  'logs_tmp',
  'request_log',
  'request_log_tmp',
  )
);
//if mysql and mysqldump command already add to system path => set to ""
//define("MYSQL_BIN_PATH", "C:/xampp/mysql/bin/");
define("MYSQL_BIN_PATH", "");

define("SYSTEM_DB_STATUS_RUNNING", 0);
define("SYSTEM_DB_STATUS_FAILR", 1);
define("SYSTEM_DB_STATUS_SUCCESS", 2);


//set not include model
Configure::write('MultiLangDatabase.NotIncludeModel', array(
  'AppModel',
  )
);
?>
<?php
/**
 * Date: 8/28/14
 * Time: 1:18 PM
 */

class DATABASE_CONFIG {

  public $default = array(
    'datasource' => 'Database/Mysql',
    'unix_socket' => '/tmp/mysql.sock',
    'persistent' => false,
    'host' => 'localhost',
    'login' => 'root',
    'password' => 'root',
    'database' => 'delishop',
    'prefix' => '',
    'encoding' => 'utf8',
  );

  public $test = array(
    'datasource' => 'Database/Mysql',
    'persistent' => false,
    'host' => 'localhost',
    'login' => 'user',
    'password' => 'password',
    'database' => 'test_database_name',
    'prefix' => '',
    //'encoding' => 'utf8',
  );
}
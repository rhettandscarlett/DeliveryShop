<?php

class MultilanguageModelFixture extends CakeTestFixture {

  //public $useDbConfig = 'other_test'; //if load fixtures to a different test datasource

  var $name = 'MultilanguageModel';
  var $table = 'multilanguage_model';

  public $fields = array(
    'id' => array('type' => 'integer', 'key' => 'primary'),
    'object_id' => array(
      'type' => 'integer',
      'null' => false
    ),
    'lang_code' => array(
      'type' => 'string',
      'length' => 3,
      'null' => false
    ),
    'name' => 'text'
  );
  public $records = array(
    array(
      'id' => 1,
      'object_id' => 1,
      'lang_code' => 'eng',
      'name' => 'Name - eng',
    ),
    array(
      'id' => 2,
      'object_id' => 1,
      'lang_code' => 'fra',
      'name' => 'Name - fra',
    ),
    array(
      'id' => 3,
      'object_id' => 1,
      'lang_code' => 'deu',
      'name' => 'Name - deu',
    ),
    array(
      'id' => 4,
      'object_id' => 2,
      'lang_code' => 'eng',
      'name' => 'Name 2 - eng',
    ),
  );

  public function init() {
    parent::init();
  }

  public function create($db) {
    parent::create($db);
    return '
      CREATE TABLE `multilanguage_model` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `object_id` int(11) unsigned NOT NULL,
        `lang_code` varchar(5) NOT NULL,
        `name` text,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8';
  }
}

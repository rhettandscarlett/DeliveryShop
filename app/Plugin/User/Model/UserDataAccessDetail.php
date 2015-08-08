<?php

App::uses('AppModel', 'Model');

class UserDataAccessDetail extends AppModel {

  var $useTable = 'user_data_access_detail';
  var $multiLanguage = null;

  public $belongsTo = array (
    'UserDataAccess' => 
    array (
      'className' => 'User.UserDataAccess',
      'foreignKey' => 'data_access_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'data_access_id' => 
    array (
      'notNull' => 
      array (
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'This field cannot be left blank',
      ),
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
      ),
      'unique_data_access_id' => 
      array (
        'rule' => 
        array (
          0 => 'checkUnique',
          1 => 
          array (
            0 => 'data_access_id',
            1 => 'data_id',
          ),
        ),
        'message' => 'Data Access Id, Data Id already exists',
      ),
    ),
    'data_id' => 
    array (
      'notNull' => 
      array (
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'This field cannot be left blank',
      ),
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
      ),
    ),
  );
}
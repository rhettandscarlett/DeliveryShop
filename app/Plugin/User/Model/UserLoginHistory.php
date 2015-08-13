<?php

App::uses('AppModel', 'Model');

class UserLoginHistory extends AppModel {

  var $useTable = 'user_login_history';
  var $multiLanguage = null;

  public $belongsTo = array (
    'UserAccount' => 
    array (
      'className' => 'User.UserAccount',
      'foreignKey' => 'account_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'account_id' => 
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
    'ip' => 
    array (
      'size' => 
      array (
        'rule' => 
        array (
          0 => 'maxLength',
          1 => 40,
        ),
        'message' => 'Please enter a text no larger than 40 characters long',
        'allowEmpty' => true,
      ),
    ),
  );
}
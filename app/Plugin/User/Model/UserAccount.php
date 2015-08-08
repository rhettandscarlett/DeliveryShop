<?php

App::uses('AppModel', 'Model');

class UserAccount extends AppModel {

  var $useTable = 'user_account';
  var $multiLanguage = null;
  public $belongsTo = array(
    'UserModel' =>
    array(
      'className' => 'User.UserModel',
      'foreignKey' => 'user_id',
    ),
  );
  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array(
    'password' =>
    array(
      'size' =>
      array(
        'rule' =>
        array(
          0 => 'maxLength',
          1 => 40,
        ),
        'message' => 'Please enter a text no larger than 40 characters long',
        'allowEmpty' => true,
      ),
    ),
    'password_hint' =>
    array(
      'size' =>
      array(
        'rule' =>
        array(
          0 => 'maxLength',
          1 => 255,
        ),
        'message' => 'Please enter a text no larger than 255 characters long',
        'allowEmpty' => true,
      ),
    ),
  );
}

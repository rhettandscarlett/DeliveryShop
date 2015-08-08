<?php

App::uses('AppModel', 'Model');

class UserRoleAccess extends AppModel {

  var $useTable = 'user_role_access';
  var $multiLanguage = null;
  public $belongsTo = array(
      'UserModel' =>
      array(
          'className' => 'User.UserModel',
          'foreignKey' => 'user_id',
      ),
      'UserRole' =>
      array(
          'className' => 'User.UserRole',
          'foreignKey' => 'role_id',
      ),
  );
  public $actsAs = array('MultiLanguage.MultiLanguage');

}

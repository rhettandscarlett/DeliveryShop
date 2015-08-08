<?php

App::uses('AppModel', 'Model');

class UserRole extends AppModel {

  var $useTable = 'user_role';
  var $multiLanguage = null;
  public $actsAs = array('MultiLanguage.MultiLanguage');
  public $hasMany = array(
      'UserRoleRight' => array(
          'className' => 'User.UserRoleRight',
          'foreignKey' => 'role_id'
      )
  );
  var $validate = array(
      'name' =>
      array(
          'notNull' =>
          array(
              'rule' => 'notEmpty',
              'required' => true,
              'message' => 'This field cannot be left blank',
          ),
          'size' =>
          array(
              'rule' =>
              array(
                  0 => 'maxLength',
                  1 => 255,
              ),
              'message' => 'Please enter a text no larger than 255 characters long',
          ),
      ),
      'description' =>
      array(
          'notNull' =>
          array(
              'rule' => 'notEmpty',
              'required' => true,
              'message' => 'This field cannot be left blank',
          ),
      ),
  );

}

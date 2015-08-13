<?php

App::uses('AppModel', 'Model');

class UserDataAccess extends AppModel {

  var $useTable = 'user_data_access';
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
      'model' =>
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
      'type' =>
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

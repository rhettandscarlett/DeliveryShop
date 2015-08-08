<?php

App::uses('AppModel', 'Model');

class UserAdmin extends AppModel {

  var $useTable = 'user_admin';
  var $multiLanguage = null;
  public $actsAs = array('MultiLanguage.MultiLanguage');
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
    'email' =>
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
      'email' =>
      array(
        'rule' =>
        array(
          0 => 'email',
        ),
        'message' => 'Please enter a valid email address',
      ),
      'unique_email' =>
      array(
        'rule' =>
        array(
          0 => 'checkUnique',
          1 =>
          array(
            0 => 'email',
          ),
        ),
        'message' => 'Email already exists',
      ),
    ),
    'status' =>
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

<?php

App::uses('AppModel', 'Model');

class DeliBilling extends AppModel {

  var $useTable = 'deli_billing';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliDefaultLocationProcedure' => 
    array (
      'className' => 'DeliDefaultLocationProcedure',
      'foreignKey' => 'default_location_procedure_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'default_location_procedure_id' => 
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
    'bill_code' => 
    array (
      'notNull' => 
      array (
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'This field cannot be left blank',
      ),
      'size' => 
      array (
        'rule' => 
        array (
          0 => 'maxLength',
          1 => 255,
        ),
        'message' => 'Please enter a text no larger than 255 characters long',
      ),
    ),
    'bill_name' => 
    array (
      'notNull' => 
      array (
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'This field cannot be left blank',
      ),
      'size' => 
      array (
        'rule' => 
        array (
          0 => 'maxLength',
          1 => 255,
        ),
        'message' => 'Please enter a text no larger than 255 characters long',
      ),
    ),
    'order' => 
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
      ),
    ),
    'status' => 
    array (
      'size' => 
      array (
        'rule' => 
        array (
          0 => 'maxLength',
          1 => 255,
        ),
        'message' => 'Please enter a text no larger than 255 characters long',
        'allowEmpty' => true,
      ),
    ),
    'init_time' => 
    array (
      'datetime' => 
      array (
        'rule' => 
        array (
          0 => 'datetime',
          1 => NULL,
        ),
        'message' => 'Please enter a valid date format',
        'allowEmpty' => true,
      ),
    ),
  );
}
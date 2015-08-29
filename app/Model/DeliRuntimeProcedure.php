<?php

App::uses('AppModel', 'Model');

class DeliRuntimeProcedure extends AppModel {

  var $useTable = 'deli_runtime_procedure';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliLocation' => 
    array (
      'className' => 'DeliLocation',
      'foreignKey' => 'location_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'location_id' => 
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
    'name' => 
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

  );
}
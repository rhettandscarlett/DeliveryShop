<?php

App::uses('AppModel', 'Model');

class DeliBillingRuntimeLocation extends AppModel {

  var $useTable = 'deli_billing_runtime_location';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliBilling' => 
    array (
      'className' => 'DeliBilling',
      'foreignKey' => 'billing_id',
    ),
    'DeliLocation' => 
    array (
      'className' => 'DeliLocation',
      'foreignKey' => 'location_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'billing_id' => 
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
    'location_id' => 
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
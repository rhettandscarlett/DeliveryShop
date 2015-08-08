<?php

App::uses('AppModel', 'Model');

class DeliBillingRuntimeProcedure extends AppModel {

  var $useTable = 'deli_default_location_procedure';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliBilling' =>
      array (
        'className' => 'DeliBilling',
        'foreignKey' => 'billing_id',
      ),
    'DeliDefaultLocationProcedure' =>
      array (
        'className' => 'DeliDefaultLocationProcedure',
        'foreignKey' => 'default_location_procedure_id',
      ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'billing_id' =>
      array (
        'numeric' =>
          array (
            'rule' => 'numeric',
            'message' => 'Please enter a valid number',
          ),
      ),
    'default_location_procedure_id' =>
      array (
        'numeric' =>
          array (
            'rule' => 'numeric',
            'message' => 'Please enter a valid number',
          ),
      ),
    'duration' =>
      array (
        'decimal' =>
          array (
            'rule' => 'decimal',
            'message' => 'Please enter a valid number',
            'allowEmpty' => true,
          ),
      ),
  );

} 
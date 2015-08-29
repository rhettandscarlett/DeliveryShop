<?php

App::uses('AppModel', 'Model');

class DeliBillingRuntimeProcedure extends AppModel {

  var $useTable = 'deli_billing_runtime_procedure';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliBilling' =>
      array (
        'className' => 'DeliBilling',
        'foreignKey' => 'billing_id',
      ),
    'DeliRuntimeProcedure' =>
      array (
        'className' => 'DeliRuntimeProcedure',
        'foreignKey' => 'runtime_procedure_id',
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
    'runtime_procedure_id' =>
      array (
        'numeric' =>
          array (
            'rule' => 'numeric',
            'message' => 'Please enter a valid number',
          ),
      ),
  );

} 
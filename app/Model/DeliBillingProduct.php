<?php

App::uses('AppModel', 'Model');

class DeliBillingProduct extends AppModel {

  var $useTable = 'deli_billing_product';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliBilling' => 
    array (
      'className' => 'DeliBilling',
      'foreignKey' => 'billing_id',
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
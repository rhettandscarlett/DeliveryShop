<?php

App::uses('AppModel', 'Model');

class DeliLocation extends AppModel {

  var $useTable = 'deli_location';
  var $multiLanguage = null;

  public $belongsTo = array (
    'DeliSchedule' => 
    array (
      'className' => 'DeliSchedule',
      'foreignKey' => 'schedule_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'schedule_id' => 
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
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
  );

  public function find($type = 'first', $query = array()) {
    if ($type != 'list') {
      return parent::find($type, $query);
    } else {
      $type = 'all';
      $data = parent::find($type, $query);
      $returnArr = array();
      foreach ($data as $singleData) {
        $returnArr[$singleData['DeliLocation']['id']] = $singleData['DeliSchedule']['name'] . ' >>>> ' . $singleData['DeliLocation']['name'];
      }
      return $returnArr;
    }



  }

}
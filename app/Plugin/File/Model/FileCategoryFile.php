<?php

App::uses('AppModel', 'Model');

class FileCategoryFile extends AppModel {

  var $useTable = 'file_category_file';
  var $multiLanguage = null;

  public $belongsTo = array (
    'File' => 
    array (
      'className' => 'File.File',
      'foreignKey' => 'file_id',
    ),
    'FileCategory' => 
    array (
      'className' => 'File.FileCategory',
      'foreignKey' => 'category_id',
    ),
  );

  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array (
    'category_id' => 
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
      ),
    ),
    'file_id' => 
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
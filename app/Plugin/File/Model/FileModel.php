<?php

App::uses('AppModel', 'Model');
App::uses('Model', 'File.MultilanguageFileModel');

class FileModel extends AppModel {

  var $useTable = 'file_model';
  var $multiLanguage = null;

  public $belongsTo = array(
    'File' => array(
      'className' => 'File.File',
    ));

  public $actsAs = array('MultiLanguage.MultiLanguage');

  public function beforeDelete($cascade = TRUE) {
    $file_model_multi_lang = new MultilanguageFileModel();
    $file_model_multi_lang->deleteAll(array('MultilanguageFileModel.file_model_id' => $this->id));

    return TRUE;
  }
}
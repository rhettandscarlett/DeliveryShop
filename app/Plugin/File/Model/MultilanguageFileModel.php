<?php
App::uses('AppModel', 'Model');

class MultilanguageFileModel extends AppModel {

  var $useTable = 'multilanguage_file_model';


  public function getLanguageFileModels($file_model_id){
    $language_records = $this->find('all', array(
      'joins' => array(
        array(
          'table' => 'file',
          'alias' => 'File',
          'type' => 'INNER',
          'conditions' => array(
            'File.id = MultilanguageFileModel.file_id'
          )
        ),
      ),
      'conditions' => array(
        'MultilanguageFileModel.file_model_id' => $file_model_id,
      ),
      'fields' => array('MultilanguageFileModel.*', 'File.*'),
    ));

    $file_langs = array();
    foreach($language_records as $language_record){
      $file_langs[$language_record['MultilanguageFileModel']['lang_code']] = $language_record;
    }

    return $file_langs;
  }
}
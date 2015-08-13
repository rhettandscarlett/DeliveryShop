<?php

App::uses('AppModel', 'Model');
App::uses('File', 'File.Model');
App::uses('FileCategoryFile', 'File.Model');

class FileCategory extends AppModel {

  var $useTable = 'file_category';
  var $multiLanguage = null;

  public $belongsTo = array(
    'FileCategoryParent' => array(
      'className' => 'File.FileCategory',
      'foreignKey' => 'parent_id'
  ));

  public $actsAs = array('SFTree', 'MultiLanguage.MultiLanguage');
  var $validate = array (
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

      'file_category_name_parent_unique' =>
        array(
            'rule'    =>
                array(
                  0 => 'checkUnique',
                  1 => array(
                    0 => 'name',
                    1 => 'parent_id',
                  ),
              ),
            'message' => 'Name in this folder already exists',
        )
     ),
    'parent_id' =>
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
      ),
    ),
    'created_user_id' => 
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
      ),
    ),
    'updated_user_id' => 
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
      ),
    ),
    'deleted_user_id' => 
    array (
      'numeric' => 
      array (
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
        'allowEmpty' => true,
      ),
    ),
  );

  public function deleteFolder($listFolder) {
    $fileModel = new File();
    $fileCategoryFileModel = new FileCategoryFile();

    $allFolder = $this->find('list', array('fields' => array('id', 'parent_id')));
    $deletings = array();
    foreach($listFolder as $folderId){
      array_push($deletings, $folderId);
      $this->_getDeleteFolder($allFolder, $folderId, $deletings);
    }
    if (!empty($deletings)) {
      $allFileCategoryFile = $fileCategoryFileModel->findAllByCategoryId($deletings);
      if (!empty($allFileCategoryFile)) {
        foreach ($allFileCategoryFile as $fileCategoryFile) {
          $fileModel->deleteFile($fileCategoryFile);
        }
      }
      $this->deleteLogic(null, array('FileCategory.id' => $deletings));
    }
  }

  private function _getDeleteFolder($allFolder = array(), $folderId, &$deletings){
    if (!in_array($folderId, $allFolder)) return;
    foreach($allFolder as $subFolderId => $parentFolderId) {
      if ($parentFolderId == $folderId) {
        array_push($deletings, $subFolderId);
        $this->_getDeleteFolder($allFolder, $subFolderId, $deletings);
      }
    }

  }

}
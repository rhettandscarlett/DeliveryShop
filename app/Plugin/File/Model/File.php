<?php

App::uses('AppModel', 'Model');
App::import("Model", "File.FileModel");
App::import("Model", "File.MultilanguageFileModel");
App::import("Model", "File.FileCategoryFile");

class File extends AppModel {

  var $useTable = 'file';
  var $multiLanguage = null;

  public $hasMany = array(
    'FileModel' => array(
      'className' => 'File.FileModel',
    )
  );

  public $actsAs = array(
    'MultiLanguage.MultiLanguage',
    'SFTree'
  );

  private function _deleteDependants($fileId) {
    $file_model            = new FileModel();
    $file_model_multi_lang = new MultilanguageFileModel();
    $file_category_file    = new FileCategoryFile();

    $allFileModel        = $file_model->find('list', array('conditions' => array('FileModel.file_id' => $fileId)));
    $allMultilangFile    = $file_model_multi_lang->find('list', array('conditions' => array('MultilanguageFileModel.file_id' => $fileId)));
    $allFileCategoryFile = $file_category_file->find('list', array('conditions' => array('FileCategoryFile.file_id' => $fileId)));

    if (!empty($allFileModel)) {
      foreach ($allFileModel as $one) {
        $file_model->delete($one, false, false);
      }
    }

    if (!empty($allMultilangFile)) {
      foreach ($allMultilangFile as $one) {
        $file_model_multi_lang->delete($one, false, false);
      }
    }

    if (!empty($allFileCategoryFile)) {
      foreach ($allFileCategoryFile as $one) {
        $file_category_file->delete($one, false, false);
      }
    }
  }

  /* ================================================
    Function description
    Retrieve files table and get maximum id.
    Parameters:
    Returns:
    number > 0: have record
    zero: have no record
    ================================================== */
  public function getMaxId() {
    $max_id_record = $this->find('first', array(
      'fields' => array('MAX(File.id) AS max_id'),
    ));

    return (empty($max_id_record) ? 0 : $max_id_record[0]['max_id']);
  }

  public function saveUploadFile($parameters, $folderId) {
    $file_path = $parameters['file_path'];
    $file_name = $parameters['file_name'];

    if (empty($file_path) || !is_file($file_path)) {
      return NULL;
    }

    $file_info = FileLib::moveFileToResourceFolder($file_path);

    if (empty($file_info['abs_path']) || empty($file_info['relative_path'])) {
      return NULL;
    }

    $path_info = pathinfo($file_info['abs_path']);

    if (FileLib::isImage($path_info['extension'])) {
      FileLib::generateThumbnail($file_info['relative_path']);
    }

    $new_file = array(
      'name'        => $parameters['name'] . '',
      'filename'    => $file_name,
      'file_type'   => $path_info['extension'],
      'size'        => filesize($file_info['abs_path']),
      'path'        => $file_info['relative_path'],
      'hash'        => FileLib::getHash($file_info['abs_path']),
      'status'      => FileLib::STATUS_READY,
      'description' => $parameters['desc'],
    );

    if (isset($parameters['id'])) {
      $this->create();
      $new_file['id'] = $parameters['id'];
    }

    if (!$this->save($new_file, FALSE)) {
      unlink($file_info['abs_path']);

      return NULL;
    }

    $newId = $this->getID();
    if (empty($folderId)) {
      return $newId;
    } else {
      $fileCategoryFileModel = new FileCategoryFile();
      $fileCategoryFileModel->save(array(
        'category_id' => $folderId,
        'file_id'     => $newId
      ), false);
    }

    return $this->getID();
  }

  public function deleteFile($file_record) {
    $errors   = array();
    $messages = array();

    $file_id = $file_record['File']['id'];

    //only commit when delete records and file was succeed
    $dataSource = $this->getDataSource();
    $dataSource->begin();

    //delete record
    $this->_deleteDependants($file_id);
    $this->delete($file_id, TRUE);

    //delete physical file
    if (!empty($file_record['File']['path'])) {
      $file = FileLib::getFullPath($file_record['File']['path']);
      if (file_exists($file)) {
        if (!unlink($file)) $errors[] = 'Unable to delete File';
      }

      //delete thumbnail
      $thumbnail_relavtive_path = FileLib::getThumbnailPath($file_record['File']['path']);
      $thumbnail_file           = FileLib::getFullPath($thumbnail_relavtive_path);
      if (file_exists($thumbnail_file)) {
        if (!unlink($thumbnail_file)) $errors[] = 'Unable to delete Thumbnail File';
      }
    }

    if (empty($errors)) {
      $messages[] = 'File deleted!';
      $dataSource->commit();
    } else {
      $dataSource->rollback();
    }

    return array(
      'errors'   => $errors,
      'messages' => $messages
    );
  }

  public function getFileModels($model, $model_id, $category_code = NULL) {
    $lang  = Configure::read("Config.language");
    $langs = Configure::read("MultiLanguage.list");

    $conditions = array(
      'FileModel.model'    => $model,
      'FileModel.model_id' => $model_id,
    );

    if (!empty($category_code)) {
      $conditions['FileModel.category_code'] = $category_code;
    }

    if (!isset($lang) || !isset($langs[$lang])) {
      $file_models = $this->FileModel->find('all', array(
        'conditions' => $conditions,
        'order'      => array('FileModel.order' => 'ASC'),
      ));

      return $file_models;
    }

    $file_models = $this->FileModel->find('all', array(
      'joins'      => array(
        array(
          'table'      => 'multilanguage_file_model',
          'alias'      => 'MultilanguageFileModel',
          'type'       => 'LEFT',
          'conditions' => array(
            'MultilanguageFileModel.file_model_id = FileModel.id',
            'MultilanguageFileModel.lang_code' => $lang,
          ),
          'order'      => array('FileModel.order' => 'ASC'),
        ),
        array(
          'table'      => 'file',
          'alias'      => 'FileForMultilanguage',
          'type'       => 'LEFT',
          'conditions' => array(
            'FileForMultilanguage.id = MultilanguageFileModel.file_id',
          )
        ),
      ),
      'conditions' => $conditions,
      'order'      => array('FileModel.order' => 'ASC'),
      'fields'     => array(
        'FileModel.*',
        'File.*',
        'MultilanguageFileModel.*',
        'FileForMultilanguage.*'
      ),
    ));

    foreach ($file_models as $i => $file_model) {
      if (isset($file_model['MultilanguageFileModel']['id']) && !empty($file_model['MultilanguageFileModel']['id'])) {

        $file_models[$i]['FileModel']['file_id'] = $file_model['MultilanguageFileModel']['file_id'];

        if (!empty($file_model['MultilanguageFileModel']['name'])) {
          $file_models[$i]['FileModel']['name'] = $file_model['MultilanguageFileModel']['name'];
        }
        if (!empty($file_model['MultilanguageFileModel']['description'])) {
          $file_models[$i]['FileModel']['description'] = $file_model['MultilanguageFileModel']['description'];
        }

        $file_models[$i]['File'] = $file_model['FileForMultilanguage'];
      }
    }

    return $file_models;
  }
}

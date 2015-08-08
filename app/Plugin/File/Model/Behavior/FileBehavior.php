<?php

/**
 * File behavior
 *
 * @package File
 * @subpackage File.models.behaviors
 */
App::uses('FileModel', 'File.Model');
App::import("Model", "File.MultilanguageFileModel");

class FileBehavior extends ModelBehavior {

  /**
   * Settings array
   *
   * @var array
   */
  public $settings = array();

  /**
   * Setup the behavior
   *
   * @param Model $Model
   * @param array $settings
   * @return void
   */
  function setup(\Model $model, $config = array()) {
    parent::setup($model, $config);
  }

  public function beforeFind(\Model $model, $query) {
    parent::beforeFind($model, $query);
    return $query;
  }

  public function afterFind(\Model $model, $results, $primary = false) {
    parent::afterFind($model, $results, $primary);
    return $results;
  }

  public function beforeValidate(\Model $model, $options = array()) {
    parent::beforeValidate($model, $options);
    return true;
  }

  public function afterValidate(\Model $model) {
    parent::afterValidate($model);
  }

  public function beforeSave(\Model $model, $options = array()) {
    return true;
  }

  public function afterSave(\Model $model, $created, $options = array()) {
    parent::afterSave($model, $created, $options);
    $model_class = get_class($model);
    if(isset($model->data[$model_class]) && isset($model->data[$model_class]['File'])){
      $model->data = $model->data[$model_class];
    }
    if (!empty($model->data['File']) && is_array($model->data['File']) && $model_class != 'File') {
      if (empty($model->plugin)) {
        $model_value = get_class($model);
      } else {
        $model_value = $model->plugin . "." . $model_class;
      }
      $file_model = new FileModel();
      $multilang_file_model = new MultilanguageFileModel();
      $file_model_records = $file_model->find('all', array(
        'conditions' => array(
          'FileModel.model' => $model_value,
          'FileModel.model_id' => $model->id,
        )
      ));

      $file_ids = array();
      $delete_ids = array();
      foreach ($file_model_records as $file_model_record) {
        $file_ids[$file_model_record['FileModel']['category_code']][$file_model_record['FileModel']['file_id']] = $file_model_record['FileModel']['id'];
        $delete_ids[] = $file_model_record['FileModel']['id'];
      }

      //do not select any file
      $fileRemoved = false;
      if(isset($model->data['File']) && isset($model->data['File']['selected_files'])) {
        $temp = array_values($model->data['File']['selected_files']);
        if (count($model->data['File']['selected_files']) == 1 && empty($temp[0]['id'])) {
          $fileRemoved = true;
        }
      }

      if (!is_array($model->data['File']) || empty($model->data['File']['selected_files']) || $fileRemoved) {
        $multilang_file_model->deleteAll(array('MultilanguageFileModel.file_model_id' => $delete_ids), FALSE);
        $file_model->deleteAll(array('FileModel.id' => $delete_ids), FALSE);
        return;
      }
      $model_file_multilang_categories = @$model->multiLanguage['files'];
      foreach ($model->data['File']['selected_files'] as $category_code => $parameters) {
        //have no selected files for this category code
        if (!is_array($parameters) || empty($parameters) || (count($parameters) == 1 && !is_array($parameters))) {
          //cascade delete itrainer_multilanguage_file_model
          if (isset($file_ids[$category_code]) && !empty($file_ids[$category_code])) {
            $multilang_file_model->deleteAll(array('MultilanguageFileModel.file_model_id' => $file_ids[$category_code]), FALSE);
          }
          $file_model->deleteAll(array(
            'FileModel.model' => $model_value,
            'FileModel.model_id' => $model->id,
            'FileModel.category_code' => $category_code
            ), FALSE);

          continue;
        }

        foreach ($parameters as $file_id => $selected_file) {
          //skip file_id = not_file_id
          if (intval($file_id) <= 0)
            continue;

          //update file_model table
          $file_model_values = array(
            'name' => isset($selected_file['name']) ? $selected_file['name'] : NULL,
            'description' => isset($selected_file['description']) ? $selected_file['description'] : NULL,
            'order' => isset($selected_file['order']) ? $selected_file['order'] : NULL,
          );
          //update existed
          if (isset($file_ids[$category_code][$selected_file['id']])) {
            $file_model_values['id'] = intval($file_ids[$category_code][$selected_file['id']]);
            $file_model->save($file_model_values, array('callbacks' => FALSE));
            unset($file_ids[$category_code][$selected_file['id']]);
          } else {
            $file_model_values['model'] = $model_value;
            $file_model_values['model_id'] = $model->id;
            $file_model_values['category_code'] = $category_code;
            $file_model_values['file_id'] = $selected_file['id'];

            //insert new
            $file_model->create();
            $file_model->save($file_model_values, array('callbacks' => 'after'));
          }
          //do not have multi language
          if (empty($model_file_multilang_categories) || !in_array($category_code, $model_file_multilang_categories)) {
            continue;
          }

          //update language
          //clear multi language
          if (!isset($selected_file['langs']) || !is_array($selected_file['langs']) || empty($selected_file['langs'])) {
            $multilang_file_model->deleteAll(array('MultilanguageFileModel.file_model_id' => $file_model->id,), FALSE);
            continue;
          }
          $multilang_ids = array();
          foreach ($selected_file['langs'] as $lang_code => $lang_values) {
            //skip empty file
            if (empty($lang_values['file_id']))
              continue;

            $multilang_record = array(
              'file_model_id' => $file_model->id,
              'file_id' => $lang_values['file_id'],
              'lang_code' => $lang_code,
            );
            if (isset($lang_values['name'])) {
              $multilang_record['name'] = $lang_values['name'];
            }
            if (isset($lang_values['description'])) {
              $multilang_record['description'] = $lang_values['description'];
            }

            if (empty($lang_values['id'])) {
              $multilang_file_model->create();
            } else {
              $multilang_record['id'] = $lang_values['id'];
            }

            $multilang_file_model->save($multilang_record);
            $multilang_ids[] = $multilang_file_model->id;
          }
          //clear unused multi lang file model
          $multilang_ids[] = -1;
          $multilang_file_model->deleteAll(array(
            'MultilanguageFileModel.file_model_id' => $file_model->id,
            'NOT' => array(
              'MultilanguageFileModel.id' => $multilang_ids,
            )
            ), FALSE);
        }

        //delete unused file_model
        if (!empty($file_ids[$category_code])) {
          $delete_ids = array();
          foreach ($file_ids[$category_code] as $file_model_id) {
            $delete_ids[] = $file_model_id;
          }

          $multilang_file_model->deleteAll(array('MultilanguageFileModel.file_model_id' => $delete_ids), FALSE);
          $file_model->deleteAll(array('FileModel.id' => $delete_ids), FALSE);
        }
      }
    }
  }

  public function beforeDelete(\Model $model, $cascade = true) {
    parent::beforeDelete($model);
    return true;
  }

  public function afterDelete(\Model $model) {
    parent::afterDelete($model);
  }

  public function onError(\Model $model, $error) {
    parent::onError($model, $error);
  }

}

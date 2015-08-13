<?php

/**
 * MultiLanguage Model
 *
 * @package MultiLanguage
 * @subpackage MultiLanguage.models
 */
class MultiLanguageModel extends AppModel {

  public $useTable = false;
  public $mainModel = null;

  public function getData($objectID) {
    $data = objectToArray($this->getFromQuery("SELECT * FROM {$this->useTable} WHERE `object_id` = '{$objectID}'"));
    $return = array();
    foreach ($data as $value) {
      foreach ($value as $key => $value1) {
        if (in_array($key, array('id', 'object_id', 'lang_code'))) {
          if ($key == 'id') {
            $return['id'][$value['lang_code']] = $value['id'];
          }
          continue;
        }
        $return['data'][$key][$value['lang_code']] = $value1;
      }
    }
    return $return;
  }

  public function setMainModel($model) {
    $this->mainModel = $model;
    $this->useTable = 'multilanguage_' . $model->useTable;
  }

}

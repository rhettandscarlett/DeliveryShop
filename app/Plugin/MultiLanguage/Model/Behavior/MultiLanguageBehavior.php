<?php

/**
 * MultiLanguage behavior
 *
 * @package MultiLanguage
 * @subpackage MultiLanguage.models.behaviors
 */
App::uses('MultiLanguage', 'MultiLanguage.Model');

class MultiLanguageBehavior extends ModelBehavior {

  /**
   * Settings array
   *
   * @var array
   */
  public $settings = array();
  public $langConfig = array();
  public $langData = array();
  public $langModels = array();

  /**
   * Setup the behavior
   *
   * @param Model $Model
   * @param array $settings
   * @return void
   */
  function setup(Model $model, $config = array()) {

  }

  public function beforeFind(Model $Model, $query) {
    $this->langConfig['query'] = $query;

    if (isset($query['multiLanguageIsUsed']) && $query['multiLanguageIsUsed'] == false) {
      return $query;
    }

    return $query;
  }

  function getDataResults($results) {
    foreach ($results as $key => $value) {
      if (is_array($value)) {
        if (isset($value['id'])) {
          $this->langData[$key][$value['id']] = $results[$key];
        }
        $this->getDataResults($value);
      }
    }
  }

  function fillDataResults(&$results, $className = '') {
    foreach ($results as $key => $value) {
      if (is_array($value)) {
        $this->fillDataResults($results[$key], $key);
      } else {
        if (!isset($results['id']) || !isset($this->langModels[$className])) {
          continue;
        }

        $mod = $this->langModels[$className];

        if (!isset($mod->multiLanguage['columns']) || !in_array($key, $mod->multiLanguage['columns'])) {
          continue;
        }

        $results[$key] = $this->langData[$className][$results['id']][$key];
      }
    }
  }

  function initRelatedObjects($model) {
    if (count($model->belongsTo) > 0 || count($model->hasOne) > 0 || count($model->hasMany) > 0) {
      $listClass = array_merge($model->hasOne, $model->hasMany, $model->belongsTo);
      foreach ($listClass as $class) {
        ClassRegistry::init($class['className']);
        $className = @array_pop(explode('.', $class['className']));
        if (isset($this->langModels[$className])) {
          continue;
        }
        $this->langModels[$className] = new $className();
        $this->initRelatedObjects($this->langModels[$className]);
      }
    }
  }

  public function afterFind(Model $Model, $results, $primary = false) {
    if (isset($this->langConfig['query']['multiLanguageIsUsed']) && $this->langConfig['query']['multiLanguageIsUsed'] == false) {
      return $results;
    }

    $this->langModels[get_class($Model)] = $Model;
    $this->initRelatedObjects($Model);

    $this->getDataResults($results);

    foreach ($this->langData as $className => $data) {
      if (!isset($this->langModels[$className]) || count($data) == 0) {
        continue;
      }

      $mod = $this->langModels[$className];

      if (!isset($mod->multiLanguage['columns'])) {
        continue;
      }

      $appModel = new AppModel();
      $appModel->useTable = false;
      $sql = 'SELECT * FROM multilanguage_' . $mod->useTable . ' multilanguage '
              . 'WHERE lang_code = "' . Configure::read('Config.language') . '" AND object_id IN (' . implode(',', array_keys($data)) . ')';
      $languageData = Hash::combine($appModel->query($sql), '{n}.multilanguage.object_id', '{n}.multilanguage');
      foreach ($data as $objectId => $val) {
        foreach ($mod->multiLanguage['columns'] as $column) {
          if (isset($languageData[$objectId][$column]) && isset($val[$column]) && !empty($languageData[$objectId][$column])) {
            $this->langData[$className][$objectId][$column] = $languageData[$objectId][$column];
          }
        }
      }
    }

    $this->fillDataResults($results);
    return $results;
  }

  public function beforeValidate(\Model $model, $options = array()) {
    parent::beforeValidate($model, $options);
    $class = get_class($model);
    if (isset($_POST['MultiLanguage'][$class][$model->getID()]) && !isset($model->data['MultiLanguage'][$class])) {
      $model->data['MultiLanguage'][$class] = $_POST['MultiLanguage'][$class][$model->getID()];
      if (!is_numeric($model->getID()) && isset($model->data[$class]['id'])) {
        $model->data[$class]['id'] = 0;
      }
    }
    return true;
  }

  public function afterValidate(\Model $model) {
    parent::afterValidate($model);
  }

  public function beforeSave(\Model $model, $options = array()) {
    parent::beforeSave($model, $options);
    return true;
  }

  public function afterSave(\Model $model, $created, $options = array()) {
    parent::afterSave($model, $created, $options);
    $class = get_class($model);

    if (isset($model->data['MultiLanguage'][$class])) {
      $data = array();
      foreach ($model->data['MultiLanguage'][$class] as $field => $value) {
        foreach ($value as $lang => $text) {
          $data[$lang][$field] = $text;
        }
      }
      $dataExisted = Hash::combine($model->query('SELECT MultiLanguage.* FROM multilanguage_' . $model->useTable . ' MultiLanguage WHERE object_id = "' . Sanitize::escape($model->getID()) . '"'), '{n}.MultiLanguage.lang_code', '{n}.MultiLanguage.id');

      foreach ($data as $lang => $object) {
        if (isset($dataExisted[$lang])) {
          $object['id'] = $dataExisted[$lang];
        }
        $object['object_id'] = $model->getID();
        $object['lang_code'] = $lang;
        $multiObj = new AppModel();
        $multiObj->useTable = 'multilanguage_' . $model->useTable;
        $multiObj->clear();
        $multiObj->save($object, array('validate' => false, 'callbacks' => false));
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

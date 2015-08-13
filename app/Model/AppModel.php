<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

  public $actsAs = array('File.File');
  public $structures = null;
  public $inputData = array();
  public $schemaCreate = '';

  public function __construct($id = false, $table = null, $ds = null) {
    /*
     * TODO: Add a way to manage many datasources
     */
    parent::__construct($id, $table, $ds);
  }

  public function beforeFind($query) {
    $schema = $this->schema();
    $class = get_class($this);

    if (isset($query['conditions'][$class . '.has_deleted'])) {
      unset($query['conditions'][$class . '.has_deleted']);
    } else {
      if (isset($query['fields']) && is_array($query['fields']) > 0) {
        foreach ($query['fields'] as $field) {
          if (preg_match('/\.deleted_time/', $field)) {
            $query['conditions'][] = "$field IS NULL";
          }
        }
      }

      if (isset($schema['deleted_time']) && (!isset($query['conditions']) || !in_array("$class.deleted_time IS NULL", $query['conditions']))) {
        $query['conditions'][] = "$class.deleted_time IS NULL";
      }
    }

    return $query;
  }

  public function afterFind($results, $primary = false) {
    if (count($results) == 1 && isset($results[0][get_class($this)]['id'])) {
      $this->idFind = $results[0][get_class($this)]['id'];
    }
    return $results;
  }

  public function beforeSave($options = array()) {
    parent::beforeSave($options);
    $schema = $this->schema();
    $class = get_class($this);

    if (isset($this->data[$class])) {
      $this->data[$class] = $this->addTimeToData($this->data[$class]);
    }

    if (isset($this->data[$class])) {
      $this->data[$class] = $this->addLoggedUserToData($this->data[$class]);
    }
  }

  public function addTimeToData($data) {
    $schema = $this->schema();
    if (isset($schema['updated_time'])) {
      $data['updated_time'] = date('Y-m-d H:i:s');
    }

    if (isset($schema['created_time']) && (!isset($data['id']) || $data['id'] == 0)) {
      $data['created_time'] = date('Y-m-d H:i:s');
    }

    return $data;
  }

  public function addLoggedUserToData($data) {
    $schema = $this->schema();
    $loggedUser = CakeSession::read('loggedUser');

    if (isset($schema['user_id']) && !isset($data['user_id']) && isset($loggedUser->User->id) && $loggedUser->User->id > 0) {
      $data['user_id'] = $loggedUser->User->id;
    }

    if (isset($schema['admin_id']) && !isset($data['admin_id']) && isset($loggedUser->Admin->id) && $loggedUser->Admin->id > 0) {
      $data['admin_id'] = $loggedUser->Admin->id;
    }

    return $data;
  }

  public function getReservedColumns() {
    return array('updated_time', 'created_time', 'deleted_time', 'user_id', 'admin_id');
  }

  public function delete($id = NULL, $cascade = true, $logic = true) {
    if ($logic) {
      $this->validate = false;
      $this->set(array(
        'id' => (int) $id,
        'deleted_time' => date('Y-m-d H:i:s')
      ));
      $this->save();
    } else {
      return parent::delete($id, $cascade);
    }
  }

  public function deleteLogic($data, $conditions = null) {
//    $this->validate = false;
    if (is_numeric($data) && is_null($conditions)) {
      $this->save(array(
        'id' => (int) $data,
        'deleted_time' => date('Y-m-d H:i:s')
      ), false);
    } elseif (is_array($data) && is_null($conditions)) {
      foreach ($data as $id) {
        $this->create();
        $this->save(array(
          'id' => $id,
          'deleted_time' => date('Y-m-d H:i:s')
        ), false);
      }
    } elseif (is_null($data) && is_array($conditions)) {
      $data = array(
        'deleted_time' => "'" . date('Y-m-d H:i:s') . "'"
      );
      $this->updateAll($data, $conditions);
    }
  }

  public function updateLinkObjects($mainId, $mainKey, $listOthers, $otherKey) {

    $class = get_class($this);
    $listExisted = Hash::combine($this->find('all', array('conditions' => array($class . '.' . $mainKey . " = " . $mainId))), '{n}.' . $class . '.' . $otherKey, '{n}.' . $class . '.' . $otherKey);
    foreach ($listOthers as $key => $val) {
      if (is_array($val)) {
        if (isset($val['id']) && $val['id'] == 0) {
          continue;
        } else {
          $val = $key;
        }
      } else {
        if ($val == 0) {
          continue;
        } else {
          $val = $key;
        }
      }
      if (!isset($listExisted[$val])) {
        $data = array();
        $data[$class][$mainKey] = $mainId;
        $data[$class][$otherKey] = $val;
        $this->clear();
        $this->save($data);
      }
      unset($listExisted[$val]);
    }

    if (count($listExisted) > 0) {
      $this->query('DELETE FROM ' . $this->useTable . ' WHERE ' . $mainKey . ' = ' . Sanitize::escape($mainId) . ' AND ' . $otherKey . ' IN ("' . implode('","', $listExisted) . '")');
    }
  }

  public function findByIdEdit($id = 0) {
    $data = $this->find('all', array('multiLanguageIsUsed' => false, 'conditions' => array(get_class($this) . '.id = ' . Sanitize::escape($id))));
    return isset($data[0]) ? $data[0] : array();
  }

  /*
   * Check an object is used as foreign key in other object or not
   * TODO: we could list the related links to other objects
   */

  public function isInUsed($id) {
    $ref = $this->getReferenceKey($this->useTable);
    foreach ($ref as $row) {
      $structure = $this->getStructure($row['TABLE_NAME']);
      $join = '';
      if (isset($structure['columns']['object_id'])) {
        $join = "INNER JOIN object ON object.id = {$row['TABLE_NAME']}.object_id AND object.deleted_time IS NULL ";
      }
      $nb = $this->getDataSource()->rawQuery("SELECT COUNT(*) nb FROM {$row['TABLE_NAME']} $join WHERE {$row['COLUMN_NAME']} = $id")->fetch(PDO::FETCH_ASSOC);
      if ($nb['nb'] > 0) {
        return true;
      }
    }
    return false;
  }

  /*
   * $listDependant = array('Plugin.Class' => foreign_key....)
   */

  public function copy($idCopy, $listDependant = array()) {
    $id = Sanitize::escape($idCopy);

    //copy object
    $className = get_class($this);
    $record = $this->findByIdEdit($id);
    $record = $this->copyInitData($record, $className);
    $this->save($record, false);
    $newId = $this->getID();

    $this->copyLanguage($this, $id, $newId);
    $this->copyFiles($this, $id, $newId);

    foreach ($listDependant as $class => $key) {
      $model = ClassRegistry::init($class);
      $className = get_class($model);
      $listOld = $model->query('SELECT * FROM ' . $model->useTable . ' ' . $className . ' WHERE ' . $key . ' = ' . $id);
      foreach ($listOld as $data) {
        $oldModelId = $data[$className]['id'];
        $record = $this->copyInitData($data, $className);
        $record[$className][$key] = $newId;
        $model->clear();
        $model->save($record, false);
        $newModelId = $model->getID();
        $this->copyLanguage($model, $oldModelId, $newModelId);
        $this->copyFiles($model, $oldModelId, $newModelId);
      }
    }

    return $newId;
  }

  private function copyLanguage($object, $id, $newId) {
    if (isset($object->multiLanguage['columns'])) {
      $tableLanguage = 'multilanguage_' . $object->useTable;
      $model = new AppModel();
      $model->useTable = $tableLanguage;
      $fields = array_keys($model->schema());
      $fieldSelect = $fieldInsert = array();
      foreach ($fields as $field) {
        if (in_array($field, array('id'))) {
          continue;
        }
        $fieldInsert[] = '`' . $field . '`';
        if ($field == 'object_id') {
          $fieldSelect[] = '"' . $newId . '"';
        } else {
          $fieldSelect[] = '`' . $field . '`';
        }
      }
      $query = 'INSERT INTO ' . $model->useTable . ' (' . implode(',', $fieldInsert) . ') SELECT ' . implode(',', $fieldSelect) . ' FROM ' . $model->useTable . ' WHERE object_id = ' . $id;
      $this->query($query);
    }
  }

  private function copyFiles($object, $id, $newId) {
    $className = get_class($object);
    $model = (!empty($object->plugin) ? $object->plugin . '.' : '') . $className;
    $query = 'INSERT INTO file_model (`file_id`, `model`, `model_id`, `category_code`, `name`, `description`, `order`) SELECT ' .
      '`file_id`, `model`, "' . $newId . '", `category_code`, `name`, `description`, `order`  FROM file_model ' .
      'WHERE model_id = ' . $id . ' AND model = "' . $model . '"';
    $this->query($query);
  }

  private function copyInitData($record, $className) {
    $record[$className]['id'] = NULL;
    unset($record[$className]['deleted_time']);
    unset($record[$className]['updated_time']);
    if (isset($record[$className]['created_time'])) {
      $record[$className]['created_time'] = date('Y-m-d H:i:s');
    }
    if (isset($record[$className]['name'])) {
      $record[$className]['name'] = $record[$className]['name'] . ' - (COPY)';
    }
    return $record;
  }

  public function getOneObject($query, $withname = false) {
    $data = $this->query($query);
    $class = get_class($this);
    if ($withname) {
      return is_array($data[0]) ? $data[0] : array("$class" => NULL);
    } else {
      return isset($data[0][$class]) ? $data[0][$class] : NULL;
    }
  }

  /*
   * Return the structure of a used table, that included
   * - Columns & its attributes like unique, type...
   * - foreign keys
   */

  public function getStructure() {
    /*
     * TODO: improve the performance by using the session
     */

    if ($this->structures != null) {
      return $this->structures;
    }
    $structure = array();

    if ($this->useTable) {
      $columns = $this->getDataSource()->describe($this->useTable);
      foreach ($columns as $column => $val) {
        $structure['columns'][$column] = $val;
      }
      $keys = $this->getDataSource()->rawQuery('SHOW KEYS FROM `' . Sanitize::escape($this->useTable) . '`');
      while ($row = $keys->fetch(PDO::FETCH_ASSOC)) {
        if ($row['Non_unique']) {
          continue;
        }
        $structure['keys'][$row['Key_name']][] = $row['Column_name'];
      }

      $fKey = $this->getForeignKeys($this->useTable);
      if (count($fKey) > 0) {
        $structure['foreignKeys'] = $fKey;
      }
    }

    return $this->structures = $structure;
  }

  /*
   * Generate validation rule base on structure & naming rule of column
   */

  public function buildValidationRule() {

    $structures = $this->getStructure();

    if (!isset($structures['columns'])) {
      return false;
    }

    foreach ($structures['columns'] as $column => $structure) {
      if (in_array($column, array('id', 'object_id', 'deleted_time', 'updated_time', 'created_time', 'created_user_id', 'updated_user_id', 'deleted_user_id'))) {
        continue;
      }

      $emptyRule = false;
      if (!$structure['null']) {
        $this->validate[$column]['notNull'] = array(
          'rule' => 'notEmpty',
          'required' => true,
          'message' => __('This field cannot be left blank'));
      } else {
        $emptyRule = true;
      }

      $this->inputData[$column]['default'] = $structure['default'];

      if (in_array($structure['type'], array('string', 'text'))) {
        $this->inputData[$column]['type'] = $structure['type'];
        if ($structure['length'] > 0) {
          $this->validate[$column]['size'] = array(
            'rule' => array('maxLength', $structure['length']),
            'message' => __('Please enter a text no larger than ' . $structure['length'] . ' characters long'));
          if ($emptyRule) {
            $this->validate[$column]['size']['allowEmpty'] = true;
          }
        }
      }

      if (in_array($structure['type'], array('integer', 'biginteger'))) {
        $this->inputData[$column]['type'] = 'string';
        $this->validate[$column]['numeric'] = array(
          'rule' => 'numeric',
          'message' => 'Please enter a valid number');
        if ($emptyRule) {
          $this->validate[$column]['numeric']['allowEmpty'] = true;
        }
      }

      if (in_array($structure['type'], array('float'))) {
        $this->inputData[$column]['type'] = 'string';
        $this->validate[$column]['decimal'] = array(
          'rule' => 'decimal',
          'message' => 'Please enter a valid number');
        if ($emptyRule) {
          $this->validate[$column]['decimal']['allowEmpty'] = true;
        }
      }

      if (in_array($structure['type'], array('date'))) {
        $this->inputData[$column]['type'] = 'date';
        $this->validate[$column]['date'] = array(
          'rule' => array('date', Configure::read('AppDateFormat')),
          'message' => 'Please enter a valid date format');
        if ($emptyRule) {
          $this->validate[$column]['date']['allowEmpty'] = true;
        }
      }

      if (in_array($structure['type'], array('datetime'))) {
        $this->inputData[$column]['type'] = 'datetime';
        $this->validate[$column]['datetime'] = array(
          'rule' => array('datetime', Configure::read('AppDateFormat')),
          'message' => 'Please enter a valid date format');
        if ($emptyRule) {
          $this->validate[$column]['datetime']['allowEmpty'] = true;
        }
      }

      if (preg_match('/^email$/', $column) || preg_match('/^email_/', $column) || preg_match('/_email$/', $column) || preg_match('/_email_/', $column)) {
        $this->inputData[$column]['type'] = 'email';
        $this->validate[$column]['email'] = array(
          'rule' => array('email'),
          'message' => 'Please enter a valid email address');
      }

      /*
        TODO: Add the validation rule for email, url, date, ip....
        Base on the prefix of column name...
       */
    }

    if (isset($structures['foreignKeys'])) {
      foreach ($structures['foreignKeys'] as $column => $key) {
        $this->inputData[$column]['type'] = 'select';
        $this->inputData[$column]['data'] = $key;
      }
    }

    //Check the unique
    if (isset($structures['keys'])) {
      foreach ($structures['keys'] as $index => $key) {
        if (count($key) == 1 && in_array($key[0], array('id', 'object_id'))) {
          continue;
        }

        $message = ucwords(str_replace('_', ' ', $key[0]));
        for ($ii = 1; $ii < count($key); $ii++) {
          $message .= ', ' . ucwords(str_replace('_', ' ', $key[$ii]));
        }

        $this->validate[$key[0]]['unique_' . $index] = array(
          'rule' => array('checkUnique', $key),
          'message' => $message . " already exists"
        );
      }
    }
  }

  public function checkUnique($data, $fields) {
    return $this->isUnique($fields, false);
  }

  public function isMatchedValidate($data, $confirmField) {
    $data = array_values($data);
    $var1 = $data[0];
    $var2 = (isset($this->data[$this->name][$confirmField])) ? $this->data[$this->name][$confirmField] : '';

    return ($var1 === $var2);
  }

  /*
   * return a list objects from query
   */

  public function getFromQuery($query) {
    $list = array();
    $rs = $this->getDataSource()->rawQuery($query);
    while ($row = $rs->fetch(PDO::FETCH_OBJ)) {
      if (isset($row->id)) {
        $list[$row->id] = $row;
      } else {
        $list[] = $row;
      }
    }
    return $list;
  }

  public function getReferenceKey($table) {
    $query = "SELECT *
      FROM
        information_schema.KEY_COLUMN_USAGE
      WHERE
        REFERENCED_TABLE_NAME = '{$table}'
        AND REFERENCED_COLUMN_NAME = 'object_id'
        AND TABLE_SCHEMA = '{$this->getDataSource()->getSchemaName()}';";
    $rs = $this->getDataSource()->rawQuery($query);
    $ref = array();
    while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
      $ref[] = $row;
    }
    return $ref;
  }

  public function getForeignKeys($usetable) {
    $fKeys = array();
    $createTable = $this->initSchemaCreate($usetable);
    preg_match_all('/CONSTRAINT.*?FOREIGN KEY\s*\([`]?(.*?)[`]?\)\s*REFERENCES\s*[`]?(.*?)[`]?\s*\([`]?(.*?)[`]?\)/', $createTable['Create Table'], $foreignKeys);
    foreach ($foreignKeys[2] as $key => $table) {
      if ($table == 'object') {
        continue;
      }
      $fKeys[$foreignKeys[1][$key]]['table'] = $table;
      $fKeys[$foreignKeys[1][$key]]['refId'] = $foreignKeys[3][$key];
    }
    return $fKeys;
  }

  public function initSchemaCreate($usetable) {
    if (empty($this->schemaCreate)) {
      $this->schemaCreate = $this->getDataSource()->rawQuery("SHOW CREATE TABLE " . $usetable)->fetch(PDO::FETCH_ASSOC);
    }
    return $this->schemaCreate;
  }

  public function exists($id = null, $check_deleted_time = false) {
    if ($id === null) {
      $id = $this->getID();
    }
    if ($id === false) {
      return false;
    }

    $conditions = array();
    $conditions[$this->alias . '.' . $this->primaryKey] = $id;

    $schema = $this->schema();
    $class = get_class($this);
    if ($check_deleted_time && isset($schema['deleted_time'])) {
      $conditions[] = $this->alias . '.deleted_time IS NULL';
    }
    return (bool) $this->find('count', array(
        'conditions' => $conditions,
        'recursive' => -1,
        'callbacks' => false
    ));
  }

  /**
   * param $keyAndModelOfKeyList: eg:  array(key1 => ModelName1, key2 => ModelName2)
   * @desc: Another way of combine array to compare with Hash::combine(). This function can combine unlimited numbers of key want to combine
   * @desc2: $unsetModelList: those modelClass will be ignore at the last level of data.
   */
  public static function generalCombine($data, $modelClass, $keyAndModelOfKeyList = array(), $unsetModelList = array()) {
    $returnData = array();
    $listOfCombineKey = array_keys($keyAndModelOfKeyList);
    if (count($listOfCombineKey) == 1) {
      foreach ($data as $singleData) {
        $each = $singleData[$modelClass];
        if (!isset($returnData[$each[$listOfCombineKey[0]]])) {
          foreach ($unsetModelList as $eachUnsetModelList) {
            if (isset($singleData[$eachUnsetModelList])) {
              unset($singleData[$eachUnsetModelList]);
            }
          }
          $returnData[$each[$listOfCombineKey[0]]] = $singleData;
        }
      }
      return $returnData;
    } else {
      $groupKeys = array();
      foreach ($data as $singleData) {
        $each = $singleData[$modelClass];
        if (!isset($returnData[$each[$listOfCombineKey[0]]])) {
          $returnData[$each[$listOfCombineKey[0]]] = array();
          $groupKeys[$each[$listOfCombineKey[0]]] = array();
          if (isset($singleData[$keyAndModelOfKeyList[$listOfCombineKey[0]]])) {
            $groupKeys[$each[$listOfCombineKey[0]]] = array($keyAndModelOfKeyList[$listOfCombineKey[0]] => $singleData[$keyAndModelOfKeyList[$listOfCombineKey[0]]]);
          }
        }
        if (isset($returnData[$each[$listOfCombineKey[0]]])) {
          array_push($returnData[$each[$listOfCombineKey[0]]], $singleData);
        }
      }
      unset($keyAndModelOfKeyList[$listOfCombineKey[0]]);
      foreach ($groupKeys as $groupKey => $currentDataAtIndex) {
        $returnData[$groupKey] = self::generalCombine($returnData[$groupKey], $modelClass, $keyAndModelOfKeyList, $unsetModelList);
        foreach ($currentDataAtIndex as $currentIndex => $currentData) {
          $returnData[$groupKey][$currentIndex] = $currentData;
        }
      }
    }
    return $returnData;
  }

  public function sortOrder($orders = array()) {
    foreach ($orders as $id => $order) {
      $this->save(array('id' => $id, 'order' => 'order'));
    }
  }

}

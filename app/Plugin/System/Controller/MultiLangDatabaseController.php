<?php

App::uses('AppModel', 'Model');

class MultiLangDatabaseController extends AppController {

  /**
   * list all model and attribute to view
   */
  public function listModel() {
    //list al app model name
    $appModels = $this->_getAllModel();
    //list all plugin model name
    $plugins = CakePlugin::loaded();
    $pluginModels = array(); //format array(Plugin Name => array(Model1=>attr1, Model2=>attr2));
    if ($plugins) {
      foreach ($plugins as $plugin) {
        $pluginModels[$plugin] = $this->_getAllModel($plugin);
      }
    }
    $this->set('appModels', $appModels);
    //$pluginModels = array();
    $this->set('pluginModels', $pluginModels);
  }
  private function _refomatOpt($opts){
    $ret = array();
    if($opts){
      foreach($opts as $key => $opt){
        if($opt){
          if($opt!='File.File'){
            $ret[] = $opt;
          }
        }else{
          if($key!='File.File'){
            $ret[] = $key;
          }
        }
      }
    }
    return $ret;
  }
  //action when click "generate table" button
  public function generateTable() {
    $modelList = $this->request->data;
    if ($modelList) {
      foreach ($modelList as $modelName => $modelAttributes) {
        $plugin = $modelAttributes['plugin'];
        unset($modelAttributes['plugin']);

        if ($modelAttributes) {//if attribute is checked
          $modelClass = ClassRegistry::init($modelName);
          $multiLangOpt = $modelClass->multiLanguage ? $this->_refomatOpt($modelClass->multiLanguage) : array();
          $multiLangOpt['columns'] = array();
          $actsAsOpt = $modelClass->actsAs ? $this->_refomatOpt($modelClass->actsAs) : array();
          $tableName = $modelClass->useTable;
          if ($tableName) {
            //create multilang table
            $model = new AppModel();
            $model->useTable = 'multilanguage_' . $tableName;
            $attributes = $this->_getModelAttributes($model);
            if (empty($attributes)) {//table not exist => create multilang table
              $query = "CREATE TABLE `{$model->useTable}` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `object_id` bigint(20) unsigned NOT NULL,
                        `lang_code` varchar(3),
                      PRIMARY KEY (`id`),
                      UNIQUE INDEX `{$model->useTable}_unique` (`object_id`, `lang_code`),
                      KEY `{$model->useTable}_lang_code` (`lang_code`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
              $model->query($query);
            }

            foreach ($modelAttributes as $modelAttribute => $flag) {
              $multiLangOpt['columns'][] = $modelAttribute;
              try {
                $query = "ALTER TABLE `{$model->useTable}` ADD `$modelAttribute` TEXT  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL;";
                $model->query($query);
              } catch (Exception $ex) {
                
              }
            }
            //end create multilang table
            //add attribute to model
            $folderPath = $plugin ? ROOT . '/app/Plugin/' . $plugin . '/Model' : ROOT . '/app/Model';
            $filePath = $folderPath . '/' . $modelName . '.php';
            $content = file_get_contents($filePath);
            //muti lang field
            $strMultiLangOpt = 'var $multiLanguage = ' . $this->_improvedVarExport($multiLangOpt, true) . ";";
            preg_match_all('/(var|public)\s\$multiLanguage\s?=(.*?);/s', $content, $multiLangMatches);

            if (isset($multiLangMatches[0][0])) {//if exists in model file
              $content = str_replace($multiLangMatches[0][0], $strMultiLangOpt, $content);
            } else {//not exists
              $content = $this->_insertAttributeToFile($content, $strMultiLangOpt);
            }
            //actsAs field
            preg_match_all('/(var|public)\s\\$actsAs\s?=(.*?);/s', $content, $actsAsMatches);
            if (!in_array('MultiLanguage.MultiLanguage', $actsAsOpt)) {//not exist this option
              $actsAsOpt[] = 'MultiLanguage.MultiLanguage';
            }
            $strActsAsOpt = 'var $actsAs = ' . $this->_improvedVarExport($actsAsOpt, true) . ";";
            if (isset($actsAsMatches[0][0])) {//if exists in model file
              $content = str_replace($actsAsMatches[0][0], $strActsAsOpt, $content);
            } else {//not exists
              $content = $this->_insertAttributeToFile($content, $strActsAsOpt);
            }

            if ($content) {
              file_put_contents($filePath, $content);
            }
          }
        }
      }
    }
    $this->Session->setFlash(__("Generate successfully"), 'flash/success');
    $this->redirect(Router::url(array('plugin'=>'System', 'controller' => 'MultiLangDatabase', 'action' => 'listModel'), true));
  }

  public function cleanUnUse() {
    //list al app model name
    $appModels = $this->_getAllModel();
    foreach ($appModels as $modelName => $modelAttribute) {
      $this->_deleteUnUseColumn($modelName);
    }
    //list all plugin model name
    $plugins = CakePlugin::loaded();
    if ($plugins) {
      foreach ($plugins as $plugin) {
        $pluginModels = $this->_getAllModel($plugin);
        if($pluginModels){
          foreach($pluginModels as $pluginModelName => $modelField){
            $this->_deleteUnUseColumn($pluginModelName, $plugin);
          }
        }
      }
    }
    $this->Session->setFlash(__("Clean successfully"), 'flash/success');
    $this->redirect(Router::url(array('plugin'=>'System', 'controller' => 'MultiLangDatabase', 'action' => 'listModel'), true));
  }

  private function _deleteUnUseColumn($modelName, $plugin='') {
    $modelName = $plugin ? $plugin.'.'.$modelName : $modelName;
    $modelClass = ClassRegistry::init($modelName);
    if(property_exists ($modelClass , 'multiLanguage')){
      $multiLangOpt = $modelClass->multiLanguage;
      if (isset($multiLangOpt['columns']) && $multiLangOpt['columns']) {
        $columns = $multiLangOpt['columns'];
        $tableName = $modelClass->useTable;
        if ($tableName) {
          $model = new AppModel();
          $model->useTable = 'multilanguage_' . $tableName;
          $attributes = $this->_getModelAttributes($model);
          $multiLangRequireColumn = array('id', 'object_id', 'lang_code');
          $multiLangColumns = array_diff($attributes, $multiLangRequireColumn);
          $unUseColumns = array_diff($multiLangColumns, $columns);
          if (!empty($unUseColumns)) {
            foreach ($unUseColumns as $unUseColumn) {
              try {
                $query = "ALTER TABLE `{$model->useTable}` DROP $unUseColumn;";
                $model->query($query);
              } catch (Exception $ex) {
                echo $ex->getMessage();
              }
            }
          }
        }
      }
    }
  }

  /**
   * insert new variable to model file
   * @param string $content content of file model
   * @param string $variable
   * @return string $content new content after insert
   */
  private function _insertAttributeToFile($content, $variable) {
    $pos = strpos($content, "{");
    if ($pos) {
      $content = substr_replace($content, "\n  " . $variable, $pos + 1, 0);
    }
    return $content;
  }

  /**
   * 
   * @param stdClass $variable
   * @param type $return
   * @return null
   */
  private function _improvedVarExport($variable, $return = false) {
    if ($variable instanceof stdClass) {
      $result = '(object) ' . _improvedVarExport(get_object_vars($variable), true);
    } else if (is_array($variable)) {
      $array = array();
      foreach ($variable as $key => $value) {
        if(is_numeric($key)){
          $array[] = $this->_improvedVarExport($value, true);
        }else{
          $array[] = var_export($key, true) . ' => ' . $this->_improvedVarExport($value, true);
        }
        
      }
      $result = 'array (' . implode(', ', $array) . ')';
    } else {
      $result = var_export($variable, true);
    }
    if (!$return) {
      print $result;
      return null;
    } else {
      return $result;
    }
  }

  /**
   * get all model from folder
   * @param string $folderPath
   * @return array  $modelNameArr
   */
  private function _getAllModel($plugin = '') {
    $folderPath = $plugin ? ROOT . '/app/Plugin/' . $plugin . '/Model' : ROOT . '/app/Model';
    $notIncludeModel = Configure::read("MultiLangDatabase.NotIncludeModel");
    $modelsArr = array();
    if (is_dir($folderPath)) {
      $modelFileNames = scandir($folderPath);
      foreach ($modelFileNames as $modelFileName) {
        $modelFullPath = $folderPath . '/' . $modelFileName;
        if (is_file($modelFullPath)) {
          $modelName = substr($modelFileName, 0, -4); //get model name
          if (!in_array($modelName, $notIncludeModel)) {
            $className = $plugin ? $plugin . '.' . $modelName : $modelName;
            $modelClass = ClassRegistry::init($className);
            $modelAttributes = $this->_getModelAttributes($modelClass);
            $modelsArr[$modelName] = $modelAttributes;
          }
        }
      }
    }
    return $modelsArr;
  }

  /**
   * get model attributes
   * @param string $className model class name
   * @return array $fields all attributes of model
   */
  private function _getModelAttributes($modelClass) {
    $fields = array();
    try {
      if (method_exists($modelClass, 'schema')) {
        $schema = $modelClass->schema();
        if ($schema) {
          foreach ($schema as $column => $properties) {
            $fields[] = $column;
          }
        }
      }
    } catch (Exception $ex) {
      
    }
    return $fields;
  }

}

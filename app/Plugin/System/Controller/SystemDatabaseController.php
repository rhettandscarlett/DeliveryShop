<?php

class SystemDatabaseController extends AppController {

  var $uses = array(
    'System.SystemDatabase',
    'DATABASE_CONFIG'
  );
  CONST TMP_TABLE_PREFIX = "sdb_tmp_";

  /**
   * action updateDd with view, default action
   */
  public function updateDb() {
    $ownDbFile = $path = APP . 'Plugin/System/Config/database/schema.sql';
    if(file_exists($ownDbFile)){ 
      try{
        $this->SystemDatabase->findById(1);
      }catch(Exception $e){//db not found
        $createTableQuery = file_get_contents($ownDbFile);
        $tmpModel = new AppModel();
        $tmpModel->useTable = false;
        $tmpModel->query($createTableQuery);
      }
    }
    $updatedList = Hash::combine(
        $this->SystemDatabase->find('all', array('order' => 'SystemDatabase.updated_script ASC')), '{n}.SystemDatabase.updated_script', '{n}.SystemDatabase', '{n}.SystemDatabase.plugin'
    );
    $this->set('updatedList', $updatedList);
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $dir = new Folder(APP . 'Config/database');
    $sqlSystem = $dir->find('.*\.sql');
    if (in_array('schema.sql', $sqlSystem)) {
      $sqlSystem = array_merge(array('schema.sql'), array_diff($sqlSystem, array('schema.sql')));
    }
    $this->set('sqlSystem', $sqlSystem);

    $pluginPath = APP . 'Plugin/';
    $plugins = CakePlugin::loaded();
    $sqlPlugins = array();
    foreach ($plugins as $plugin) {
      $dir = new Folder($pluginPath . $plugin . '/Config/database');
      $sqlPlugins[$plugin] = $dir->find('.*\.sql');
      if (in_array('schema.sql', $sqlPlugins[$plugin])) {
        $sqlPlugins[$plugin] = array_merge(array('schema.sql'), array_diff($sqlPlugins[$plugin], array('schema.sql')));
      }
    }
    $this->set('sqlPlugins', $sqlPlugins);
  }
  
  public function updateDBExecute($script, $plugin = '') {
    $data = array();
    $data['updated_script'] = $script;
    if (empty($plugin)) {
      $plugin = NULL;
      $path = APP . 'Config/database/' . $script;
    } else {
      $path = APP . 'Plugin/' . $plugin . '/Config/database/' . $script;
      $data['plugin'] = $plugin;
    }
    //backup database
    $pluginName = $plugin ? $plugin . "_" : "";
    //$backupScriptName = str_replace(".sql", "_" . date('Y-m-d-H-i-s',time()) . '.sql', $script);
    $backupPath = APP . 'Config/database/backup/' . $pluginName . $script;
    if(!file_exists($backupPath)){
      $this->_backupDatabase($backupPath);
    }
    //end backup
    $dbVersion = $this->SystemDatabase->findByUpdatedScriptAndPlugin($script, $plugin);
    if (empty($dbVersion)) {
      $data['status'] = SYSTEM_DB_STATUS_RUNNING;
      $data['start_updated_time'] = date('Y-m-d H:i:s');
      $this->SystemDatabase->save($data, false);
      $data['id'] = $this->SystemDatabase->getInsertID();
    } else {
      if ($dbVersion['SystemDatabase']['status'] == SYSTEM_DB_STATUS_RUNNING) {
        die('This script is updating');
      } else if ($dbVersion['SystemDatabase']['status'] == SYSTEM_DB_STATUS_SUCCESS) {
        die('This script is updated');
      }
      $data = $dbVersion['SystemDatabase'];
    }
    if (is_file($path)) {
      $query = file_get_contents($path);
      //get exec result
      $ret = $this->_execQuery($query);
      if ($ret['isError'] == FALSE) {//if temp table OK => exec main table
        $ret = $this->_execQuery($query, FALSE);
      }
      $data['end_updated_time'] = date('Y-m-d H:i:s');
      $data['log'] = $ret['errorMsg'];
      if ($ret['isError']) {
        $data['status'] = SYSTEM_DB_STATUS_FAILR;
        $this->Session->setFlash("Update Failr", 'flash/error');
      } else {
        $data['status'] = SYSTEM_DB_STATUS_SUCCESS;
        $this->Session->setFlash("Update successfully", 'flash/success');
      }
      $this->SystemDatabase->save($data, false);
    }
    return $this->redirect(Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDb')));
  }

  public function preview($script, $plugin = '') {
    if (empty($plugin)) {
      $plugin = NULL;
      $path = APP . 'Config/database/' . $script;
    } else {
      $path = APP . 'Plugin/' . $plugin . '/Config/database/' . $script;
    }
    $dbVersion = $this->SystemDatabase->findByUpdatedScriptAndPlugin($script, $plugin);
    if (!empty($dbVersion)) {
      if ($dbVersion['SystemDatabase']['status'] == SYSTEM_DB_STATUS_RUNNING) {
        die('This script is updating');
      } else if ($dbVersion['SystemDatabase']['status'] == SYSTEM_DB_STATUS_SUCCESS) {
        die('This script is updated');
      }
    }
    if (is_file($path)) {
      $query = file_get_contents($path);
      //get exec result
      $ret = $this->_execQuery($query);
      echo nl2br($ret['errorMsg']);
    } else {
      echo sprintf('File "%s" not found!', $path);
    }
    exit();
  }

  public function revert($script, $plugin = '') {
    $pluginName = $plugin ? $plugin . "_" : "";
    $backupPath = APP . 'Config/database/backup/' . $pluginName . $script;
    $message = "";
    if (is_file($backupPath)) {
      $this->_importDatabase($backupPath);
    } else {
      $message = sprintf('File "%s" not found!', $backupPath);
    }
    if ($message) {
      $this->Session->setFlash($message, 'flash/error');
    } else {
      $this->Session->setFlash($message, 'flash/success');
    }
    return $this->redirect(Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDb')));
  }

  public function updateDBView($id, $type = 'log') {
    $dbVersion = $this->SystemDatabase->findById($id);
    if ($dbVersion) {
      echo nl2br($dbVersion['SystemDatabase']['log']);
    }
    die;
  }

  private function _getSqlBinDir() {
    $dumpBin = MYSQL_BIN_PATH;
    return $dumpBin;
  }

  /**
   * list table 
   * 1. get table with command "CREATE TABLE "
   * 2. get all table in system
   * 3. merge 1 & 2 and remove duplicate value
   * @param string $queryContent query script
   * @return array $table all table name from script and system
   */
  private function _listTables($queryContent) {
    $pattern = '/create+\s+table\s+`?([a-z\d_]+)`?/i';
    preg_match_all($pattern, $queryContent, $tableOnCreates); //get all table with "create table .."
    $tables = $this->SystemDatabase->getDataSource()->listSources();
    if ($tableOnCreates[1]) {
      $tables = array_merge($tables, $tableOnCreates[1]);
      $tables = array_unique($tables);
    }
    return $tables;
  }

  /**
   * replace table in sql script add prefix sdb_tmp_{table name} to create temporary table
   * @param string $queryContent script content
   * @return string $queryContent content after replace
   */
  private function _replace2Table($queryContent) {
    $sourceTables = array();
    $destTables = array();
    $tables = $this->_listTables($queryContent);
    foreach ($tables as $table) {
      $sourceTables[] = "`" . $table . "`";
      $sourceTables[] = " " . $table . " ";
      $sourceTables[] = "'" . $table . "'";
      $destTables[] = "`" . self::TMP_TABLE_PREFIX . $table . "`";
      $destTables[] = " " . self::TMP_TABLE_PREFIX . $table . " ";
      $destTables[] = "''" . self::TMP_TABLE_PREFIX . $table . "'";
    }
    $queryContent = str_replace($sourceTables, $destTables, $queryContent);
    return $queryContent;
  }

  /**
   * execute query script, can run in preview mode
   * in preview mode : 
   *  1. create all temporary table in system, 
   *  2. replace all {table name} in sql script to sdb_tmp_{table name}
   *  3. execute new sql script add always rollback in transaction (if miss something will do not affect to db)
   *  4. delete all temp table
   * do not in preview mode: execute script with transaction(exception will rollback)
   * @param string $query query script content
   * @param boolean $isPreview: true if preview mode
   * @return array $ret contain $isError (true: if error) and $errorMsg (error message)
   */
  private function _execQuery($query, $isPreview = TRUE) {
    $isError = FALSE;
    $errorMsg = "";
    if ($isPreview) {
      $this->_createTemporaryTable();
    }
    $dataSource = $this->SystemDatabase->getDataSource();
    $dataSource->begin();
    $query = trim($query);
    try {
      $errorMsg .= "#### Run ####\n";
      $errorMsg .= $query . "\n";
      $queryTmp = $isPreview ? $this->_replace2Table($query) : $query;
      $this->SystemDatabase->query($queryTmp);
      $errorMsg .= "Error: NO\n";
      $errorMsg .= "Affected rows: {$this->SystemDatabase->getAffectedRows()}\n";
    } catch (Exception $e) {
      $errorMsg .= "Error: " . str_replace(self::TMP_TABLE_PREFIX, '', $e->errorInfo[2]) . "\n";
      $isError = TRUE;
      $dataSource->rollback();
    }
    $errorMsg .= "\n\n";
    if ($isPreview) {//always rollback on preview
      $dataSource->rollback();
    }
    $ret['isError'] = $isError;
    $ret['errorMsg'] = $errorMsg;
    if ($isPreview) {
      $this->_dropTemporaryTable($query);
    }
    return $ret;
  }

  /**
   * drop temporary table
   *  1. list all table in query and system and add prefix sdb_tmp_{table name}
   *  2. drop all table after add prefix
   * @param string $query sql script content
   * @return boolean $result drop query result;
   */
  private function _dropTemporaryTable($query) {
    $tables = $this->_listTables($query);
    $queryTmpTable = "SET FOREIGN_KEY_CHECKS = 0; ";
    foreach ($tables as $table) {
      $tmpTable = strpos($table, self::TMP_TABLE_PREFIX) === FALSE ? self::TMP_TABLE_PREFIX . $table : $table;
      $queryTmpTable .= "DROP TABLE IF EXISTS `{$tmpTable}`; ";
    }
    $queryTmpTable .= 'SET FOREIGN_KEY_CHECKS = 1;';
    $result = $this->SystemDatabase->query($queryTmpTable);
    return $result;
  }

  /**
   * create temporary table
   * 1. get all table in system and add prefix sdb_tmp_{table name}
   * 2. create command create table after add prefix
   * @return boolean $result create query result
   */
  private function _createTemporaryTable() {
    $tables = $this->SystemDatabase->getDataSource()->listSources();
    $queryTmpTable = "";
    foreach ($tables as $table) {
      $tmpTable = strpos($table, self::TMP_TABLE_PREFIX) === FALSE ? self::TMP_TABLE_PREFIX . $table : $table;
      $queryTmpTable .= "CREATE TEMPORARY TABLE {$tmpTable} AS (SELECT * FROM {$table});";
    }
    $result = $this->SystemDatabase->query($queryTmpTable);
    return $result;
  }

  /**
   * backup database 
   * 1. read config then get table don't need backup
   * 2. get all table in system and remove table don't need backup
   * 2. create shell command and exec
   * @param string $backupPath backup path
   * @return mixed $ret result after exec command
   */
  private function _backupDatabase($backupPath, $isFull=FALSE) {
    if(!$isFull){
      $nonBackupTables = Configure::read("SystemDatabase.NonBackupTable");
    }
    $tables = $this->SystemDatabase->getDataSource()->listSources();
    $backupTables = array_diff($tables, $nonBackupTables);
    $strBackupTables = implode(" ", $backupTables);
    $dbConfig = $this->DATABASE_CONFIG->default;
    $dumpBin = $this->_getSqlBinDir() . "mysqldump";
    $command = "{$dumpBin} --add-drop-table ";
    $command .= "--host={$dbConfig['host']} --user={$dbConfig['login']} ";
    if ($dbConfig['password']) {
      $command .= "--password=" . $dbConfig['password']." ";
    }
    $command .= $dbConfig['database'] . " ";
    $command .= $strBackupTables;
    $command.= " -r " . $backupPath;
    exec($command, $output, $return);
    return TRUE;
  }

  /**
   * import database from sql file
   * 1. create shell command and exec
   * @param string $backupPath backup path
   * @return mixed $ret result after exec command
   */
  private function _importDatabase($backupPath) {
    $this->SystemDatabase->getDataSource();
    $dbConfig = $this->DATABASE_CONFIG->default;
    $dumpBin = $this->_getSqlBinDir() . "mysql";
    $command = "{$dumpBin} --host={$dbConfig['host']} --user={$dbConfig['login']} ";
    if ($dbConfig['password']) {
      $command .= "--password=" . $dbConfig['password'] . " --default-character-set=utf8 ";
    }
    $command .= $dbConfig['database'];
    $command.= " < " . $backupPath;
    exec($command, $output, $return);
    return TRUE;
  }


  public function listBackupDB() {
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $dir = new Folder(APP.'Plugin/System/Data/FullBackupDB/');
    $files = $dir->find('.*\.sql');
    $data = array();
    foreach($files as $file) {
      $tmp = explode('-',str_replace('.sql','',$file));
      $data[$file] = "{$tmp[0]}-{$tmp[1]}-{$tmp[2]} {$tmp[3]}:{$tmp[4]}:{$tmp[5]}";
    }
    krsort($data);
    $this->set('dataList',$data);
  }

  public function addBackupDB() {
    @$this->_backupDatabase(APP.'Plugin/System/Data/FullBackupDB/'.date('Y-m-d-H-i-s').'.sql', true);
    return $this->redirect(Router::url(array('action' => 'listBackupDB')));
  }

  public function deleteBackupDB($file) {
    @unlink(APP.'Plugin/System/Data/FullBackupDB/'.$file);
    $this->Session->setFlash(__('Delete %s successfully',$file), 'flash/success');
    return $this->redirect(Router::url(array('action' => 'listBackupDB')));
  }

  public function rollbackBackupDB($file) {
    $this->_importDatabase(APP.'Plugin/System/Data/FullBackupDB/'.$file);
    $this->Session->setFlash(__('Rollback %s successfully',$file), 'flash/success');
    return $this->redirect(Router::url(array('action' => 'listBackupDB')));
  }

}

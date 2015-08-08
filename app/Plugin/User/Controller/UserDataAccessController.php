<?php

class UserDataAccessController extends AppController {

  var $uses = array('User.UserDataAccess', 'User.UserDataAccessDetail', 'User.UserModel');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'UserDataAccess';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function index($id) {
    $this->set('user', $this->UserModel->findById($id));

    $dataList = $this->UserDataAccess->findAllByUserId($id);
    $this->set('dataList', $dataList);
  }

  public function edit($id) {
    $modelList = array();
    foreach (Configure::read('User.Models') as $model => $value) {
      $modelList[$model] = $value['name'];
    }
    foreach ($this->UserDataAccess->findAllByUserId($id) as $value) {
      unset($modelList[$value['UserDataAccess']['model']]);
    }
    if (!empty($this->request->data)) {
      $this->UserDataAccess->set($this->request->data);
      if ($this->UserDataAccess->validates()) {
        $this->request->data['UserDataAccess']['user_id'] = $id;
        $this->UserDataAccess->save($this->request->data);
        $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'index', $id)));
      }
    }

    $this->set('user', $this->UserModel->findById($id));
    $this->set('modelList', $modelList);
  }

  public function updateList($id) {

    $model = $this->UserDataAccess->findById($id);

    if (isset($this->request->data['addlist'])) {
      foreach ($this->request->data['addlist'] as $data_id => $val) {
        if ($val == 0) {
          continue;
        }
        $data = array();
        $data['data_access_id'] = $id;
        $data['data_id'] = $data_id;
        $this->UserDataAccessDetail->clear();
        $this->UserDataAccessDetail->save($data, false);
      }
    }

    if (isset($this->request->data['deletelist'])) {
      foreach ($this->request->data['deletelist'] as $data_id => $val) {
        if ($val == 0) {
          continue;
        }
        $data = array();
        $data['UserDataAccessDetail.data_access_id'] = $id;
        $data['UserDataAccessDetail.data_id'] = $data_id;
        $this->UserDataAccessDetail->clear();
        $this->UserDataAccessDetail->deleteAll($data);
      }
    }

    $modelList = Configure::read('User.Models');
    $primary = $modelList[$model['UserDataAccess']['model']]['primary'];

    $classModel = ClassRegistry::init($model['UserDataAccess']['model']);
    $className = get_class($classModel);

    $selectedData = $classModel->find('all', array('conditions' => array("$className.$primary IN (SELECT data_id FROM user_data_access_detail WHERE data_access_id = " . Sanitize::escape($id) . ")")));
    $listData = $classModel->find('all', array('conditions' => array("$className.$primary NOT IN (SELECT data_id FROM user_data_access_detail WHERE data_access_id = " . Sanitize::escape($id) . ")")));
    $this->set('selectedData', $selectedData);
    $this->set('listData', $listData);
    $this->set('className', $className);

    $this->set('model', $model);
  }

}

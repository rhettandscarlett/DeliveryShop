<?php

class UserRoleController extends AppController {

  var $uses = array('');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'UserRole';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function index() {
    return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'search')) . '/');
  }

  public function view($id) {
    $data = $this->UserRole->findById($id);
    if (!empty($data['UserRole']['deleted_time'])) {
      $this->Session->setFlash(__('This Role has been deleted'), 'flash/error');
    }
    $this->set('data', $data);
  }

  public function edit($id = 0) {
    if (empty($this->request->data)) {
      $this->request->data = $this->UserRole->findById($id);
    } else {
      $this->UserRole->set($this->request->data);
      if ($this->UserRole->validates()) {
        if (!$this->UserRole->save()) {
          $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
        } else {
          $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
          return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'search')));
        }
      }
    }
  }

  public function delete($id) {
    $this->UserRole->deleteLogic($id);
    return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'search')) . '/');
  }

  public function search() {
    $condition = array();
    $condition['UserRole.deleted_time'] = null;

    $this->set('displayPaging', true);
    $this->Paginator->settings = array(
      'conditions' => $condition,
      'limit' => 50
    );
    $dataList = $this->Paginator->paginate('UserRole');

    $this->set('dataList', $dataList);
  }

}

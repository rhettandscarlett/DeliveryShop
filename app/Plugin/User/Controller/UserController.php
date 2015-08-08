<?php

class UserController extends AppController {

  var $uses = array('User.UserRoleAccess','User.UserRole');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'UserModel';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function index() {

  }

  public function view($id) {

    $data = $this->UserModel->findById($id);
    if (!empty($data['UserModel']['deleted_time'])) {
      $this->Session->setFlash(__('This  has been deleted'), 'flash/error');
    }
    $this->set('data', $data);
  }

  public function edit($id = 0) {

    if (empty($this->request->data)) {
      $this->request->data = $this->UserModel->findById($id);
      unset($this->request->data['UserAccount']['password']);
    } else {

      if (empty($this->request->data['UserAccount']['password'])) {
        unset($this->request->data['UserAccount']['password']);
      } else {
        $this->request->data['UserAccount']['password'] = sha1($this->request->data['UserAccount']['password']);
      }

      if ($this->UserModel->saveAll($this->request->data)) {
        $this->UserModel->addRegisteredRole($this->UserModel->getId());
        $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
        return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'User', 'action' => 'search')));
      }
    }
  }

  public function ed($id = 0) {

    $this->view = 'edit_with_role';
    $this->set('roles', Hash::combine($this->UserRole->find('all'),'{n}.UserRole.id','{n}.UserRole.name'));

    if (empty($this->request->data)) {
      $this->request->data = $this->UserModel->findById($id);
      $roleAccess = $this->UserRoleAccess->findByUserId($id);
      if(!empty($roleAccess)) {
        $this->request->data['UserModel']['role'] = $roleAccess['UserRole']['id'];
      }
      unset($this->request->data['UserAccount']['password']);
    } else {

      if (empty($this->request->data['UserAccount']['password'])) {
        unset($this->request->data['UserAccount']['password']);
      } else {
        $this->request->data['UserAccount']['password'] = sha1($this->request->data['UserAccount']['password']);
      }

      if ($this->UserModel->saveAll($this->request->data)) {
        $this->UserModel->onlyOneRole($this->UserModel->getId(), $this->request->data['UserModel']['role']);
        $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
        return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'User', 'action' => 'listing')));
      }
    }
  }

  public function delete($id) {
    $this->UserModel->deleteLogic($id);
    return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'User', 'action' => 'listing')) . '/');
  }

  public function search() {
    $this->set('selectedRoles', Hash::combine($this->UserRoleAccess->find('all'), '{n}.UserRoleAccess.role_id', '{n}.UserRole.name', '{n}.UserRoleAccess.user_id'));
    $this->set('displayPaging', true);
    $this->Paginator->settings = array(
      'limit' => 10
    );
    $dataList = $this->Paginator->paginate('UserModel');

    $this->set('dataList', $dataList);
  }

  public function listing() {
    $this->set('selectedRoles', Hash::combine($this->UserRoleAccess->find('all'), '{n}.UserRoleAccess.role_id', '{n}.UserRole.name', '{n}.UserRoleAccess.user_id'));
    $this->set('displayPaging', true);
    $this->Paginator->settings = array(
      'limit' => 10
    );
    $dataList = $this->Paginator->paginate('UserModel');

    $this->set('dataList', $dataList);
  }

}

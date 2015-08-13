<?php

class UserAdminController extends AppController {

  var $modelClass = 'UserAdmin';

  public function beforeFilter() {
    parent::beforeFilter();
    $this->layout = 'admin';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function index() {
    return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'search')) . '/');
  }

  public function login($referer = '') {
    if ($this->loggedUser->Admin->id > 0) {
      $this->redirect($this->request->webroot);
    }

    $referer = urldecode($referer);
    $this->layout = "login";
    if (!empty($this->request->data)) {
      $admin = $this->UserAdmin->findByEmail($this->request->data['UserAdmin']['email']);
      $this->setCookie();
      if (empty($admin) || $admin['UserAdmin']['password'] != sha1($this->request->data['UserAdmin']['password'])) {
        $this->Session->setFlash(__('The email or password you entered is incorrect.'), 'flash/error');
      } elseif ($admin['UserAdmin']['status'] == USER_ADMIN_DISABLE) {
        $this->Session->setFlash(__('Your account has been disabled.'), 'flash/error');
      } else {
        $this->loggedUser = new stdClass();
        $this->loggedUser->User = new stdClass();
        $this->loggedUser->User->id = 0;
        unset($admin['UserAdmin']['password']);
        $this->loggedUser->Admin = arrayToObject($admin['UserAdmin']);
        $this->Session->write('loggedUser', $this->loggedUser);
        if(empty($referer)){
          $this->redirect('/admin');
        }else{
          $this->redirect($referer);
        }
      }
    }
  }

  public function logout() {
    $this->Session->destroy();
    $this->Cookie->destroy();
    $this->redirect($this->referer());
  }

  public function view($id) {
    $data = $this->UserAdmin->findById($id);
    if (!empty($data['Object']['deleted_time'])) {
      $this->Session->setFlash(__('This Admin has been deleted'), 'flash/error');
    }
    $this->set('data', $data);
  }

  public function edit($id = 0) {

    if (empty($this->request->data)) {
      $this->request->data = $this->UserAdmin->findById($id);
      unset($this->request->data['UserAdmin']['password']);
    } else {
      if (empty($this->request->data['UserAdmin']['password'])) {
        unset($this->request->data['UserAdmin']['password']);
      } else {
        $this->request->data['UserAdmin']['password'] = sha1($this->request->data['UserAdmin']['password']);
      }
      $this->UserAdmin->set($this->request->data);
      if ($this->UserAdmin->validates()) {
        if (!$this->UserAdmin->save($this->request->data)) {
          $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
        } else {
          $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
          return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'search')));
        }
      } else {
        $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
      }
    }
  }

  public function profile() {
    $id = $this->loggedUser->Admin->id;

    if (empty($this->request->data)) {
      $this->request->data = $this->UserAdmin->findById($id);
      unset($this->request->data['UserAdmin']['password']);
    } else {
      if (empty($this->request->data['UserAdmin']['password'])) {
        unset($this->request->data['UserAdmin']['password']);
      } else {
        $this->request->data['UserAdmin']['password'] = sha1($this->request->data['UserAdmin']['password']);
      }
      $this->UserAdmin->set($this->request->data);
      if ($this->UserAdmin->validates()) {
        if (!$this->UserAdmin->save($this->request->data)) {
          $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
        } else {
          $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
          return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'profile')));
        }
      } else {
        $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
      }
    }
  }

  public function delete($id) {
    if ($id == $this->loggedUser->Admin->id) {
      $this->Session->setFlash(__('Cannot delete yourself.'), 'flash/error');
    } else {
      $this->UserAdmin->deleteLogic($id);
    }

    return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'search')) . '/');
  }

  public function search() {
    $condition = array();
    $condition['UserAdmin.deleted_time'] = null;

    $this->set('displayPaging', true);
    $this->Paginator->settings = array(
      'conditions' => $condition,
      'limit' => 10
    );
    $dataList = $this->Paginator->paginate('UserAdmin');

    $this->set('dataList', $dataList);
  }

}

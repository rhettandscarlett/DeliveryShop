<?php

class UserRoleAccessController extends AppController {

  var $uses = array('User.UserRoleAccess', 'User.UserRoleRight', 'User.UserRole', 'User.UserModel');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'UserRoleAccess';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function editUsers($id) {
    $this->set('role', $this->UserRole->findById($id));

    if (isset($this->request->data['addlist'])) {
      foreach ($this->request->data['addlist'] as $user_id => $val) {
        if ($val == 0) {
          continue;
        }
        $data = array();
        $data['role_id'] = $id;
        $data['user_id'] = $user_id;
        $this->UserRoleAccess->clear();
        $this->UserRoleAccess->save($data, false);
      }
    }

    if (isset($this->request->data['deletelist'])) {
      foreach ($this->request->data['deletelist'] as $user_id => $val) {
        if ($val == 0) {
          continue;
        }
        $data = array();
        $data['UserRoleAccess.role_id'] = $id;
        $data['UserRoleAccess.user_id'] = $user_id;
        $this->UserRoleAccess->clear();
        $this->UserRoleAccess->deleteAll($data);
      }
    }

    $this->set('listUsers', $this->UserModel->find('all', array('order' => array('UserModel.id'), 'conditions' => array('UserModel.id NOT IN (SELECT user_id FROM user_role_access WHERE role_id = ' . Sanitize::escape($id) . ')'))));
    $this->set('selectedUsers', $this->UserRoleAccess->findAllByRoleId($id, null, array('UserRoleAccess.user_id')));
  }

  public function editRoles($id) {
    $this->set('roles', Hash::combine($this->UserRole->find('all'), '{n}.UserRole.id', '{n}.UserRole.name'));
    $this->set('selectedRoles', Hash::combine($this->UserRoleAccess->findAllByUserId($id), '{n}.UserRoleAccess.role_id', '{n}.UserRole.name'));
    $this->set('user', $this->UserModel->findById($id));

    if (count($this->request->data) > 0) {
      $this->UserRoleAccess->updateLinkObjects($id, 'user_id', $this->request->data['rolelist'], 'role_id');
      return $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'User', 'action' => 'search')));
    }
  }

}

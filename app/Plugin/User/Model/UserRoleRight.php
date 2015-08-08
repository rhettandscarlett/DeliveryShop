<?php

App::uses('AppModel', 'Model');

class UserRoleRight extends AppModel {

  var $useTable = 'user_role_right';
  var $multiLanguage = null;
  public $belongsTo = array(
    'UserRole' =>
    array(
      'className' => 'User.UserRole',
      'foreignKey' => 'role_id',
    ),
  );
  public $actsAs = array('MultiLanguage.MultiLanguage');
  var $validate = array(
    'role_id' =>
    array(
      'notNull' =>
      array(
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'This field cannot be left blank',
      ),
      'numeric' =>
      array(
        'rule' => 'numeric',
        'message' => 'Please enter a valid number',
      ),
    ),
    'is_owner' =>
    array(
      'notNull' =>
      array(
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'This field cannot be left blank',
      ),
    ),
  );

  public function getRightByRole($roleId) {
    if (!is_array($roleId)) {
      $roleId = array($roleId);
    }

    $rolesP = array();
    $rolesC = array();
    foreach ($this->find('all', array('conditions' => array("UserRoleRight.role_id IN (" . implode("','", $roleId) . ")"))) as $role) {
      if (!empty($role['UserRoleRight']['plugin'])) {
        if (!empty($role['UserRoleRight']['action'])) {
          $rolesP[$role['UserRoleRight']['plugin']][$role['UserRoleRight']['controller']][$role['UserRoleRight']['action']]['owner'] = $role['UserRoleRight']['is_owner'];
          $rolesP[$role['UserRoleRight']['plugin']][$role['UserRoleRight']['controller']][$role['UserRoleRight']['action']]['id'] = $role['UserRoleRight']['id'];
        } elseif (!empty($role['UserRoleRight']['controller'])) {
          $rolesP[$role['UserRoleRight']['plugin']][$role['UserRoleRight']['controller']]['id'] = $role['UserRoleRight']['id'];
        } else {
          $rolesP[$role['UserRoleRight']['plugin']]['id'] = $role['UserRoleRight']['id'];
        }
      } else {
        if (!empty($role['UserRoleRight']['action'])) {
          $rolesC[$role['UserRoleRight']['controller']][$role['UserRoleRight']['action']]['owner'] = $role['UserRoleRight']['is_owner'];
          $rolesC[$role['UserRoleRight']['controller']][$role['UserRoleRight']['action']]['id'] = $role['UserRoleRight']['id'];
        } elseif (!empty($role['UserRoleRight']['controller'])) {
          $rolesC[$role['UserRoleRight']['controller']]['id'] = $role['UserRoleRight']['id'];
        }
      }
    }
    return array($rolesP, $rolesC);
  }

}

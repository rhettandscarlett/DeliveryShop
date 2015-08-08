<?php

App::uses('Component', 'Controller');

class UserComponent extends Component {

  var $uses = array('User.UserAccount', 'User.UserModel', 'User.UserAdmin', 'User.UserRoleAccess');

  public function initialize(Controller $controller) {
    $controller->loggedUser = $controller->Session->read('loggedUser');

    if (is_null($controller->loggedUser)) {
      $controller->Cookie->domain = env('HTTP_BASE');
      $controller->Cookie->name = 'remember_me';
      $cookie = $controller->Cookie->read('User');
      if (!empty($cookie)) {
        if ($cookie['model_class'] == 'UserAccount') {
          $user_model = ClassRegistry::init('User.UserAccount');
          $user = $user_model->find('first', array(
            'conditions' => array(
              'UserModel.email' => $cookie['email'],
              'UserAccount.password' => $cookie['password'],
              'UserModel.status' => USER_ACTIVE,
            ),
            'multiLanguageIsUsed' => false
          ));
          if ($user) {
            $controller->loggedUser = new stdClass();
            $controller->loggedUser->Admin = new stdClass();
            $controller->loggedUser->Admin->id = 0;
            unset($user['UserAccount']['password']);
            $controller->loggedUser->User = arrayToObject($user['UserModel']);
            $access_model = ClassRegistry::init('User.UserRoleAccess');
            $controller->loggedUser->Role = Hash::combine($access_model->findByUserId($user['UserModel']['id']), 'UserRoleAccess.role_id', 'UserRoleAccess.role_id');
            $controller->Session->write('loggedUser', $this->loggedUser);
          }
        } elseif ($cookie['model_class'] == 'UserAdmin') {
          $admin_model = ClassRegistry::init('User.UserAdmin');
          $admin = $admin_model->find('first', array(
            'conditions' => array(
              'UserAdmin.email' => $cookie['email'],
              'UserAdmin.password' => $cookie['password'],
              'UserAdmin.status' => USER_ADMIN_ACTIVE
            ),
            'multiLanguageIsUsed' => false
          ));
          if ($admin) {
            $controller->loggedUser = new stdClass();
            $controller->loggedUser->User = new stdClass();
            $controller->loggedUser->User->id = 0;
            unset($admin['UserAdmin']['password']);
            $controller->loggedUser->Admin = arrayToObject($admin['UserAdmin']);
            $controller->Session->write('loggedUser', $this->loggedUser);
          }
        }
      }
    }

    if (empty($controller->loggedUser)) {
      $controller->loggedUser = new stdClass();
      $controller->loggedUser->Admin = new stdClass();
      $controller->loggedUser->User = new stdClass();
      $controller->loggedUser->Admin->id = 0;
      $controller->loggedUser->User->id = 0;
    }

    /* Admin has all access */
    if ($controller->loggedUser->Admin->id > 0) {
      return true;
    }

    $classController = get_class($controller);
    $parentController = get_parent_class($controller);

    /* System CakeHandler controller */
    if(in_array($classController,array('CakeErrorController'))) {
      return true;
    }

    /* Verify exclude parent controller */
    $userParentExcludeController = Configure::read('USER_EXCLUDE_PARENT_CONTROLLER');
    if (!empty($controller->plugin) && isset($userParentExcludeController['plugin'][$controller->plugin][$parentController])) {
      return true;
    } elseif (empty($controller->plugin) && isset($userParentExcludeController['controller'][$parentController])) {
      return true;
    }
    /* End of verify exclude parent controller */

    /* Verify exclude controller */
    $accController = null;
    $userExcludeController = Configure::read('USER_EXCLUDE_CONTROLLER');

    if (!empty($controller->plugin)) {
      if (isset($userExcludeController['plugin'][$controller->plugin])) {
        $accPlugin = $userExcludeController['plugin'][$controller->plugin];
        if (count($accPlugin) == 0) {
          return true;
        }
        if (isset($accPlugin[$classController])) {
          $accController = $accPlugin[$classController];
        }
      }
    } else {
      if (isset($userExcludeController['controller'][$classController])) {
        $accController = $userExcludeController['controller'][$classController];
      }
    }
    if (!is_null($accController)) {
      if (count($accController) == 0) {
        return true;
      }
      if (isset($accController[$controller->action])) {
        return true;
      }
    }
    /* End of Verify exclude controller */

    /* Exclude exact URL */
    if (in_array(Router::reverse($controller->request), Configure::read('USER_EXCLUDE_URL'))) {
      return true;
    }

    /* Exclude exact URL Pattern */
    foreach (Configure::read('USER_EXCLUDE_URL_REGEX') as $exculePattern) {
      if (@preg_match($exculePattern, Router::reverse($controller->request))) {
        return true;
      }
    }

    $access = true;
    $roleRight = ClassRegistry::init('User.UserRoleRight');
    if ($controller->loggedUser->User->id == 0) {
      list($rolesP, $rolesC) = $roleRight->getRightByRole(USER_ROLE_ANONYM);
    } else {
      list($rolesP, $rolesC) = $roleRight->getRightByRole($controller->loggedUser->Role);
    }

    if (!empty($controller->plugin)) {
      if (!(isset($rolesP[$controller->plugin][$classController][$controller->action]['id']) ||
        isset($rolesP[$controller->plugin][$classController]['id']) || isset($rolesP[$controller->plugin]['id']))) {
        $access = false;
      }
    } elseif (!(isset($rolesC[$classController][$controller->action]['id']) || isset($rolesC[$classController]['id']))) {
      $access = false;
    }
    if (!$access) {
      if ($controller->loggedUser->User->id == 0) {
        $controller->Session->setFlash(__('You are not authorized to access this page'), 'flash/error');
        $controller->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login')));
      } else {
        $controller->Session->setFlash(__('You are not authorized to access this page'), 'flash/error');
        $controller->redirect('/user/account/login');
      }
    }
  }

  public function startup(Controller $controller) {
  }

  public function beforeRender(Controller $controller) {
    $controller->set('loggedUser', $controller->loggedUser);
  }

  public function shutdown(Controller $controller) {

  }

  public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {

  }

}

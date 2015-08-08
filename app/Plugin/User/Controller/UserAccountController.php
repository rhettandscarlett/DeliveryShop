<?php

class UserAccountController extends AppController {

  var $uses = array('User.UserModel', 'User.UserRoleAccess', 'User.UserAccount', 'EmailTemplate.EmailTemplate');
  public $components = array('Email');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'UserAccount';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function login($referer = '') {
    if ($this->loggedUser->User->id > 0) {
      $this->redirect('/admin');
    }
    $this->layout = "login";

    if (!empty($this->request->data)) {
      $this->setCookie();
      $result = $this->UserModel->verifyLogin($this->request->data['UserModel']['email'], $this->request->data['UserModel']['password']);
      if ($result['status']) {
        $loggedUser = $result['user'];
        $this->Session->write('loggedUser', $loggedUser);
        $this->redirect('/admin');
      } else {
        $this->Session->setFlash($result['message'], 'flash/error');
      }
    }
  }

  public function register() {
    $this->UserAccount->validate['password'] = array(
      'notNull' =>
      array(
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'Password field cannot be left blank',
      ),
      'minLength' =>
      array(
        'rule' => array('minLength', USER_MIN_PASSWORD_LENGTH),
        'message' => 'Minimum ' . USER_MIN_PASSWORD_LENGTH . ' characters long'
      ),
    );
    $this->UserAccount->validate['password_confirmation'] = array(
      'notNull' => array(
        'rule' => 'notEmpty',
        'required' => true,
        'message' => 'Password Confirmation field cannot be left blank',
      ),
      'match_password' => array(
        'rule' => array('isMatchedValidate', 'password'),
        'message' => 'Password does not match the confirmation password',
      ),
    );

    if (empty($this->request->data)) {
      $this->request->data = NULL;
    } else {
      if ($this->UserModel->register($this->request->data)) {
        if (USER_AUTO_ACTIVE == 1) {
          $this->view = 'register_success_non_active';
        } else {
          $this->view = 'register_success_active';
        }
      }
    }
  }

  public function myProfile() {
    if ($this->loggedUser->User->id <= 0) {
      $this->Session->setFlash(__('Please login to continue'), 'flash/error');
      $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login')));
    }

    $this->UserAccount->validate['password_confirmation'] = array(
      'match_password' => array(
        'rule' => array('isMatchedValidate', 'password'),
        'message' => 'Password does not match the confirmation password',
      ),
    );

    if (empty($this->request->data)) {
      $this->request->data = $this->UserModel->findById($this->loggedUser->User->id);
    } else {
      $this->request->data['UserModel']['email'] = !empty($this->loggedUser->User->email) ? $this->loggedUser->User->email : $this->request->data['UserModel']['email'];
      $this->request->data['UserModel']['id'] = $this->loggedUser->User->id;
      if ($this->UserModel->updateProfile($this->request->data)) {
        $this->Session->setFlash(__('Your profile have been saved successfully.'), 'flash/success');
        $this->loggedUser->User->name = $this->request->data['UserModel']['name'];
        $this->Session->write('loggedUser', $this->loggedUser);
      }
    }

    return;
  }

  public function profile() {
    if ($this->loggedUser->User->id <= 0) {
      $this->Session->setFlash(__('Please login to continue'), 'flash/error');
      $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login')));
    }

    $this->UserAccount->validate['password_confirmation'] = array(
      'match_password' => array(
        'rule' => array('isMatchedValidate', 'password'),
        'message' => 'Password does not match the confirmation password',
      ),
    );

    if (empty($this->request->data)) {
      $this->request->data = $this->UserModel->findById($this->loggedUser->User->id);
    } else {
      $this->request->data['UserModel']['email'] = !empty($this->loggedUser->User->email) ? $this->loggedUser->User->email : $this->request->data['UserModel']['email'];
      $this->request->data['UserModel']['id'] = $this->loggedUser->User->id;
      if ($this->UserModel->updateProfile($this->request->data)) {
        $this->Session->setFlash(__('Your profile have been saved successfully.'), 'flash/success');
        $this->loggedUser->User->name = $this->request->data['UserModel']['name'];
        $this->Session->write('loggedUser', $this->loggedUser);
      }
    }

    return;
  }

  public function activate($token = 'token') {
    $userAccount = $this->UserAccount->findByResetTokenPassword($token);
    if (empty($userAccount)) {
      $this->set('message', __('The token is not correct'));
    } else {
      if ($userAccount['UserModel']['status'] != USER_INACTIVE || (time() - strtotime($userAccount['UserAccount']['reset_token_time'])) / 60 > USER_TOKEN_EXPIRE) {
        $this->set('message', __('The token is expired'));
      } else {
        $userAccount['UserModel']['status'] = USER_ACTIVE;
        $this->UserModel->save($userAccount['UserModel']);

        $userAccount['UserAccount']['reset_token_password'] = NULL;
        $this->UserAccount->save($userAccount['UserAccount'], false);
        $this->set('message', __('Your account is activated. Please login to continue.'));
      }
    }
  }

  public function forgotPassword() {
    $this->layout = "login";
    if (!empty($this->request->data)) {
      $data = $this->UserModel->findByEmail($this->request->data['UserModel']['email']);
      if (empty($data)) {
        $this->Session->setFlash(__('The email you entered is not exist'), 'flash/error');
      } else {
        $data['UserAccount']['reset_token_password'] = UtilLib::generateToken();
        $data['UserAccount']['reset_token_time'] = date('Y-m-d H:i:s');
        $this->UserAccount->save($data['UserAccount'], array('callbacks' => false));

        App::uses('CakeEmail', 'Network/Email');

        $Email = new CakeEmail('noreply');
        $noreplyConf = $Email->config();

        $Email->emailFormat('html');
        $Email->template('User.reset_password');

        $Email->viewVars(array(
          'token' => $data['UserAccount']['reset_token_password'],
          'name' => $data['UserModel']['name']
        ));

        $Email->from($noreplyConf['from']);
        $Email->to($data['UserModel']['email']);
        $Email->subject(__('Please activate your account'));
        $Email->send();

        $this->Session->setFlash(__('Recover password link is sent'), 'flash/error');
      }
    }
  }

  public function resetPassword($token = 'token') {
    $this->layout = "login";

    $userAccount = $this->UserAccount->findByResetTokenPassword($token);
    if (empty($userAccount)) {
      $this->Session->setFlash(__('The token is not correct'), 'flash/error');
      $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'forgotPassword')));
    } else {
      if ((time() - strtotime($userAccount['UserAccount']['reset_token_time'])) / 60 > USER_TOKEN_EXPIRE) {
        $this->Session->setFlash(__('The token is expired'), 'flash/error');
        $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'forgotPassword')));
      } elseif (!empty($this->request->data)) {
        $this->UserModel->validate = array();
        $this->UserModel->validate['password'] = array(
          'notNull' =>
          array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Password field cannot be left blank',
          ),
          'minLength' =>
          array(
            'rule' => array('minLength', USER_MIN_PASSWORD_LENGTH),
            'message' => 'Minimum ' . USER_MIN_PASSWORD_LENGTH . ' characters long'
          ),
        );
        $this->UserModel->validate['password_confirmation'] = array(
          'notNull' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Password Confirmation field cannot be left blank',
          ),
          'match_password' => array(
            'rule' => array('isMatchedValidate', 'password'),
            'message' => 'Password does not match the confirmation password',
          ),
        );
        $this->UserModel->set($this->request->data);
        if ($this->UserModel->validates()) {
          $userAccount['UserAccount']['reset_token_password'] = NULL;
          $userAccount['UserAccount']['password'] = sha1($this->request->data['UserModel']['password']);
          $this->UserAccount->save($userAccount['UserAccount'], false);
          $this->Session->setFlash(__('Your password have been changed.'), 'flash/success');
          $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login')));
        }
      }
    }
  }

  public function loginViaFacebook() {
    
  }

  public function loginViaTwitter() {
    
  }

  public function loginViaGoogle() {
    
  }

  public function logout() {
    $this->Session->destroy();
    $this->Cookie->destroy();
//    $this->redirect($this->referer());
    $this->redirect('/');
  }

}

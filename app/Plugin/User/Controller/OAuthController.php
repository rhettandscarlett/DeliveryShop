<?php

class OAuthController extends AppController {

  var $uses = array('User.UserModel', 'User.UserRoleAccess', 'User.UserAccount');

  public function fbLogin($referer = '') {
    if ($this->loggedUser->User->id > 0) {
      $this->redirect($this->request->webroot);
    }
    if (empty($referer)) {
      $referer = $this->request->webroot;
    } else {
      $referer = urldecode($referer);
    }
    require_once App::pluginPath('User') . "Vendor" . DS . "Facebook" . DS . "facebook.php";
    $facebook = new Facebook(array(
      'appId' => FACEBOOK_LOGIN_APPID,
      'secret' => FACEBOOK_LOGIN_SECRET,
    ));
    $user = $facebook->getUser();
    if ($user) {
      try {
        $userProfile = $facebook->api('/me');
        if (isset($userProfile['id'])) {
          $dbUser = $this->UserAccount->findByOauthUidAndOauthProvider($userProfile['id'], FACEBOOK_LOGIN_PROVIDER);
          if ($dbUser) {//if user in yte system
            $this->_setLoggedUser($dbUser);
            $this->redirect(Router::url('/', true));
          } else {//user not in yte sytem
            $data = $this->_getUserFromProvider($userProfile, FACEBOOK_LOGIN_PROVIDER);
            $userId = $this->UserModel->OAuthRegister($data);
            $dbUser = $this->UserModel->findById($userId);
            $isSetSuccess = $this->_setLoggedUser($dbUser);
            if ($isSetSuccess) {
              $this->redirect($referer);
            } else {
              $this->Session->setFlash(__("Cannot login via Facebook"), 'flash/error');
              $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
            }
          }
        } else {
          $this->Session->setFlash(__("Cannot get your information"), 'flash/error');
          $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
        }
      } catch (FacebookApiException $e) {
        $user = NULL;
      }
    }
    if (empty($user)) {
      //login url
      $loginurl = $facebook->getLoginUrl(array(
        'scope' => 'email, read_stream, publish_stream, user_birthday',
        'redirect_uri' => FACEBOOK_LOGIN_REDIRECT_URI,
      ));
      header('Location: ' . $loginurl);
    }
    exit;
  }

  public function googleLogin() {
    if ($this->loggedUser->User->id > 0) {
      $this->redirect($this->request->webroot);
    }
    if (empty($referer)) {
      $referer = $this->request->webroot;
    } else {
      $referer = urldecode($referer);
    }
    $pluginPath = App::pluginPath('User') . "Vendor";
    set_include_path($pluginPath . PATH_SEPARATOR . get_include_path());
    require_once $pluginPath . '/Google/Client.php';
    require_once $pluginPath . '/Google/Service/Plus.php';
    $client = new Google_Client();
    $client->setClientId(GOOGLE_LOGIN_APPID);
    $client->setClientSecret(GOOGLE_LOGIN_SECRET);
    $client->setRedirectUri(GOOGLE_LOGIN_REDIRECT_URI);
    $client->addScope("https://www.googleapis.com/auth/userinfo.email");
    $client->addScope("https://www.googleapis.com/auth/plus.login");
    $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
    $client->addScope("https://www.googleapis.com/auth/plus.profile.emails.read");
    $authUrl = '';
    if (isset($_GET['code'])) {
      $client->authenticate($_GET['code']);
      $googlePlus = new Google_Service_Plus($client);
      $userProfile = $googlePlus->people->get('me');
      if (!empty($userProfile)) {
        $dbUser = $this->UserAccount->findByOauthUidAndOauthProvider($userProfile->id, GOOGLE_LOGIN_PROVIDER);
        if ($dbUser) {
          $this->_setLoggedUser($dbUser);
          $this->redirect(Router::url('/', true));
        } else {
          $data = $this->_getUserFromProvider($userProfile, GOOGLE_LOGIN_PROVIDER);
          $userId = $this->UserModel->OAuthRegister($data);
          $dbUser = $this->UserModel->findById($userId);
          $isSetSuccess = $this->_setLoggedUser($dbUser);
          if ($isSetSuccess) {
            $this->redirect($referer);
          } else {
            $this->Session->setFlash(__("Cannot login via Google Plus"), 'flash/error');
            $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
          }
        }
      } else {
        $this->Session->setFlash(__("Cannot get your information"), 'flash/error');
        $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
      }
    } else {
      $authUrl = $client->createAuthUrl();
    }
    if ($authUrl) {
      header('Location: ' . $authUrl);
    }
    exit();
  }

  public function twitterLogin() {
    if ($this->loggedUser->User->id > 0) {
      $this->redirect($this->request->webroot);
    }
    if (empty($referer)) {
      $referer = $this->request->webroot;
    } else {
      $referer = urldecode($referer);
    }
    $pluginPath = App::pluginPath('User') . "Vendor";
    require_once $pluginPath . '/twitteroauth/twitteroauth.php';
    if (isset($_REQUEST['oauth_token']) && $this->Session->read('token') !== $_REQUEST['oauth_token']) {
      session_destroy();
      $this->redirect(Router::url('/', true));
    } elseif (isset($_REQUEST['oauth_token']) && $this->Session->read('token') == $_REQUEST['oauth_token']) {
      $connection = new TwitterOAuth(
        TWITTER_API_KEY, TWITTER_API_SECRET, $this->Session->read('token'), $this->Session->read('token_secret')
      );
      $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
      if ($connection->http_code == '200') {
        $userProfile = $connection->get('account/verify_credentials');
        $this->Session->delete('token');
        $this->Session->delete('token_secret');
        if ($userProfile && isset($userProfile->id)) {
          $dbUser = $this->UserAccount->findByOauthUidAndOauthProvider($userProfile->id, TWITTER_LOGIN_PROVIDER);
          if ($dbUser) {//if user in yte system
            $this->_setLoggedUser($dbUser);
            $this->redirect(Router::url('/', true));
          } else {
            $data = $this->_getUserFromProvider($userProfile, TWITTER_LOGIN_PROVIDER);
            $userId = $this->UserModel->OAuthRegister($data);
            $dbUser = $this->UserModel->findById($userId);
            $isSetSuccess = $this->_setLoggedUser($dbUser);
            if ($isSetSuccess) {
              $this->redirect($referer);
            } else {
              $this->Session->setFlash(__("Cannot login via Twitter"), 'flash/error');
              $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
            }
          }
        }
      } else {
        $this->Session->setFlash(__("error, try again later!"), 'flash/error');
        $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
      }
    } else {
      $connection = new TwitterOAuth(TWITTER_API_KEY, TWITTER_API_SECRET);
      $request_token = $connection->getRequestToken(TWITTER_OAUTH_CALLBACK);
      //received token info from twitter
      $this->Session->write('token', $request_token['oauth_token']);
      $this->Session->write('token_secret', $request_token['oauth_token_secret']);
      // any value other than 200 is failure, so continue only if http code is 200
      if ($connection->http_code == '200') {
        $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
        header('Location: ' . $twitter_url);
      } else {
        $this->Session->setFlash(__("error connecting to twitter! try again later"), 'flash/error');
        $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'login'), true));
      }
    }
    exit();
  }

  private function _getUserFromProvider($userProfile, $provider = FACEBOOK_LOGIN_PROVIDER) {
    $data = array();
    switch ($provider) {
      case FACEBOOK_LOGIN_PROVIDER:
        $data = array();
        $data['UserModel']['email'] = NULL;
        $data['UserModel']['username'] = NULL;
        $data['UserModel']['name'] = $userProfile['name'];
        $data['UserModel']['firstname'] = $userProfile['first_name'];
        $data['UserModel']['lastname'] = $userProfile['last_name'];
        $data['UserModel']['address'] = isset($userProfile['location']['name']) ? $userProfile['location']['name'] : '';
        $data['UserAccount']['password'] = $provider . "_" . sha1(time());
        $data['UserAccount']['password_hint'] = "Login via " . $provider;
        $data['UserAccount']['oauth_uid'] = $userProfile['id'];
        $data['UserAccount']['oauth_provider'] = $provider;
        $jsonDataArr = $data['UserModel'];
        $jsonDataArr['email'] = $userProfile['email'];
        $jsonDataArr['username'] = $userProfile['username'];
        $jsonData = json_encode($jsonDataArr);
        $data['UserAccount']['oauth_data'] = $jsonData;
        break;
      case GOOGLE_LOGIN_PROVIDER:
        $data = array();
        $nameArr = $userProfile->getName();
        $data['UserModel']['email'] = NULL;
        $data['UserModel']['username'] = NULL;
        $data['UserModel']['name'] = $userProfile->displayName;
        $data['UserModel']['firstname'] = $nameArr['familyName'];
        $data['UserModel']['lastname'] = $nameArr['givenName'];
        $data['UserModel']['address'] = $userProfile->currentLocation;
        $data['UserAccount']['password'] = $provider . "_" . sha1(time());
        $data['UserAccount']['password_hint'] = "Login via " . $provider;
        $data['UserAccount']['oauth_uid'] = $userProfile->id;
        $data['UserAccount']['oauth_provider'] = $provider;
        $jsonDataArr = $data['UserModel'];
        $emails = $userProfile->getEmails();
        $email = $emails ? $emails[0]['value'] : '';
        $jsonDataArr['email'] = $email;
        $jsonDataArr['username'] = $email;
        $jsonData = json_encode($jsonDataArr);
        $data['UserAccount']['oauth_data'] = $jsonData;
        break;
      case TWITTER_LOGIN_PROVIDER:
        $data = array();
        $data['UserModel']['email'] = NULL;
        $data['UserModel']['username'] = NULL;
        $data['UserModel']['name'] = $userProfile->name;
        $data['UserModel']['firstname'] = '';
        $data['UserModel']['lastname'] = '';
        $data['UserModel']['address'] = $userProfile->location;

        $data['UserAccount']['password'] = $provider."_" . sha1(time());
        $data['UserAccount']['password_hint'] = "Login via ".$provider;
        $data['UserAccount']['oauth_uid'] = $userProfile->id;
        $data['UserAccount']['oauth_provider'] = $provider;

        $jsonDataArr = $data['UserModel'];
        $jsonDataArr['username'] = $userProfile->screen_name;
        $jsonData = json_encode($jsonDataArr);
        $data['UserAccount']['oauth_data'] = $jsonData;
        break;
      default:
        break;
    }
    return $data;
  }

  private function _setLoggedUser($user) {
    if ($user) {
      $loggedUser = new stdClass();
      unset($user['UserAccount']['password']);
      $loggedUser->Admin = new stdClass();
      $loggedUser->Admin->id = 0;
      $loggedUser->User = arrayToObject($user['UserModel']);

      $roles = new UserRoleAccess();
      $loggedUser->Role = Hash::combine($roles->findByUserId($user['UserAccount']['user_id']), 'UserRoleAccess.role_id', 'UserRoleAccess.role_id');
      $this->Session->write('loggedUser', $loggedUser);
      return true;
    }
    return false;
  }

}

?>
<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('AppModel', 'Model');
App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');
App::uses('SFRouter', 'Lib');
App::uses('DeliLib', 'Lib');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

  /**
   * Components
   *
   * @var array
   * @access public
   */
  public $helpers = array(
    'Html' => array(
      'className' => 'SFHtml',
      'configFile' => 'sfinput'
    ),
    'Form' => array(
      'className' => 'SFForm'
    ),
    'Paginator' => array(
      'className' => 'SFPaginator'
    ),
    //'Session',
    'Text',
    'Number',
    'Js',
    'Blocks.Elements',
    'File.File',
  );
  public $components = array(
    'Session' => array(
      'className' => 'SFSession'
    ),
    'Cookie',
    'RequestHandler',
    'Blocks.Blocks',
    'User.User',
    'Paginator',
  );
  public $uses = array(
    'File.FileModel'
  );
  public $theme = "DeliveryShop";
  var $jsResponse = array();

  function beforeFilter() {
    parent::beforeFilter();
    $js = array();
    $js['data'] = json_encode(array());
    $js['messages'] = array(
      "error_title" => __("Error!!!"),
      "success_title" => __("Success!!!"),
      "hint_text" => __("Input keyword for searching"),
      "no_results_text" => __("No data"),
      "searching_text" => __("Searching...")
    );
    $menuList = array(
      'homepage' => __('HomePage'),
      'tracking' => __('Tracking'),
      'service' => __('Service'),
      'introduce' => __('About Us'),
      'contact' => __('Contact'),
    );
    $this->set('menuList', $menuList);
    $this->set('currentPage', $this->params->url);
    $this->set('js', $js);
    $this->layout();
  }

  function beforeRender() {
    parent::beforeRender();
    $this->set('controller', $this);
    $this->setHeadTitle();
  }

  function setHeadTitle() {
    $this->set('title_for_layout', __("PTI Express Website"));

    switch ($this->params['controller']) {
      
    }
  }

  function afterFilter() {
    parent::afterFilter();
    if (isset($_SERVER['HTTP_SF_AJAX_HEADER']) && $_SERVER['HTTP_SF_AJAX_HEADER'] == 'sfDialog') {
      unset($this->viewVars['controller']);
      $scripts = isset($this->jsResponse['script']) ? $this->jsResponse['script'] : array();

      $this->jsResponse = array();
      $this->jsResponse['id_display']['sfDialogModel_title'] = isset($this->viewVars['title']) ? $this->viewVars['title'] : '';
      $this->jsResponse['id_display']['sfDialogModel_body'] = $this->render();
      $this->jsResponse['script'][] = '$("#sfDialogModel").modal("show")';
      $this->jsResponse['script'][] = '$("#sfDialogModel").scrollTop(0);';
      foreach ($scripts as $script) {
        $this->jsResponse['script'][] = $script;
      }
      $this->renderJsResponse();
    } elseif (count($this->jsResponse) > 0) {
      $this->renderJsResponse();
    }
  }

  protected function responseJson($data) {
    $this->autoRender = false;
    $this->response->type('json');
    $this->response->body(json_encode($data));
  }

  protected function renderJsResponse() {
    foreach ($this->jsResponse as $key => $value) {
      switch ($key) {
        case 'id_display':
          foreach ($value as $key1 => $value1) {
            $this->jsResponse[$key][$key1] = base64_encode($value1);
          }
          break;
        case 'id_append':
          foreach ($value as $key1 => $value1) {
            $this->jsResponse[$key][$key1] = base64_encode($value1);
          }
          break;
        case 'id_error':
          foreach ($value as $key1 => $value1) {
            $this->jsResponse[$key][$key1] = base64_encode($value1);
          }
          break;
        case 'modal_dialog':
          foreach ($value as $key1 => $value1) {
            if ($key1 == 'title' || $key1 == 'content') {
              $this->jsResponse[$key][$key1] = base64_encode($value1);
            }
          }
          break;
        default :
          break;
      }
    }
    echo json_encode($this->jsResponse);
    die;
  }

  protected function responseCSV($data, $options = array()) {
    $file_name = "CMS-Translation.csv";
    if (isset($options['file_name'])) {
      $file_name = $options['file_name'];
    }
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header("Content-Disposition: attachment;filename={$file_name}");
    header('Cache-Control: max-age=0');

    echo $data;
    exit;
  }

  protected function setCookie($options = array(), $cookieKey = 'User') {
    if (empty($this->request->data[$this->modelClass]['remember_me'])) {
      $this->Cookie->delete($cookieKey);
    } else {
      $validProperties = array('domain', 'key', 'name', 'path', 'secure', 'time');
      $defaults = array('name' => 'remember_me');

      $options = array_merge($defaults, $options);
      foreach ($options as $key => $value) {
        if (in_array($key, $validProperties)) {
          $this->Cookie->{$key} = $value;
        }
      }

      $cookieData = array(
        'model_class' => $this->modelClass,
        'email' => $this->request->data[$this->modelClass]['email'],
        'password' => sha1($this->request->data[$this->modelClass]['password'])
      );
      $this->Cookie->write($cookieKey, $cookieData, true, '1 Month');
    }
    unset($this->request->data[$this->modelClass]['remember_me']);
  }

  public function beforeRedirect($url, $status = null, $exit = true) {
    $this->Session->write('SFRedirectParams', array(
      'status' => TRUE,
      'plugin' => $this->params['plugin'],
      'controller' => $this->params['controller'],
      'action' => $this->params['action'],
    ));
  }

  public function layout($layout = null, $prefix = null) {
    if (!empty($layout)) {
      $this->layout = $layout;
      if (!empty($prefix)) {
        $this->set('prefix', $prefix);
      } else {
        $this->set('prefix', 'admin');
      }
    } else {
      if (preg_match('/^admin/', $this->params->url)) {
        $this->layout = "admin";
      } else {
        $this->layout = "default";
      }
    }
  }

  public function canAccess() {
    
  }

}

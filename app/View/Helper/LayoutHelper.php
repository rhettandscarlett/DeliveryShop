<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Layout Helper
 */
class LayoutHelper extends AppHelper {

  public $helpers = array(
    'Html',
    'Form',
    'Session',
    'Js',
  );
  public $coreHelpers = array(
    // CakePHP
    'Ajax',
    'Cache',
    'Form',
    'Html',
    'Javascript',
    'JqueryEngine',
    'Js',
    'MootoolsEngine',
    'Number',
    'Paginator',
    'PrototypeEngine',
    'Rss',
    'Session',
    'Text',
    'Time',
    'Xml',
    'Layout',
  );

  public function __get($name) {
    switch ($name) {
      default:
        return parent::__get($name);
    }
  }

  public function __set($name, $val) {
    switch ($name) {
      default:
        return parent::__set($name, $val);
    }
  }

  public function js() {
    $js = array();
    if (isset($this->params['locale'])) {
      $js['basePath'] = Router::url('/' . $this->params['locale'] . '/');
    } else {
      $js['basePath'] = Router::url('/');
    }
    $js['params'] = array(
      'plugin' => $this->params['plugin'],
      'controller' => $this->params['controller'],
      'action' => $this->params['action'],
      'named' => $this->params['named'],
    );
    $js['datatable'] = array(
      'sLengthMenu' => '_MENU_ ' . __('Items per page'),
      'sInfo' => '_START_ - _END_ / _TOTAL_',
      'sInfoEmpty' => '0 - 0 / 0',
      'sSearch' => __('Search'),
      'sAll' => __('All'),
      'sZeroRecords' => __('No data available in table'),
      'oPaginate' => array(
        'sPrevious' => __('Back'),
        'sNext' => __('Next')
      )
    );
    if (is_array(Configure::read('Js'))) {
      $js = Hash::merge($js, Configure::read('Js'));
    }
    return $this->Html->scriptBlock('var sf = ' . $this->Js->object($js) . ';');
  }

  public function status($value) {
    if ($value == 1) {
      $icon = 'ok';
      $class = 'green';
    } else {
      $icon = 'remove';
      $class = 'red';
    }
    if (method_exists($this->Html, 'icon')) {
      return $this->Html->icon($icon, compact('class'));
    } else {
      if (empty($this->_View->SFHtml)) {
        $this->_View->Helpers->load('SFHtml');
      }
      return $this->_View->SFHtml->icon($icon, compact('class'));
    }
  }

  public function displayField($item, $model, $field, $options = array()) {
    extract(array_intersect_key($options, array(
      'type' => null,
      'url' => array(),
      'options' => array(),
    )));
    switch ($type) {
      case 'boolean':
        $out = $this->status($item[$model][$field]);
        break;
      default:
        $out = h($item[$model][$field]);
        break;
    }

    if (!empty($url)) {
      if (isset($url['pass'])) {
        $passVars = is_string($url['pass']) ? array($url['pass']) : $url['pass'];
        foreach ($passVars as $passField) {
          $url[] = $item[$model][$passField];
        }
        unset($url['pass']);
      }

      if (isset($url['named'])) {
        $namedVars = is_string($url['named']) ? array($url['named']) : $url['named'];
        foreach ($namedVars as $namedField) {
          $url[$namedField] = $item[$model][$namedField];
        }
        unset($url['named']);
      }

      $out = $this->Html->link($out, $url, $options);
    }
    return $out;
  }

  public function sessionFlash() {
    $messages = $this->Session->read('Message');
    $output = '';
    if (is_array($messages)) {
      $sfkey = sprintf("%s-%s-%s", $this->params['plugin'], $this->params['controller'], $this->params['action']);
      $sfRedirect = (array) CakeSession::read('SFRedirectParams');
      if (isset($sfRedirect['status']) && $sfRedirect['status'] == TRUE) {
        $sfkey = sprintf("%s-%s-%s", $sfRedirect['plugin'], $sfRedirect['controller'], $sfRedirect['action']);
      }
      foreach (array_keys($messages) as $key) {
        if ($key === $sfkey) {
          $output .= $this->Session->flash($key);
        }
      }
    }
    CakeSession::write('SFRedirectParams', null);
    return $output;
  }

  public function isLoggedIn() {
    if ($this->Session->check('Auth.User.id')) {
      return true;
    } else {
      return false;
    }
  }

  public function filter($content) {
    $view = $this->_View;
    $event = new CakeEvent('Helper.Layout.beforeFilter', $view, array('content' => &$content));
    $view->getEventManager()->dispatch($event);
    //$content = htmlspecialchars($content);
    return $content;
  }

  public function getUser($field = '') {
    $view = $this->_View;
    $loggedUser = $view->viewVars['loggedUser'];

    $user = $loggedUser->User;
    if ((int) $loggedUser->Admin->id > 0) {
      $user = $loggedUser->Admin;
      $user->firstname = $user->name;
      $user->lastname = "";
    }
    $output = "";
    switch ($field) {
      case "fullname":
        $output = $user->firstname . " " . $user->lastname;
        break;
      default:
        if (isset($user->$field)) {
          $output = $user->$field;
        }
    }

    return $output;
  }

  public function generateTree($categories, $parent_id = 0, $url = array()) {
    $output = "<ul>";
    foreach ($categories as $category) {
      if ($category->parent_id == $parent_id) {
        $url['id'] = $category->id;
        $icon = (!isset($category->end) || $category->end == 1) ? "" : "<i data-id=\"{$category->id}\" class=\"fa fa-minus-square\"></i>";
        $output .= sprintf("<li><span>%s <a data-id=\"%d\" href=\"%s\">%s</a></span>", $icon, $category->id, Router::url($url), $category->name);
        $output .= self::generateTree($categories, $category->id);
        $output .= "</li>";
      }
    }
    $output .= "</ul>";

    return $output;
  }

}

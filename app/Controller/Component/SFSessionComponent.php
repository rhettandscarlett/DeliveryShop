<?php

App::uses('SessionComponent', 'Controller/Component');

class SFSessionComponent extends SessionComponent {

  public function initialize(Controller $controller) {
    $this->controller = $controller;
  }

  public function __call($name, $args) {
    $method = $name;
    $key = 'flash';

    if (strpos($method, 'form_') === 0) {
      $key = 'form';
      $method = preg_replace('/^form_/', '', $method);
    }

    if (strpos($method, 'alert_') === 0) {
      $method = preg_replace('/^alert_/', '', $method);
    }

    $class = null;
    if (in_array($method, array('success', 'danger', 'error', 'info'))) {
      $class = 'alert-' . $method;
    }

    if ($class !== null || $method === 'alert') {
      $plugin = 'TwitterBootstrap';
      return $this->setFlash($args[0], 'alert', compact('plugin', 'class'), $key);
    }

    throw new BadMethodCallException("Method '{$name}' does not exist.");
  }

  public function setFlash($message, $element = 'default', $params = array(), $key = 'flash') {
    if ($key == 'flash') {
      $params = $this->controller->params->params;
      $key = sprintf("%s-%s-%s", $params['plugin'], $params['controller'], $params['action']);
    }
    parent::setFlash($message, $element, $params, $key);
  }

}

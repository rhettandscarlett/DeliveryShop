<?php

App::uses('Component', 'Controller');

class MultiLanguageComponent extends Component {

  public function initialize(Controller $controller) {
    if (CakeSession::check('Config.language')) {
      Configure::write('Config.language', CakeSession::read('Config.language'));
    }
    if (CakeSession::check('Config.language')) {
      Configure::write('Config.language', CakeSession::read('Config.language'));
    }
  }

}

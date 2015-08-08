<?php

/**
 * @property DeliLocationController $DeliLocationController
 *
 */

class DeliLocationController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliLocation';
  }

  public function index() {

  }


} 
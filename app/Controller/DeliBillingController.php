<?php

/**
 * @property DeliBillingController $DeliBillingController
 *
 */

class DeliBillingController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliBilling';
  }

  public function index() {

  }


} 
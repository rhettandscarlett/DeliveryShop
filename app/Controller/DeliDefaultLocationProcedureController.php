<?php

/**
 * @property DeliDefaultLocationProcedureController $DeliDefaultLocationProcedureController
 *
 */

class DeliDefaultLocationProcedureController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliDefaultLocationProcedure';
  }

  public function index() {

  }


} 
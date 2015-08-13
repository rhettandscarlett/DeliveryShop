<?php

/**
 * @property DeliBillingRuntimeProcedureController $DeliBillingRuntimeProcedureController
 *
 */

class DeliBillingRuntimeProcedureController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliBillingRuntimeProcedure';
  }

  public function index() {

  }


} 
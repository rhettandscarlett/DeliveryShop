<?php

/**
 * @property DeliDefaultSettingController $DeliDefaultSettingController
 *
 */

class DeliDefaultSettingController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliDefaultSetting';
  }

  public function index() {

  }


} 
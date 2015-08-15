<?php

/**
 * @property DeliBillingRuntimeProcedureController $DeliBillingRuntimeProcedureController
 * @property DeliBillingRuntimeProcedure $DeliBillingRuntimeProcedure
 * @property DeliLocation $DeliLocation
 * @property DeliSchedule $DeliSchedule
 */

class DeliBillingRuntimeProcedureController extends AppController {
  public $uses = array(
    'DeliLocation',
    'DeliSchedule',
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliBillingRuntimeProcedure';
  }

  public function index() {

  }

  public function edit($id = 0) {
    $this->set('scheduleList', $this->DeliSchedule->find('list'));

    if (empty($this->request->data)) {
      $this->request->data = $this->DeliLocation->findById($id);
    } else {
      $this->DeliLocation->set($this->request->data);
      if (!$this->DeliLocation->save()) {
        $this->Session->setFlash(__('Cannot save your data'), 'flash/error');
      } else {
        $this->Session->setFlash(__('Your data is saved successfully'), 'flash/success');
        $this->redirect(Router::url(array('controller' => 'DeliLocation', 'action' => 'index')).buildQueryString());
      }

    }
  }


} 
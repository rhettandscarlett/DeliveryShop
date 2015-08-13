<?php

/**
 * @property DeliScheduleController $DeliScheduleController
 * @property DeliSchedule $DeliSchedule
 *
 */

class DeliScheduleController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliSchedule';
  }

  public function index() {
    $dataList = $this->DeliSchedule->find('all');
    $this->set('dataList', $dataList);
  }

  public function edit($id = 0) {
    if (empty($this->request->data)) {
      $this->request->data = $this->DeliSchedule->findById($id);
    } else {
      $this->DeliSchedule->set($this->request->data);
      if (!$this->DeliSchedule->save()) {
        $this->Session->setFlash(__('Cannot save your data'), 'flash/error');
      } else {
        $this->Session->setFlash(__('Your data is saved successfully'), 'flash/success');
        $this->redirect(Router::url(array('controller' => 'DeliSchedule', 'action' => 'index')).buildQueryString());
      }

    }
  }


} 
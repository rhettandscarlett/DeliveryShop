<?php

/**
 * @property DeliLocationController $DeliLocationController
 * @property DeliLocation $DeliLocation
 * @property DeliSchedule $DeliSchedule
 *
 */

class DeliLocationController extends AppController {
  public $uses = array(
    'DeliLocation',
    'DeliSchedule',
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliLocation';
    $this->set('listTimeZone', DateTimeZone::listIdentifiers(DateTimeZone::ALL));
  }

  public function index() {
    $dataList = $this->DeliLocation->find('all', array('order' => array('DeliLocation.order ASC', 'DeliLocation.id ASC')));
    $this->set('dataList', $dataList);
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

  public function delete($id) {
    $this->DeliLocation->deleteLogic($id);
    $this->Session->setFlash(__('Location #%s is deleted successfully', $id), 'flash/success');
    $this->redirect(Router::url(array(
        'controller' => 'DeliLocation',
        'action'     => 'index'
      )) . buildQueryString());
  }



} 
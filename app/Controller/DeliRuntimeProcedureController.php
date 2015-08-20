<?php

/**
 * @property DeliRuntimeProcedureController $DeliRuntimeProcedureController
 * @property DeliRuntimeProcedure $DeliRuntimeProcedure
 * @property DeliLocation $DeliLocation
 */

class DeliRuntimeProcedureController extends AppController {
  public $uses = array(
    'DeliLocation',
    'DeliSchedule',
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliRuntimeProcedure';
    $this->set('locationList', $this->DeliLocation->find('list', array('conditions' => array('DeliLocation.is_default' => false))));
  }

  public function index() {
    $filterId = isset($_GET['filterId']) ? $_GET['filterId'] : 0;
    $condition = array();
    $condition['DeliRuntimeProcedure.deleted_time'] = null;
    if ($filterId) {
      $condition['DeliRuntimeProcedure.location_id'] = $filterId;
    }
    $js['curLink'] = Router::url(array('action' => 'index'), true);
    if (is_array(Configure::read('Js'))) {
      $js = Hash::merge($js, Configure::read('Js'));
    }
    Configure::write('Js', $js);
    $dataList = $this->DeliRuntimeProcedure->find('all', array('conditions' => $condition, 'order' => array('DeliRuntimeProcedure.order ASC', 'DeliLocation.order ASC')));
    $this->set(compact('dataList', 'filters', 'filterId'));
  }

  public function edit($id = 0) {
    $plusDay = array();
    for ($i = 0; $i <= 10; $i++) {
      $plusDay[$i] = $i;
    }
    $this->set('plusDay', $plusDay);

    if (empty($this->request->data)) {
      $this->request->data = $this->DeliRuntimeProcedure->findById($id);
    } else {
      $this->DeliRuntimeProcedure->set($this->request->data);
      if (!$this->DeliRuntimeProcedure->save()) {
        $this->Session->setFlash(__('Cannot save your data'), 'flash/error');
      } else {
        $this->Session->setFlash(__('Your data is saved successfully'), 'flash/success');
        $this->redirect(Router::url(array('controller' => 'DeliRuntimeProcedure', 'action' => 'index')).buildQueryString());
      }

    }
  }

  public function delete($id) {
    $this->DeliRuntimeProcedure->deleteLogic($id);
    $this->Session->setFlash(__('Runtime Procedure #%s is deleted successfully', $id), 'flash/success');
    $this->redirect(Router::url(array(
        'controller' => 'DeliRuntimeProcedure',
        'action'     => 'index'
      )) . buildQueryString());
  }



} 
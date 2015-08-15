<?php

/**
 * @property DeliDefaultLocationProcedureController $DeliDefaultLocationProcedureController
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 * @property DeliLocation $DeliLocation
 *
 */

class DeliDefaultLocationProcedureController extends AppController {

  public $uses = array(
    'DeliLocation',
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliDefaultLocationProcedure';
  }

  public function index() {
    $dataList = $this->DeliDefaultLocationProcedure->find('all', array('order' => array('DeliDefaultLocationProcedure.order ASC', 'DeliLocation.order ASC')));
    $this->set('dataList', $dataList);
  }

  public function edit($id = 0) {
    $this->set('locationList', $this->DeliLocation->find('list'));
    $plusDay = array();
    for ($i = 0; $i <= 10; $i++) {
      $plusDay[$i] = $i;
    }
    $this->set('plusDay', $plusDay);

    if (empty($this->request->data)) {
      $this->request->data = $this->DeliDefaultLocationProcedure->findById($id);
    } else {
      $this->DeliDefaultLocationProcedure->set($this->request->data);
      if (!$this->DeliDefaultLocationProcedure->save()) {
        $this->Session->setFlash(__('Cannot save your data'), 'flash/error');
      } else {
        $this->Session->setFlash(__('Your data is saved successfully'), 'flash/success');
        $this->redirect(Router::url(array('controller' => 'DeliDefaultLocationProcedure', 'action' => 'index')).buildQueryString());
      }

    }
  }

  public function delete($id) {
    $this->DeliDefaultLocationProcedure->deleteLogic($id);
    $this->Session->setFlash(__('Procedure #%s is deleted successfully', $id), 'flash/success');
    $this->redirect(Router::url(array(
        'controller' => 'DeliDefaultLocationProcedure',
        'action'     => 'index'
      )) . buildQueryString());
  }




} 
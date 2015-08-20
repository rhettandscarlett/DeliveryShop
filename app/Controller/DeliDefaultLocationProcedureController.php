<?php

/**
 * @property DeliDefaultLocationProcedureController $DeliDefaultLocationProcedureController
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 * @property DeliSchedule $DeliSchedule
 * @property DeliLocation $DeliLocation
 *
 */

class DeliDefaultLocationProcedureController extends AppController {

  public $uses = array(
    'DeliSchedule',
    'DeliLocation',
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliDefaultLocationProcedure';
    $this->set('locationList', $this->DeliLocation->find('list', array('conditions' => array('DeliLocation.is_default' => true))));
  }

  public function index() {
    $filterId = isset($_GET['filterId']) ? $_GET['filterId'] : 0;

    $condition = array();
    $condition['DeliDefaultLocationProcedure.deleted_time'] = null;
    if ($filterId) {
      $condition['DeliDefaultLocationProcedure.location_id'] = $filterId;
    }
    $js['curLink'] = Router::url(array('action' => 'index'), true);
    if (is_array(Configure::read('Js'))) {
      $js = Hash::merge($js, Configure::read('Js'));
    }
    Configure::write('Js', $js);
    $dataList = $this->DeliDefaultLocationProcedure->find('all', array('conditions' => $condition, 'order' => array('DeliDefaultLocationProcedure.order ASC', 'DeliLocation.order ASC')));
    $this->set(compact('dataList', 'filters', 'filterId'));
  }

  public function edit($id = 0) {
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
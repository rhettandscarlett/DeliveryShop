<?php

/**
 * @property DeliBillingController $DeliBillingController
 * @property DeliSchedule $DeliSchedule
 * @property DeliBilling $DeliBilling
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 * @property DeliBillingRuntimeProcedure $DeliBillingRuntimeProcedure
 *
 */
class DeliBillingController extends AppController {

  public $uses = array(
    'DeliSchedule',
    'DeliDefaultLocationProcedure',
    'DeliBillingRuntimeProcedure',
  );

  public $scheduleList;

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliBilling';
    $this->scheduleList = $this->DeliSchedule->find('list');
    $this->set('scheduleList', $this->scheduleList);
  }

  public function index() {
    $dataList = $this->DeliBilling->find('all', array('order' => array('DeliBilling.order ASC')));
    $dataList = AppModel::generalCombine($dataList, 'DeliBilling', array('schedule_id' => '', 'id' => ''));
    $this->set('allBillingList', $dataList);
  }

  public function edit($scheduleId = 0, $id = 0) {
    $defaultProcedure = $this->DeliDefaultLocationProcedure->getDefaultProcedure($scheduleId);
    if (empty($defaultProcedure)) {
      $this->Session->setFlash(__('Schedule <b>%s</b> has no procedure to work.', $this->scheduleList[$scheduleId]), 'flash/error');
      $this->redirect(Router::url(array(
          'controller' => 'DeliBilling',
          'action'     => 'index'
        )) . buildQueryString());
    }
    $this->set(compact('defaultProcedure', 'scheduleId'));

    if (empty($this->request->data)) {
      $this->request->data = $this->DeliBilling->findById($id);
    } else {
      if (!empty($this->request->data['DeliBilling']['picked_up_date'])) {
        $date = date_create_from_format('d/m/Y', $this->request->data['DeliBilling']['picked_up_date']);
        $this->request->data['DeliBilling']['picked_up_date'] = date('Y-m-d', $date->getTimestamp());
      }
      $this->DeliBilling->set($this->request->data);
      if (!$this->DeliBilling->save()) {
        $this->Session->setFlash(__('Cannot save your data'), 'flash/error');
      } else {
        $this->Session->setFlash(__('Your data is saved successfully'), 'flash/success');
        $this->redirect(Router::url(array(
          'controller' => 'DeliBilling',
          'action'     => 'index'
        )) . buildQueryString());
      }

    }
  }

  public function delete($id) {
    $this->DeliBilling->deleteLogic($id);
    $this->Session->setFlash(__('Billing #%s is deleted successfully', $id), 'flash/success');
    $this->redirect(Router::url(array(
        'controller' => 'DeliBilling',
        'action'     => 'index'
      )) . buildQueryString());
  }


}
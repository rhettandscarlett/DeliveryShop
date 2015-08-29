<?php

/**
 * @property DeliBillingController $DeliBillingController
 * @property DeliSchedule $DeliSchedule
 * @property DeliBilling $DeliBilling
 * @property DeliLocation $DeliLocation
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 * @property DeliBillingRuntimeProcedure $DeliBillingRuntimeProcedure
 * @property DeliRuntimeProcedure $DeliRuntimeProcedure
 * @property DeliBillingRuntimeLocation $DeliBillingRuntimeLocation
 *
 */
class DeliBillingController extends AppController {

  public $uses = array(
    'DeliSchedule',
    'DeliDefaultLocationProcedure',
    'DeliBillingRuntimeProcedure',
    'DeliRuntimeProcedure',
    'DeliBillingRuntimeLocation',
    'DeliLocation',
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliBilling';
    $transitStatusList = Configure::read('DELI_TRANSIT_STATUS_LIST');
    $this->set('transitStatusList', $transitStatusList);
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
      $this->redirect(Router::url(array('controller' => 'DeliBilling','action'     => 'index')) . buildQueryString());
    }
    $this->DeliLocation->bindModel(array(
      'hasMany' => array(
        'DeliRuntimeProcedure' => array(
          'className' => 'DeliRuntimeProcedure',
          'foreignKey' => 'location_id',
          'conditions' => array('DeliRuntimeProcedure.deleted_time IS NULL')
        )
      )
    ));
    $attachLocationsList = $this->DeliLocation->find('all', array('conditions' => array(
          'DeliLocation.schedule_id' => $scheduleId,
          'DeliLocation.is_default' => false,
        )
      )
    );
    $attachLocationsData = $attachLocations = array();
    foreach ($attachLocationsList as $attachLocationsRecord) {
      $attachLocations[$attachLocationsRecord['DeliLocation']['id']] = $attachLocationsRecord['DeliSchedule']['name'] . ' >>>> ' . $attachLocationsRecord['DeliLocation']['name'];
      $attachLocationsData[$attachLocationsRecord['DeliLocation']['id']] = $attachLocationsRecord['DeliRuntimeProcedure'];
    }

    $storedRuntimeProsData = $this->DeliBillingRuntimeProcedure->findAllByBillingId($id);
    $storedRuntimePros = array();
    if (!empty($storedRuntimeProsData)) {
      $storedRuntimePros = Hash::combine($storedRuntimeProsData, '{n}.DeliBillingRuntimeProcedure.runtime_procedure_id', '{n}.DeliBillingRuntimeProcedure.id');
      $storedRuntimeProsData = Hash::combine($storedRuntimeProsData, '{n}.DeliBillingRuntimeProcedure.id', '{n}.DeliBillingRuntimeProcedure');
    }

    $this->set(compact('defaultProcedure', 'scheduleId', 'attachLocations', 'attachLocationsData', 'storedRuntimeProsData' ,'storedRuntimePros'));
    $plusDay = array();
    for ($i = 0; $i <= 10; $i++) {
      $plusDay[$i] = $i;
    }
    $this->set('plusDay', $plusDay);

    if (empty($this->request->data)) {
      $this->DeliBilling->bindModel(array(
        'hasOne' => array(
          'DeliBillingRuntimeLocation' => array(
            'className' => 'DeliBillingRuntimeLocation',
            'foreignKey' => 'billing_id',
            'conditions' => array('DeliBillingRuntimeLocation.deleted_time IS NULL')
          ),
        ),
        'hasMany' => array(
          'DeliBillingRuntimeProcedure' => array(
            'className' => 'DeliBillingRuntimeProcedure',
            'foreignKey' => 'billing_id',
            'conditions' => array('DeliBillingRuntimeProcedure.deleted_time IS NULL')
          ),
        ),
      ));
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
        if (!empty($this->request->data['DeliBillingRuntimeLocation']['location_id'])) {
          $storedLocation = $this->DeliBillingRuntimeLocation->findByBillingId($this->DeliBilling->id);
          $saving = array('billing_id' => $this->DeliBilling->id);
          if (!empty($storedLocation)) {
            $saving['id'] = $storedLocation['DeliBillingRuntimeLocation']['id'];
            if ($this->request->data['DeliBillingRuntimeLocation']['location_id'] !== $saving['id']) {
              $this->DeliBillingRuntimeProcedure->deleteLogic(null, array('DeliBillingRuntimeProcedure.billing_id' => $this->DeliBilling->id));
            }
          }
          $saving['location_id'] = $this->request->data['DeliBillingRuntimeLocation']['location_id'];
          $this->DeliBillingRuntimeLocation->save($saving, false);

          if (!empty($this->request->data['DeliBillingRuntimeProcedure'])) {
            $savingRuntimePros = array();
            foreach ($this->request->data['DeliBillingRuntimeProcedure'] as $runtimeProData) {
              if ($runtimeProData['runtime_procedure_id'] > 0) {
                $savingRuntimePros[$runtimeProData['id']] = $runtimeProData;
                $savingRuntimePros[$runtimeProData['id']]['billing_id'] = $this->DeliBilling->id;
              }
            }
            foreach($storedRuntimePros as $storedRuntimeProId) {
              if (!in_array($storedRuntimeProId, array_keys($savingRuntimePros))) {
                $this->DeliBillingRuntimeProcedure->deleteLogic(null, array('DeliBillingRuntimeProcedure.id' => $storedRuntimeProId));
              }
            }
            $this->DeliBillingRuntimeProcedure->saveMany($savingRuntimePros);
          } else {
            $this->DeliBillingRuntimeProcedure->deleteLogic(null, array('DeliBillingRuntimeProcedure.billing_id' => $this->DeliBilling->id));
          }

        } else {
          $this->DeliBillingRuntimeProcedure->deleteLogic(null, array('DeliBillingRuntimeProcedure.billing_id' => $this->DeliBilling->id));
          $this->DeliBillingRuntimeLocation->deleteLogic(null, array('DeliBillingRuntimeLocation.billing_id' => $this->DeliBilling->id));
        }

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
    $this->DeliBillingRuntimeLocation->deleteLogic(null, array('DeliBillingRuntimeLocation.billing_id' => $id));
    $this->DeliBillingRuntimeProcedure->deleteLogic(null, array('DeliBillingRuntimeProcedure.billing_id' => $id));
    $this->Session->setFlash(__('Billing #%s is deleted successfully', $id), 'flash/success');
    $this->redirect(Router::url(array(
        'controller' => 'DeliBilling',
        'action'     => 'index'
      )) . buildQueryString());
  }


}
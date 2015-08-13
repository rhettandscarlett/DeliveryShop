<?php

App::uses('DeliPageController', 'Controller');

/**
 * @property DeliBilling $DeliBilling
 * @property DeliLocation $DeliLocation
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 * @property DeliSchedule $DeliSchedule
*/

class DeliFrontendBillingController extends DeliPageController{
  public $uses = array(
    'DeliLocation',
    'DeliDefaultLocationProcedure',
    'DeliSchedule',
  );

  public $listTimeZone = array();

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliBilling';
    $this->listTimeZone = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    $this->set('statusList', Configure::read('DELI_TRANSIT_STATUS_LIST'));
  }

  public function doTracking() {
    App::uses('CakeTime', 'Utility');
    if (empty($_GET) || (isset($_GET['bill_code']) && empty($_GET['bill_code']))) {
      $this->Session->setFlash(__('Please type bill code'));
      $this->redirect($this->referer());
    } else {
      $codes = explode("\n", $_GET['bill_code']);
      foreach ($codes as &$code) {
        $code = trim($code);
      }
      $billFound = $this->DeliBilling->findAllByBillCode($codes);
      foreach ($billFound as &$bill) {
        $locations = $this->DeliLocation->find('all', array('conditions'=>array('DeliLocation.schedule_id'=>$bill['DeliSchedule']['id']), 'order'=>array('DeliLocation.order ASC')));
        $dayPlus = 0;
        $bill['DeliLocation'] = array();
        foreach($locations as $lId => $location) {
          date_default_timezone_set($this->listTimeZone[$location['DeliLocation']['timezone']]);
          $localTime = date('Y-m-d H:i:s', time());
          $procudures = $this->DeliDefaultLocationProcedure->find('all', array('conditions'=>array('DeliDefaultLocationProcedure.location_id'=>$location['DeliLocation']['id']), 'order'=>array('DeliDefaultLocationProcedure.order ASC')));
          foreach ($procudures as $pId => &$procudure) {
            $dayPlus += (int)$procudure['DeliDefaultLocationProcedure']['plus_day'];
            $procudureTime = $bill['DeliBilling']['picked_up_date'] . ' ' . $procudure['DeliDefaultLocationProcedure']['time'];
            $procudureTime = date('Y-m-d H:i:s', strtotime($procudureTime . ' +' . $dayPlus . ' day'));
            if ($localTime < $procudureTime) {
              unset($procudures[$pId]);
            } else {
              $procudure['DeliDefaultLocationProcedure']['realtime'] = CakeTime::nice($procudureTime);
            }
          }
          $location['DeliDefaultLocationProcedure'] = Hash::combine($procudures, '{n}.DeliDefaultLocationProcedure.id', '{n}.DeliDefaultLocationProcedure');
          $bill['DeliLocation'][] = $location;
        }
      }

      $this->set('billFound', $billFound);
    }

  }

}
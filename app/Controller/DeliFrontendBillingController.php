<?php

/**
 * @property DeliBilling $DeliBilling
 * @property DeliLocation $DeliLocation
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 * @property DeliSchedule $DeliSchedule
 * @property ContactForm $ContactForm
 * @property DeliRuntimeProcedure $DeliRuntimeProcedure
 * @property DeliBillingRuntimeLocation $DeliBillingRuntimeLocation
 * @property DeliBillingRuntimeProcedure $DeliBillingRuntimeProcedure
 */
class DeliFrontendBillingController extends AppController {
  public $uses = array(
    'DeliLocation',
    'DeliDefaultLocationProcedure',
    'DeliSchedule',
    'DeliRuntimeProcedure',
    'DeliBillingRuntimeLocation',
    'DeliBillingRuntimeProcedure',
    'ContactForm',
  );

  public $listTimeZone = array();

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass   = 'DeliBilling';
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
        $locations            = $this->DeliLocation->find('all', array(
          'conditions' => array(
            'DeliLocation.schedule_id' => $bill['DeliSchedule']['id'],
            'DeliLocation.is_default' => true,
          ),
          'order'      => array('DeliLocation.order ASC')
        ));
        $dayPlus              = 0;
        $bill['DeliLocation'] = array();
        foreach ($locations as $location) {
          date_default_timezone_set($this->listTimeZone[$location['DeliLocation']['timezone']]);
          $localTime  = date('Y-m-d H:i:s', time());
          $procudures = $this->DeliDefaultLocationProcedure->find('all', array(
            'conditions' => array('DeliDefaultLocationProcedure.location_id' => $location['DeliLocation']['id']),
            'order'      => array('DeliDefaultLocationProcedure.order ASC')
          ));
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
          $bill['DeliLocation'][]                   = $location;
        }

        $deliveredLocation = $this->DeliBillingRuntimeLocation->findByBillingId($bill['DeliBilling']['id']);
        if (!empty($deliveredLocation) && !empty($deliveredLocation['DeliBillingRuntimeLocation']['location_id'])) {
          $runtimeProcedures = $this->DeliBillingRuntimeProcedure->find('all', array('conditions' => array('DeliBillingRuntimeProcedure.billing_id' => $bill['DeliBilling']['id']), 'order' => 'DeliRuntimeProcedure.order ASC'));
          if (!empty($runtimeProcedures)) {
            date_default_timezone_set($this->listTimeZone[$deliveredLocation['DeliLocation']['timezone']]);
            $localTime  = date('Y-m-d H:i:s', time());
            foreach ($runtimeProcedures as $pId => &$procudure) {
              $dayPlus += (int)$procudure['DeliBillingRuntimeProcedure']['plus_day'];
              $procudureTime = $bill['DeliBilling']['picked_up_date'] . ' ' . $procudure['DeliBillingRuntimeProcedure']['time'];
              $procudureTime = date('Y-m-d H:i:s', strtotime($procudureTime . ' +' . $dayPlus . ' day'));
              if ($localTime < $procudureTime) {
                unset($runtimeProcedures[$pId]);
              } else {
                $procudure['DeliBillingRuntimeProcedure']['realtime'] = CakeTime::nice($procudureTime);
                $procudure['DeliBillingRuntimeProcedure']['name'] = $procudure['DeliRuntimeProcedure']['name'];
              }
            }

            $deliveredLocation['DeliDefaultLocationProcedure'] = Hash::combine($runtimeProcedures, '{n}.DeliBillingRuntimeProcedure.id', '{n}.DeliBillingRuntimeProcedure');
            $bill['DeliLocation'][]                   = $deliveredLocation;
          }
        }
      }

      $this->set('billFound', $billFound);
    }

  }
}
<?php

/**
 * @property DeliPageController $DeliPageController
 * @property DeliPage $DeliPage
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 *
 */
class DeliPageController extends AppController {

  public $uses = array(

  );

  public $menuList = array();

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'DeliPage';
    $this->menuList = array(
      'homepage' => __('HomePage'),
      'tracking' => __('Tracking'),
      'service' => __('Service'),
      'introduce' => __('About Us'),
      'contact' => __('Contact'),
    );
    $this->set('menuList', $this->menuList);
    $this->set('currentPage', $this->params->url);
  }

  public function index($page = 'index') {
    $this->render($page);
  }

  public function adminIndex() {
    $dataList = $this->DeliPage->find('all', array('order' => array('DeliPage.order ASC')));
    $this->set('dataList', $dataList);
  }

}
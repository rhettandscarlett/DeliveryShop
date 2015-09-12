<?php

/**
 * @property DeliPageController $DeliPageController
 * @property DeliPage $DeliPage
 * @property ContactForm $ContactForm
 * @property DeliDefaultLocationProcedure $DeliDefaultLocationProcedure
 *
 */
class DeliPageController extends AppController {

  public $uses = array(
    'ContactForm',
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

  public function contact() {
    App::uses('CakeEmail', 'Network/Email');
    if (!empty($this->request->data)) {
      $this->ContactForm->set($this->request->data);
      if ($this->ContactForm->validates()) {
        $Email = new CakeEmail();
        $Email->config('gmail');
        $Email->from(array($this->request->data['ContactForm']['email'] => __('PTI Express - ptivn.com - Contact Email')));
        $Email->to('thangcest2@gmail.com');
        $Email->subject($this->request->data['ContactForm']['title']);
        $Email->send($this->request->data['ContactForm']['content']);
        $this->Session->setFlash(__('Thanks for contacting us.'), 'flash/success');
      }
    }

  }

  public function adminIndex() {
    $dataList = $this->DeliPage->find('all', array('order' => array('DeliPage.order ASC')));
    $this->set('dataList', $dataList);
  }

  public function phpinfo() {
    $this->autoRender = false;
    phpinfo();
  }

}
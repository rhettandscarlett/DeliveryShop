<?php
App::uses('AppModel', 'Model');
class SecretAccessController extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = false;
    if ($this->loggedUser->Admin->id !== '1') {
      $this->redirect('/');
    }
  }

  public function query() {
    if(!empty($this->request->data)) {
      $command = ConnectionManager::getDataSource("default");
      try {
        $command->query($this->request->data['SecretAccessQuery']['query']);
        $res = $command->getLog();
        $this->Session->setFlash(__('Your data is saved successfully.'), 'flash/success');
        $this->set('res', $res);
      } catch (Exception $e) {
        $this->set('error', $e->getMessage());
        $this->Session->setFlash(__('Can not excute.'), 'flash/error');
      }
    }
  }

} 
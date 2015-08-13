<?php

class FileModelController extends AppController {

  var $uses = array('File.FileModel');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'FileModel';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function view($id) {
  
    $data = $this->FileModel->findById($id);
    if (!empty($data['FileModel']['deleted_time'])) {
      $this->Session->setFlash(__('This Model has been deleted'), 'flash/error');
    }
    $this->set('data', $data);
  }

  public function edit($id = 0) {

    if (empty($this->request->data)) {
      $this->request->data = $this->FileModel->findById($id);
    } else {
      $this->FileModel->set($this->request->data);
      if ($this->FileModel->validates()) {
        if (!$this->FileModel->save()) {
          $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
        } else {
          $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
          return $this->redirect(Router::url(array('plugin' => 'File', 'controller' => 'FileModel', 'action' => 'view')).'/'.$this->FileModel->id);
        }
      }
    }
  }

  public function delete($id) {
    if ($this->FileModel->isInUsed($id)) {
      $this->Session->setFlash(__('Unable to delete your data. It\'s in used'), 'flash/error');
      return $this->redirect($this->referer());
    }
    $this->FileModel->deleteLogic($id);
      
    return $this->redirect(Router::url(array('plugin' => 'File', 'controller' => 'FileModel', 'action' => 'search')).'/');
  }

  public function search() {

    $condition = array();
    $condition['FileModel.deleted_time'] = null;
      
    $this->set('displayPaging', true);
    $this->Paginator->settings = array(
        'conditions' => $condition,
        'limit' => 10
    );
    $dataList = $this->Paginator->paginate('FileModel');
    
    $this->set('dataList', $dataList);
  }

}
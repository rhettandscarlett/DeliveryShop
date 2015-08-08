<?php

class EmailTemplateController extends AppController {

  var $uses = array('EmailTemplate.EmailTemplate');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'EmailTemplate';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function view($id) {

    $data = $this->EmailTemplate->findById($id);
    if (!empty($data['EmailTemplate']['deleted_time'])) {
      $this->Session->setFlash(__('This Category has been deleted'), 'flash/error');
    }
    $this->set('data', $data);
    $this->set('is_inused', $this->EmailTemplate->isInUsed($id));
  }

  public function edit($id = 0) {
    $required_tokens = Configure::read("EMAIL_TEMPLATE_REQUIRE_FIELDS");

    if (empty($this->request->data)) {
      $this->request->data = $this->EmailTemplate->findById($id);
    } else {
      $this->EmailTemplate->set($this->request->data);
      $data = $this->request->data['EmailTemplate'];
      $lacked_tokens = array();

      if (isset($required_tokens[$data['template_key']])){
        foreach($required_tokens[$data['template_key']] as $token){
          if (strpos($data['body'], $token) === FALSE) {
            $lacked_tokens[] = $token;
          }
        }
      }
      if (!empty($lacked_tokens)){
        $this->Session->setFlash(__('Token required: ') . implode(', ', $lacked_tokens), 'flash/error');
      }
      else {
        if ($this->EmailTemplate->validates()) {
          if (!$this->EmailTemplate->save()) {
            $this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
          } else {
            $this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
            return $this->redirect(Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'view')).'/'.$this->EmailTemplate->id);
          }
        }
      }
    }

    $required_token_elements = @$required_tokens[$this->request->data['EmailTemplate']['template_key']];
    $this->set('required_token_elements', $required_token_elements);
  }

  public function delete($id) {
    if ($this->EmailTemplate->isInUsed($id)) {
      $this->Session->setFlash(__('Unable to delete your data. It\'s in used'), 'flash/error');
      return $this->redirect($this->referer());
    }
    $this->EmailTemplate->deleteLogic($id);
      
    return $this->redirect(Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'search')).'/');
  }

  public function search() {
    $this->set('displayPaging', true);
    $this->Paginator->settings = array(
      'limit' => 10
    );
    $dataList = $this->Paginator->paginate('EmailTemplate');

    foreach ($dataList as $key => $data){
      $dataList[$key]['EmailTemplate']['extra_field_is_inused'] = $this->EmailTemplate->isInUsed($data['EmailTemplate']['id']);
    }

    $this->set('dataList', $dataList);
  }

}
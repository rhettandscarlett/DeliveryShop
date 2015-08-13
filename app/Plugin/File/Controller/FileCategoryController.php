<?php

/**
 * @property FileCategory $FileCategory
 * @property FileCategoryFile $FileCategoryFile
*/
class FileCategoryController extends AppController {
  public $uses = array('File.FileCategoryFile');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'FileCategory';
  }

  public function index() {
    $condition = array();
    $condition['FileCategory.deleted_time'] = null;
    $dataList = $this->FileCategory->find('all', array('conditions' => $condition));
    $this->set('dataList', $dataList);
  }

  public function edit($id = 0) {
    $parentList = $this->FileCategory->getTreeReferenceData($id);
    $this->set('dataParentId', $parentList);
    if (empty($this->request->data)) {
      $this->request->data = $this->FileCategory->findByIdEdit($id);
    } else {
      $this->FileCategory->set($this->request->data);
      if ($this->FileCategory->validates()) {
        if ($this->FileCategory->save()) {
          $this->Session->setFlash(__('Your data is saved successfully.'), 'flash/success');
          $this->redirect(Router::url(array('action' => 'index')));
        }
        $this->Session->setFlash(__('Cannot save your data.'), 'flash/error');
      }
    }
  }

  public function editByAjax($folderId = null, $id = 0) {
    $parentList = $this->FileCategory->getTreeReferenceData($id);
    $this->set('dataParentId', $parentList);
    $this->set('folderId', $folderId);
    if (empty($this->request->data)) {
      $this->request->data = $this->FileCategory->findByIdEdit($id);
    } else {
      $view = new View($this);
      $this->FileCategory->set($this->request->data);
      if ($this->FileCategory->validates()) {
        $this->FileCategory->save();
        $this->jsResponse['location'] = Router::url(array('plugin' => 'File', 'controller' => 'File', 'action' => 'manage', $folderId));
      } else {
        $this->jsResponse['id_display']['errorSaving'] = $view->element('flash/error', array('message' => __('Cannot save your data.')));
        foreach ($this->FileCategory->validationErrors as $field => $error) {
          $this->jsResponse['id_error']['FileCategory' . ucfirst($field)] = $error[0];
        }
      }
      $this->renderJsResponse();
    }
  }


  public function destroy($id) {
    $this->FileCategory->deleteFolder(array($id));
    $this->Session->setFlash(__('Your folder is deleted successfully'), 'flash/success');
    return $this->redirect($this->referer());
  }

} 
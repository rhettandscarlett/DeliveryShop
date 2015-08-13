<?php

App::uses('UtilLib', 'Lib');

class CleanCacheController extends AppController {

  public function cleanCache() {
    echo "Cleaning ...<br>";
    Cache::clear(false);
    clearCache();
    $files = array();
    $files = array_merge($files, glob(CACHE . 'css' . DS . '*')); // remove cached css
    $files = array_merge($files, glob(CACHE . 'js' . DS . '*'));  // remove cached js           
    $files = array_merge($files, glob(CACHE . 'models' . DS . '*'));  // remove cached models           
    $files = array_merge($files, glob(CACHE . 'persistent' . DS . '*'));  // remove cached persistent  
    foreach ($files as $f) {
      if (is_file($f)) {
        @unlink($f);
      }
    }
    $this->Session->setFlash(__("All Cache cleaned!"), 'flash/success');
    $this->redirect(Router::url("/", true));
  }
}

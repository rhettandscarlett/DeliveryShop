<?php
/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class FormHandler {
  private $upload_file;
  function __construct($upload_file=NULL){
    $this->upload_file = $upload_file;
  }
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($this->upload_file['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $this->upload_file['name'];
    }
    function getSize() {
        return $this->upload_file['size'];
    }
}


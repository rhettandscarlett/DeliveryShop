<?php
App::uses('FileLib', 'File.Lib');
/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class UploadBase64Handler {
  private $filename;
  private $file_content;

  function __construct($file_content_base64, $filename){
    $this->file_content = base64_decode($file_content_base64);
    $this->filename = basename($filename);
  }

  /**
   * Save the file to the specified path
   * @return boolean TRUE on success
   */
  function save($path) {
    file_put_contents($path, $this->file_content);
    return true;
  }

  function getName() {
    return $this->filename;
  }

  function getSize() {
    return strlen($this->file_content);
  }
}


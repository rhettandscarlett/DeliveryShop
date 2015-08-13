<?php
App::uses('FormHandler', 'File.Lib');
App::uses('XHRHandler', 'File.Lib');
App::uses('UploadBase64Handler', 'File.Lib');
class UploadHandler {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760, $upload_file=NULL, $file_content_base64_params=NULL){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;

        if (isset($file_content_base64_params)){

          $this->file = new UploadBase64Handler($file_content_base64_params['content'], $file_content_base64_params['filename']);
        }
        elseif (isset($_GET['qqfile'])) {
            $this->file = new XHRHandler();
        } elseif (isset($upload_file)) {
            $this->file = new FormHandler($upload_file);
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            return array('error' => "Increase post_max_size and upload_max_filesize to $size");
        }

        return array();
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }

    public function checkSetting(){
      $errors = $this->checkServerSettings();

      return $errors;
    }

    public function getFileName(){
      return $this->file->getName();
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    public function handleUpload($name, $desc){
      $uploadDirectory = FileLib::getRootFolder() . DS;

      if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
      }

      if (!file_exists(FileLib::getTempFolder())) {
        mkdir(FileLib::getTempFolder(), 0777, true);
      }

      if (!is_writable($uploadDirectory)){
          return array('error' => "Server error. Upload directory $uploadDirectory isn't writable. Please ask server admin to change permissions.");
      }

      if (!$this->file){
          return array('error' => 'No files were uploaded.');
      }

      $size = $this->file->getSize();

      if ($size == 0) {
          return array('error' => 'File is empty!');
      }

      if ($this->sizeLimit && $size > $this->sizeLimit) {
          return array('error' => 'File is too large. Please ask server admin to increase the file upload limit.' . $this->sizeLimit);
      }

      $pathinfo = pathinfo($this->file->getName());
      $filename = $pathinfo['filename'];
      $ext = $pathinfo['extension'];

      if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
          $these = implode(', ', $this->allowedExtensions);
          return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
      }

      $file_path = FileLib::getTempFolder() . DS  . $filename . '_' . uniqid('', TRUE) . '.' . $ext;
      if ($this->file->save($file_path)){
          return array('success' => true, 'values' => array(
            'file_path' => $file_path ,
            'file_name' => $pathinfo['basename'],
            'name' => $name,
            'desc' => $desc,
          ));
      } else {
          return array('error'=> 'Could not save uploaded file. The upload was cancelled, or server error encountered');
      }
    }
}

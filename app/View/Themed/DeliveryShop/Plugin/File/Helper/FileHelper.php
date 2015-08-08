<?php

/**
 * File Helper
 *
 * @package File
 * @subpackage File.views.helpers
 */
App::uses('FileLib', 'File.Lib');
App::import("Model", "File.FileModel");

class FileHelper extends AppHelper {

  var $helpers = array('Html');
  private $_defaultParams = array(
    'id' => '',
    'categoryCode' => '',
    'selectedItemViewType' => 'thumbnail',
    'listItemViewType' => 'thumbnail',
    'limitFileNumber' => 100,
    'limitFileSize' => 100,
    'extensions' => array(),
    'filterName' => '',
    'jsCallback' => '',
    'lang' => '',
    'inputName' => FALSE,
    'inputDesc' => FALSE,
    'inputOrder' => FALSE,
    'isMultiLang' => FALSE,
    'canUpload' => FALSE,
    'type'=>'image',
  );
private function _parseParams($params){
  $config = Configure::read("FILE_PLUGIN_CONFIG");
  $params = is_array($params) ? $params : array();
  $params = array_merge($this->_defaultParams, $params);
  if(isset($config[$params['categoryCode']]) && is_array($config[$params['categoryCode']])){
    $params = array_merge($params, $config[$params['categoryCode']]);
  }  
  foreach($params as $key => $value){
    if(is_bool($value)){
      $params[$key] = $value ? 1 : 0;
    }
    if(!isset($params['id']) || !$params['id']){
      $params['id'] = md5(time()).rand(0, 999);
    }
  }
  return $params;
}
  /**
   * create File Form Element, can choose file from list
   * @param type $modelObject
   * @param type $categoryCode
   * @param array $options
   * @return type
   */
  public function linkFileFormElement($modelObject, $categoryCode, array $options = array()) {
    $objectFileModel = new FileModel();
    $modelValue = '';
    if (empty($modelObject->plugin)) {
      $modelValue = get_class($modelObject);
    } else {FileLib::getFormTemplate();
      $modelValue = $modelObject->plugin . "." . get_class($modelObject);
    }
    //find all file model of this model with category
    $selectedFiles = $objectFileModel->find("all", array(
      'conditions' => array(
        'FileModel.model' => $modelValue,
        'FileModel.model_id' => $modelObject->id,
        'FileModel.category_code' => $categoryCode,
      ),
      'order' => array('FileModel.order' => 'asc')
      )
    );
    $options['categoryCode'] = $categoryCode;
    $params = $this->_parseParams($options);
    //$params['categoryCode'] = $categoryCode;
    $html = $this->_View->element("File.form_choose_file", array(
      'params' => $params,
      'selectedFiles' => $selectedFiles,
    ));
    return $html;
  }

  public function linkFileFormMultipleElement($modelObject, $categoryCode, array $options = array()) {
    $options['categoryCode'] = $categoryCode;
    $params = $this->_parseParams($options);
    
    $multiLangCategories = @$modelObject->multiLanguage['files'];
    if (!isset($multiLangCategories)) {
      $multiLangCategories = array();
    }
    //get selected files
    $objectFileModel = new FileModel();
    $modelValue = '';
    if (empty($modelObject->plugin)) {
      $modelValue = get_class($modelObject);
    } else {
      $modelValue = $modelObject->plugin . "." . get_class($modelObject);
    }
    //file of model
    $selectedFiles = $objectFileModel->find("all", array(
      'conditions' => array(
        'FileModel.model' => $modelValue,
        'FileModel.model_id' => $modelObject->id,
        'FileModel.category_code' => $categoryCode,
      ),
      'order' => array('FileModel.order' => 'asc')
      )
    );
    $isMultiLang = in_array($categoryCode, $multiLangCategories);
    $selectedFileLangs = array();
    if ($isMultiLang && $selectedFiles) {//list file lang
      $objectMultiLangFileModel = new MultilanguageFileModel();
      foreach ($selectedFiles as $selectedFile) {
        $fileLangs = $objectMultiLangFileModel->getLanguageFileModels($selectedFile['FileModel']['id']);
        $selectedFileLangs[$selectedFile['FileModel']['id']] = $fileLangs;
      }
    }
    //$params['categoryCode'] = $categoryCode;
    $html = $this->_View->element('File.form_choose_file_multi', array(
      'params' => $params,
      'selectedFiles' => $selectedFiles,
      'selectedFileLangs' => $selectedFileLangs,
    ));
    return $html;
  }
  public function listFileFormElements($modelObject, $categoryCode, $options=array()) {
    $options['categoryCode'] = $categoryCode;
    $params = $this->_parseParams($options);
    $params['listItemViewType'] = 'name';
    //get selected files
    $object_file_model = new FileModel();
    $modelValue = '';
    if (empty($modelObject->plugin)) {
      $modelValue = get_class($modelObject);
    } else {
      $modelValue = $modelObject->plugin . "." . get_class($modelObject);
    }
    $selectedFiles = $object_file_model->find("all", array(
      'conditions' => array(
        'FileModel.model' => $modelValue,
        'FileModel.model_id' => $modelObject->id,
        'FileModel.category_code' => $categoryCode,
      ),
      'order' => array('FileModel.order' => 'asc')
      )
    );  
    $html = $this->_View->element('File.form_list_file_name', array(
      'params' => $params,
      'selectedFiles' => $selectedFiles,
      'model_id' => $modelObject->id,
    ));
    return $html;
  }
  public function getFirstFileUrl($model_object, $category_code, $is_thumbnail_url = FALSE) {
    $object_file_model = new FileModel();
    if (empty($model_object->plugin)) {
      $model_value = get_class($model_object);
    } else {
      $model_value = $model_object->plugin . "." . get_class($model_object);
    }

    $file_record = $object_file_model->find("first", array(
        'conditions' => array(
          'FileModel.model' => $model_value,
          'FileModel.model_id' => $model_object->id,
          'FileModel.category_code' => $category_code,
        ),
      )
    );

    if (empty($file_record))
      return NULL;
    $file_record = $file_record['File'];

    $file_url = NULL;
    if ($is_thumbnail_url && self::isImage($file_record['file_type'])) {
      $file_url = self::getThumbnailUrl($file_record['path']);
    }
    if (empty($file_url)) {
      $file_url = self::getFileUrl($file_record['path']);
    }

    return $file_url;
  }
  public function listFileThumbnailElement($modelObject, $categoryCode, $options=array()) {
    $options['categoryCode'] = $categoryCode;
    $params = $this->_parseParams($options);
    //get selected files
    $object_file_model = new FileModel();
    $modelValue = '';
    if (empty($modelObject->plugin)) {
      $modelValue = get_class($modelObject);
    } else {
      $modelValue = $modelObject->plugin . "." . get_class($modelObject);
    }
    $file = $object_file_model->find("first", array(
      'conditions' => array(
        'FileModel.model' => $modelValue,
        'FileModel.model_id' => $modelObject->id,
        'FileModel.category_code' => $categoryCode,
      ))
    );
    $file = isset($file['File']) ? $file['File'] : array();
    $html = $this->_View->element('File.file_item_only_thumbnail', array(
      'params' => $params,
      'file' => $file,
      'model_id' => $modelObject->id,
    ));
    return $html;
  }

  public function getFileUrl($relative_path, $get_root_url = TRUE) {
    $url = FileLib::getUrlFile($relative_path, $get_root_url);

    return $url;
  }

  public function getThumbnailUrl($relative_path) {
    if (!FileLib::hasThumbnailFile($relative_path)) {
      return NULL;
    }
    return FileLib::getThumnailURL($relative_path);
  }

  public function isImage($file_extension) {
    return FileLib::isImage($file_extension);
  }

  public function getFileIconUrl($file_extension) {
    return Router::url("/") . 'File/img/fileicons/' . strtolower($file_extension) . '.png';
  }

  public function formatBytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++)
      $size /= 1024;
    return round($size, 2) . $units[$i];
  }

  public function getMaxThumbnailHeight() {
    return Configure::read('AMU.thumbnail_max_height');
  }

  public function isExistPhysicalFile($relative_path) {
    return FileLib::isExistFile($relative_path);
  }

  /* ================================================
    Function description
    Truncate a string to a specified length. If the string length > specified length, will add '...' to end of result string.
    Parameters:
    $string: string, contain an string to truncate.
    $len: string, contain length to truncate to.
    Returns:
    string: contain string after truncated.
    string + '...': if $string length > $len
    ================================================== */

  public function truncateString($string, $len) {
    if (empty($string)) {
      return '';
    }

    if (!isset($len) || $len <= 0) {
      $len = NULL;
    }

    $truncated_string = substr($string, 0, $len);
    if (strlen($string) > $len) {
      $truncated_string .= '...';
    }

    return $truncated_string;
  }

  /**
   * @param $is_multiple_upload is a string that contain boolean type in javascript. Ex: 'true' or 'false'.
   * @param $after_complete_one is a string contain javascript function name that handle after upload complete one. Ex: 'afterCompleteOne'
   * @return string
   */
  public function getAjaxUploadForm($eId, $is_multiple_upload, $add_parameters, $after_complete_one, $after_complete_all, $jsCallbackFunction_after_add_one, $allowedExtensions) {
    $allowedExtensions = $allowedExtensions ? "['".implode("','", $allowedExtensions)."']" : "[]";
    $webroot_url = Router::url("/") . "file";
    $lastDir = 'Object__' . uniqid('');

    $html_forms = <<<END
            <div class="FileManagerUpload$lastDir" name="AjaxMultiUpload" id="file_manager_upload_form_$lastDir">
                <noscript>
                     <p>Please enable JavaScript to use file uploader.</p>
                </noscript>
            </div>
END;

    $html_forms .=<<<END
      <script>
        createUploader('$webroot_url/ajax_upload/', '$lastDir', $is_multiple_upload, '$eId', $add_parameters, $after_complete_one, $after_complete_all, "$jsCallbackFunction_after_add_one", $allowedExtensions);
      </script>
END;

    return $html_forms;
  }

  public static function buildQueryString($params, $reset = false) {
    if (is_array($params)) {
      if ($reset) {
        return http_build_query($params);
      } else {
        $query_data = array();
        parse_str($_SERVER['QUERY_STRING'], $query_data);
        foreach ($params as $pKey => $pVal) {
          if (is_array($pVal) && !empty($pVal)) {
            $params[$pKey] = implode(",", $pVal);
          }
          unset($query_data[$pKey]);
        }
        foreach ($params as $pKey => $pVal) {
          if ($pVal !== NULL)
            $query_data[$pKey] = $pVal;
        }
        return http_build_query($query_data);
      }
    }
    return '';
  }

}

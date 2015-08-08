<?php
/*================================================
This is the FileManagerLib.php of plugin FileManager
This class contain some functions about file management.

Created at: 2013-10-11
Last updated: 2013-10-14
List of functions:
getFullPath($abs_path);
getUrlFile($abs_path);
getRootFolder()
================================================*/
App::uses('ImageLib', 'File.Lib');

class FileLib {
  const STATUS_READY = 0;
  const CONFIG_NO_LIMIT_NB_FILES = -1; //no limit number files

  /*================================================
  Function description
  Concatenate the root resource folder with absolute path. Then return the full path to this resource.
  Parameters:
  $relative_path: string, contain an relative path to file or folder,
  Returns:
  a full path to the resource object
  ==================================================*/
  public static function getFullPath($relative_path){
    return self::getRootFolder() . DS . $relative_path;
  }

  /*================================================
  Function description
  Concatenate the root resource url with absolute path. Then return the full url to this resource.
  Parameters:
  $relative_path: string, contain an absolute path for file or folder,
  Returns:
  a url to this this resource object
  ==================================================*/
  public static function getUrlFile($relative_path, $get_root_url=TRUE){
    $dir = Configure::read('AMU.directory');
    $sub_dir = Configure::read('AMU.sub_directory');
    if ($get_root_url){
      return Router::url("/", TRUE) . $dir . '/' . $sub_dir . '/' . $relative_path;
    }
    else {
      return $dir . '/' . $sub_dir . '/' . $relative_path;
    }
  }

  /*================================================
  Function description
  Get the root resource folder.
  Returns:
  full path to the root resource folder
  ==================================================*/
  public static function getRootFolder(){
    $relPath = Configure::read ('AMU.directory');
    $sub_dir = Configure::read('AMU.sub_directory');

    return WWW_ROOT . $relPath .DS . $sub_dir ;
  }

  /*================================================
  Function description
  Get the full path temp folder.
  Returns:
  full path to the temp folder
  ==================================================*/
  public static function getTempFolder(){
    $abs_temp = Configure::read ('AMU.temp_directory');

    return self::getRootFolder() . DS . $abs_temp ;
  }

  /*================================================
  Function description
  Move a file from $old_path to $new_path.
  Returns:
  TRUE: moved successful
  TRUE: failed move
  ==================================================*/
  public static function moveFile($old_path, $new_path){
    return @rename($old_path, $new_path);
  }

  /*================================================
  Function description
  Move a file from $path to resource folder.
  Returns:
  array('abs_path' => '', 'relative_path' => ''): failed move
  array('abs_path' => 'xxx', 'relative_path' => 'yyy'): moved successful
  ==================================================*/
  public static function moveFileToResourceFolder($path){
    $result = array('abs_path' => '', 'relative_path' => '');

    APP::import('File.Model', 'File');
    $file = new File();
    $max_file_id = $file->getMaxId();

    $nb_items = Configure::read ('AMU.items_per_folder');

    $folder = intval(($max_file_id + 1) / $nb_items);
    $folder = (($max_file_id + 1) % $nb_items) > 0 ? $folder + 1 : $folder;
    $folder = (string) $folder;

    $path_info = pathinfo($path);

    $new_file_name = sha1_file($path) . '_' . uniqid('') . '.' . $path_info['extension'];
    $relative_path = $folder . DS . $new_file_name;

    $new_path = self::getFullPath($relative_path);

    $dir = dirname($new_path);
    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }

    if (self::moveFile($path, $new_path)){
      $result['relative_path'] = $relative_path;
      $result['abs_path'] = $new_path;
    }

    return $result;
  }

  /*================================================
  Function description
  Get limit file size per file can be uploaded.
  Parameters:
  $config: string, contain json format to get the configuration.
  Returns:
  Integer: file size (bytes) can be uploaded.
  ==================================================*/
  public static function getLimitFileSize($configs, $category_code){
    if (empty($configs) || empty($configs[$category_code]) || empty($configs[$category_code]['limit_file_size'])) {
      $size = Configure::read ('AMU.filesizeMB');
      if (empty($size)) {
        return NULL;
      }
      $size_limit = $size * 1024 * 1024;

      return $size_limit;
    }

    return intval($configs[$category_code]['limit_file_size']) * 1024 * 1024;
  }

  /*================================================
  Function description
  Get limit number files can be uploaded.
  Parameters:
  $config: string, contain json format to get the configuration.
  Returns:
  Integer: number of files can be uploaded.
    0 (zero): disable upload function.
    integer > 0: limit number of files can be uploaded.
    -1 or < 0: unlimit number of files can be uploaded.
  ==================================================*/
  public static function getLimitNumberFiles($configs, $category_code){
    //no limit
    if (empty($configs) || empty($configs[$category_code]) || empty($configs[$category_code]['limit_nb_files'])) {
      return NULL;
    }

   return intval($configs[$category_code]['limit_nb_files']);
  }

  /*================================================
  Function description
  Get Boolean, enable or disable input description.

  Parameters:
  $configs: string, contain json format to get the configuration.
  $category_code: string, contain category code
  Returns:
  TRUE: Boolean, if enable input desc
  FALSE: Boolean, if disable input desc
  ==================================================*/
  public static function isEnableInputDesc($configs, $category_code){
    return (empty($configs) || empty($configs[$category_code]) ? FALSE : $configs[$category_code]['input_desc']);
  }

  /*================================================
  Function description
  Get Boolean, enable or disable input name.

  Parameters:
  $configs: string, contain json format to get the configuration.
  $category_code: string, contain category code
  Returns:
  TRUE: Boolean, if enable input name
  FALSE: Boolean, if disable input name
  ==================================================*/
  public static function isEnableInputName($configs, $category_code){
    return (empty($configs) || empty($configs[$category_code]) ? FALSE : $configs[$category_code]['input_name']);
  }

  /*================================================
  Function description
  Get array contain allowed file extensions .

  Parameters:
  $configs: string, contain json format to get the configuration.
  $category_code: string, contain category code
  Returns:
  array: contain extensions
  ==================================================*/
  public static function getAllowedExtensionsFile($configs, $category_code){
    if  (empty($configs) || empty($configs[$category_code]) || empty($configs[$category_code]['allowed_extensions'])){
      $string_extensions = Configure::read ('AMU.allowed_extensions');
      $allowed_extensions = explode(',', $string_extensions);
      return $allowed_extensions;
    }
    else
      return $configs[$category_code]['allowed_extensions'];
  }

  /*================================================
  Function description
  Get array file categories.

  Parameters:
  $configs: string, contain json format to get the configuration.
  Returns:
  array: contain category name and category code.
  ==================================================*/
  public static function getCategories($configs){
    if (empty($configs)){
      return array();
    }
    $categories = array();

    foreach($configs as $config){
      $categories[$config['category_code']] = array('category_code' => $config['category_code'], 'category_name' => $config['category_name']);
    }

    return $categories;
  }

  public static function getHash($path){
    return sha1_file($path);
  }

  public static function hasThumbnailFile($source_relative_path){
    $thumbnail_relative_path = self::getThumbnailPath($source_relative_path);

    return is_file(self::getFullPath($thumbnail_relative_path));
  }

  public static function getThumnailURL($source_relative_path){
    $thumbnail_relative_path = self::getThumbnailPath($source_relative_path);
    return self::getUrlFile($thumbnail_relative_path);
  }

  public static function isImage($extension){
    return in_array(strtolower($extension), self::imageExtensions());
  }

  public static function imageExtensions(){
    return explode(',', Configure::read("AMU.image_extensions"));
  }

  public static function getThumbnailPath($source_relative_path){
    return Configure::read ('AMU.thumbnail_directory') . "/" . $source_relative_path;
  }

  public static function generateThumbnail($source_relative_path){
    $source_path = self::getFullPath($source_relative_path);
    $dest_path =  self::getFullPath(self::getThumbnailPath($source_relative_path));

    return ImageLib::createThumbnail($source_path, $dest_path);
  }

  public static function isExistFile($source_relative_path){
    $path = self::getFullPath($source_relative_path);
    //echo "<br>".$path;
    return is_file($path);
  }
  public static function getFormTemplate($templateName = "thumbnail", $type = 'item', $lang='') {
    switch ($templateName) {
      case "name":
        $itemTemplate = "name";
        break;
      case "multi_name":
        $itemTemplate = $lang ? 'name': "multi";
      break;
      case "multi_thumbnail":
        $itemTemplate = $lang ? 'thumbnail' : "multi";
        break;
      case "thumbnail_and_name":
        $itemTemplate = "thumbnail_and_name";
        break;
      default:
        $itemTemplate = "thumbnail";
        break;
    }
    if ($type == 'list') {
      $itemTemplate = 'list_files_' . $itemTemplate;
    } elseif($type=='item') {
      $itemTemplate = 'file_item_' . $itemTemplate;
    }elseif($type=='filter'){
      $itemTemplate = 'filter_files_' . $itemTemplate;
    }
    return $itemTemplate;
  }
}

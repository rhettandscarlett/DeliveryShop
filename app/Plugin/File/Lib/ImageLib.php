<?php
$plugin_path =  App::pluginPath('File');
require_once $plugin_path ."Vendor" . DS . "phpthumb" . DS . "ThumbLib.inc.php";

class ImageLib {
  public static function createThumbnail($source, $dest){
    $max_width = Configure::read ('AMU.thumbnail_max_width');
    $max_height = Configure::read ('AMU.thumbnail_max_height');

    $dest_folder = dirname($dest);
    if (!file_exists($dest_folder)) {
      mkdir($dest_folder, 0777, true);
    }

    return self::convertDown($source, $dest, $max_width, $max_height);
  }

  public static function convertDown($source, $dest, $max_width=0, $max_height=0){
    try {
      $thumb = \PhpThumbFactory::create($source);
      $thumb->resize($max_width, $max_height);
      $thumb->save($dest);
    }
    catch(\Exception $e){

      return NULL;
    }

    return $dest;
  }
}



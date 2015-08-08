<?php

App::uses('HtmlHelper', 'View/Helper');
App::uses('Inflector', 'Utility');
App::uses('FileLib', 'File.Lib');

class SFHtmlHelper extends HtmlHelper {

  public function useTag($tag) {
    $args = func_get_args();

    if ($tag === 'radio') {
      $class = (isset($args[3]['class'])) ? $args[3]['class'] : 'radio';
      unset($args[3]['class']);
    }

    $html = call_user_func_array(array('parent', 'useTag'), $args);

    if ($tag === 'radio') {
      $regex = '/(<label)(.*?>)/';
      if (preg_match($regex, $html, $match)) {
        $html = $match[1] . ' class="' . $class . '"' . $match[2] . preg_replace($regex, ' ', $html);
      }
    }

    return $html;
  }

  public function image($path, $options = array()) {
    if (empty($path)) {
      $path = '/';
    } else {
      if (isset($options['data-src'])) {
        unset($options['data-src']);
      }
    }
    return parent::image($path, $options);
  }

  public function getImage($file_model, $options = array()) {
    $path = "no-image.png";
    if (!is_null($file_model)) {
      if (FileLib::hasThumbnailFile($file_model['File']['path'])) {
        $path = FileLib::getThumnailURL($file_model['File']['path']);
      } else {
        $imagepath = FileLib::getFullPath($file_model['File']['path']);
        if (is_file($imagepath)) {
          $path = FileLib::getUrlFile($file_model['File']['path']);
        }
      }
      $alt = $file_model['FileModel']['name'];
      if(empty($alt)){
        $alt = $file_model['File']['name'];
      }
      if(isset($options['title']) && strlen($options['title']) > 0){
        $alt = $options['title'];
      }
      $options['alt'] = $alt;
    }

    return $this->image($path, $options);
  }
  
  public function getImagePath($file_model) {
    $path = "no-image.png";
    if (!is_null($file_model)) {
      if (FileLib::hasThumbnailFile($file_model['File']['path'])) {
        $path = FileLib::getThumnailURL($file_model['File']['path']);
      } else {
        $imagepath = FileLib::getFullPath($file_model['File']['path']);
        if (is_file($imagepath)) {
          $path = FileLib::getUrlFile($file_model['File']['path']);
        }
      }
    }

    return $path;
  }

}

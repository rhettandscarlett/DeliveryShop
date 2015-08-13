<?php

class UtilLib {

  public static function mysqlEscapeString($string){
    if (empty($string)) return "";

    return str_replace(array('%', '_'), array('\%', '\_'), $string);
  }

  public static function getCurrentDateTime(){
    return date('Y-m-d H:i:s');
  }

  public static function generateToken(){
    return md5(uniqid(mt_rand(), TRUE));
  }

}
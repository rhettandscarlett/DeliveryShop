<?php

function sfConvertField2Name($field) {
  $str = ucwords(str_replace('_', ' ', $field));
  $str = preg_replace('/\s+id$/i', '', $str);
  return $str;
}

function sfConvertTreeField2Name($str) {
  return preg_replace('/-\s/', '', $str);
}

function sfConvertName2TreeField($depth) {
  return str_repeat('- ', 2 * $depth);
}

function arrayToObject($d) {
  if (is_array($d)) {
    return (object) array_map(__FUNCTION__, $d);
  } else {
    return $d;
  }
}

function objectToArray($d) {
  if (is_object($d)) {
    $d = get_object_vars($d);
  }
  if (is_array($d)) {
    return array_map(__FUNCTION__, $d);
  } else {
    return $d;
  }
}

function convertPixel2MM($pixel, $dpi = 72) {
  $inchs = $pixel / $dpi;
  return $inchs * 25.4;
}
function buildQueryString($params = array(), $reset = false) {
  $ret = '';
  if (is_array($params)) {
    if ($reset) {
      $ret = http_build_query($params);
    } else {
      $query_data = array();
      parse_str($_SERVER['QUERY_STRING'], $query_data);
      foreach ($params as $pKey => $pVal) {
        unset($query_data[$pKey]);
      }
      foreach ($params as $pKey => $pVal) {
        if ($pVal !== NULL)
          $query_data[$pKey] = $pVal;
      }
      $ret = http_build_query($query_data);
    }
  }
  if($ret){
    $ret = '?'.$ret;
  }
  return $ret;
}
function getErrorMsg($arrError) {
  $error = "";
  if (!empty($arrError)) {
    foreach ($arrError as $errorField) {
      if (!empty($errorField)) {
        $error .= "<li>" . $errorField . "</li>";
      }
    }
  }
  return $error;
}

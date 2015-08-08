<?php

function unitTestGetRealPath($plugin, $section, $file) {
  $path = unitTestPath($plugin, $section, $file);

  if(!is_file($path)) {
    return false;
  }
  return $path;
}

function unitTestPath($plugin, $section, $file) {
  $path = ROOT.'/app';
  $path .= empty($plugin) ? '' : '/Plugin/'.$plugin;
  $path .= '/Test/Case/'.$section.'/'. str_replace('.php','Test.php',$file);
  return $path;
}

function unitTestClassPath($plugin, $section, $file) {
  $path = ROOT.'/app';
  $path .= empty($plugin) ? '' : '/Plugin/'.$plugin;
  $path .= '/'.$section.'/'. $file;
  return $path;
}

function unitTestDataPath($plugin, $section, $file) {
  $file = sha1('plug-'.$plugin.'-sec'.$section.'-file'.$file);
  return ROOT.'/app/Plugin/UnitTest/Config/Data/'.$file;
}

function unitTestRoundPercent($num1, $num2) {
  return $num2 == 0 ? 0 : round($num1/$num2);
}


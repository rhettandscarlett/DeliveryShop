<?php

class UnitTestHelper extends AppHelper {

  public function buttonRun($plugin, $section, $file) {

    $disable = unitTestGetRealPath($plugin, $section, $file) ? '' : 'disabled="disabled"';

    return '
      <button onclick="unitTestRun(this,\''.$plugin.'\',\''.$section.'\',\''.$file.'\')" type="button" class="btn btn-warning btn-xs" '.$disable.'>Run</button>
    ';
  }

  public function buttonRunDebug($plugin, $section, $file) {
    $disable = unitTestGetRealPath($plugin, $section, $file) ? '' : 'disabled="disabled"';

    return '
      <button onclick="unitTestRunDebug(\''.$plugin.'\',\''.$section.'\',\''.$file.'\')" type="button" class="btn btn-warning btn-xs" '.$disable.'>Debug</button>
    ';
  }

  public function buttonAdd($plugin, $section, $file) {
    return '
      <button onclick="unitTestAdd(this,\''.$plugin.'\',\''.$section.'\',\''.$file.'\')" type="button" class="btn btn-warning btn-xs">Add Unit Test</button>
    ';
  }
}

<?php

class UnitTestController extends AppController {

  var $helpers = array('UnitTest.UnitTest');

  public function beforeFilter() {

    parent::beforeFilter();
  }

  public function beforeRender() {
    $this->layout = 'blank';
    parent::beforeRender();
  }

  public function afterFilter() {
    parent::afterFilter();
  }

  public function index() {

    $lists = $this->getAllClasses();

    $results = array();
    $total = array();
    $total['all']['pass'] = 0;
    $total['all']['fail'] = 0;
    $total['all']['coverage'] = 0;
    $total['all']['coverage-number'] = 0;

    foreach($lists as $pluginKey => $plugins) {
      $plugin = $plugins['plugin'];
      unset($plugins['plugin']);
      $total['plugin'][$pluginKey]['pass'] = 0;
      $total['plugin'][$pluginKey]['fail'] = 0;
      $total['plugin'][$pluginKey]['coverage'] = 0;
      $total['plugin'][$pluginKey]['coverage-number'] = 0;

      foreach($plugins as $section => $files) {
        foreach($files as $key => $file) {
          $fileData = unitTestDataPath($plugin, $section, $file);
          $timeData = is_file($fileData) ? filectime($fileData) : 0;
          $fileUnitTest = unitTestGetRealPath($plugin, $section, $file);
          if(is_file($fileUnitTest)) {
            $timeUnitTest = filectime($fileUnitTest);
            if($timeUnitTest > $timeData) {
              $this->runATestCase($section, $file, $plugin);
            }
          }

          if(is_file($fileData)) {
            $result = unserialize(file_get_contents($fileData));
            $results[$pluginKey][$section][$file]['pass'] = isset($result['pass']) ? $result['pass'] : 0;
            $results[$pluginKey][$section][$file]['fail'] = isset($result['fail']) ? $result['fail'] : 0;
            $results[$pluginKey][$section][$file]['coverage'] = isset($result['coverage']) ? $result['coverage'] : 0;

            $total['plugin'][$pluginKey]['pass'] += $results[$pluginKey][$section][$file]['pass'];
            $total['plugin'][$pluginKey]['fail'] += $results[$pluginKey][$section][$file]['fail'];
            $total['plugin'][$pluginKey]['coverage'] += $results[$pluginKey][$section][$file]['coverage'];
            $total['plugin'][$pluginKey]['coverage-number']++;
            $total['all']['pass'] += $results[$pluginKey][$section][$file]['pass'];
            $total['all']['fail'] += $results[$pluginKey][$section][$file]['fail'];
            $total['all']['coverage'] += $results[$pluginKey][$section][$file]['coverage'];
            $total['all']['coverage-number']++;
          }
        }
      }
    }

    $this->set('total',$total);
    $this->set('results',$results);
    $this->set('lists',$lists);
  }

  public function run() {
    $section = $_GET['section'];
    $file = $_GET['file'];
    $plugin = $_GET['plugin'];

    $result = $this->runATestCase($section, $file, $plugin);

    return $this->responseJson($result);
  }

  public function runDebug() {
    $section = $_GET['section'];
    $file = $_GET['file'];
    $plugin = $_GET['plugin'];

    $result = $this->runATestCase($section, $file, $plugin);

    $this->set('result',$result);
  }


  public function add() {
    $section = $_GET['section'];
    $file = $_GET['file'];
    $plugin = $_GET['plugin'];

    $fileUnitTest = unitTestPath($plugin, $section, $file);
    if(is_file($fileUnitTest)) {
      return array();
    }

    $template = ROOT.'/app/Plugin/UnitTest/Config/Template/';
    $className = str_replace('.php','',$file);
    $pluginName = empty($plugin) ? '' : $plugin.'.';

    $funcs = '';
    $classPath = unitTestClassPath($plugin, $section, $file);
    $strClass = file_get_contents($classPath);
    $str = '';
    preg_match_all('/public\s+function\s+(.*?)\(/i',$strClass,$matches);

    switch($section) {
      case 'Controller':
        $str = file_get_contents($template.'controller.php');
        foreach($matches[1] as $func) {
          $funcs .= "
  public function test".ucfirst($func)."() {

  }
          ";
        }
        $str = sprintf($str,$className,$funcs);
        break;
      case 'Controller/Component':
        $str = file_get_contents($template.'controller_component.php');
        foreach($matches[1] as $func) {
          $funcs .= "
  public function test".ucfirst($func)."() {

  }
          ";
        }
        $classNoComponent = str_replace('Component','',$className);
        $str = sprintf($str,$className,$pluginName,$classNoComponent,$className,$className,$className,$className,$classNoComponent,$className,$className,$funcs);
        break;
      case 'Model':
        $str = file_get_contents($template.'model.php');
        foreach($matches[1] as $func) {
          $funcs .= "
  public function test".ucfirst($func)."() {

  }
          ";
        }

        $str = sprintf($str,$className,$pluginName,$className,$className,$pluginName,$className,$funcs);
        break;
      case 'View/Helper':
        $str = file_get_contents($template.'view_helper.php');
        foreach($matches[1] as $func) {
          $funcs .= "
  public function test".ucfirst($func)."() {

  }
          ";
        }

        $str = sprintf($str,$className,$pluginName,$className,str_replace('Helper','',$className),$className,$funcs);
        break;
    }

    if(!empty($str)) {
      $pathDir = dirname($fileUnitTest);
      if(!is_dir($pathDir)) {
        mkdir($pathDir,0777,true);
      }
      file_put_contents($fileUnitTest,$str);
    }

    $result = $this->runATestCase($section, $file, $plugin);
    return $this->responseJson($result);
  }

  private function runATestCase($section, $file, $plugin) {
    if(!unitTestGetRealPath($plugin, $section, $file)) {
      return array();
    }

    $url = FULL_BASE_URL.'/test.php?case=';

    $url .= urlencode($section.'/'.str_replace('.php','',$file));
    if(!empty($plugin)) {
      $url .= '&plugin='.$plugin;
    }
    $url .= '&show_passes=1&code_coverage=true';

    App::uses('HttpSocket', 'Network/Http');
    $HttpSocket = new HttpSocket();
    $results = $HttpSocket->get($url);

    $data = array();
    $data['fail'] = 0;
    $data['pass'] = 0;
    $data['data'] = array();

    preg_match_all('/<li class=["\'](fail|pass)["\']>(.*?)<\/li>/si',$results,$matches);
    foreach($matches[1] as $key => $value) {
      if($value=='fail') {
        $data['fail']++;
        $data['data']['fail'][$key] = $matches[2][$key];
      }
      if($value=='pass') {
        $data['pass']++;
        $data['data']['pass'][$key] = $matches[2][$key];
      }
    }

    preg_match_all('/coverage_show_hide\(["\']coverage-'.$file.'["\']\);">.*?Code coverage:\s*(\d+)%.*?<pre>(.*?)<\/pre>/si',$results,$matches);

    if(isset($matches[1][0])) {
      $data['coverage'] = $matches[1][0];
    }
    $data['coverage_code'] = $results;

    $file = unitTestDataPath($plugin, $section, $file);
    file_put_contents($file, serialize($data));

    return $data;
  }

  private function getAllClasses() {
    $list = array();

    $list['APP'] = $this->getClasses();
    $list['APP']['plugin'] = '';

    $dir = new Folder(ROOT.'/app/Plugin/');
    $plugins = $dir->read();
    $ignorePlugin = Configure::read('UnitTest.Ignore.Plugin');

    foreach($plugins[0] as $plugin) {
      if(in_array($plugin,$ignorePlugin)) {
        continue;
      }
      $list['Plugin - '.$plugin] = $this->getClasses($plugin);
      $list['Plugin - '.$plugin]['plugin'] = $plugin;
    }

    return $list;
  }

  private function getClasses($plugin='') {
    $list = array();

    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    if($plugin=='') {
      $path = ROOT.'/app/';
    } else {
      $path = ROOT.'/app/Plugin/'.$plugin.'/';
    }

    $dir = new Folder($path.'Controller');
    $list['Controller'] = $dir->find('.*\.php');

    $dir = new Folder($path.'Controller/Component');
    $list['Controller/Component'] = $dir->find('.*\.php');

    $dir = new Folder($path.'Model');
    $list['Model'] = $dir->find('.*\.php');

    //$dir = new Folder($path.'Model/Behavior');
    //$list['Model/Behavior'] = $dir->find('.*\.php');

    $dir = new Folder($path.'View/Helper');
    $list['View/Helper'] = $dir->find('.*\.php');


    return $list;
  }

}

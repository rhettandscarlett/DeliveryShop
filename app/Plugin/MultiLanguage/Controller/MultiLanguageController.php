<?php

/**
 * MultiLanguage Controller
 *
 * @package MultiLanguage
 * @subpackage MultiLanguage.controllers
 */
App::uses('ObjectTypeModel', 'Model');
App::uses('ObjectLinkModel', 'Model');
App::uses('MultiLanguageModel', 'MultiLanguage.Model');

class MultiLanguageController extends AppController {

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'MultiLanguageModel';
  }

  /**
   * Update language data.
   *
   * @param int $objectID
   * @param ObjectType $objectType
   * @return void
   */
  public function update($objectTypeID = null, $objectID = null) {
    $objectType = new ObjectTypeModel();
    $objectTypeData = $objectType->getFromId($objectTypeID);
    if (!$objectTypeData) {
      throw new NotFoundException('Could not find object type');
    }

    if (!$this->Session->check('Config.MultiLanguage.update.referer')) {
      $this->Session->write('Config.MultiLanguage.update.referer', $this->referer());
    }

    $objectConf = $objectType->convertConfigFromJson($objectTypeData->extra_data);

    $objectLinkModel = new ObjectLinkModel();
    $objectLinkModel->useTable = $objectTypeData->link_table;
    $this->MultiLanguageModel->useTable = 'multilanguage_' . $objectTypeData->link_table;
    $objectLink = $objectLinkModel->getFromObjectId4Edit($objectID);
    if (!$objectLink) {
      throw new NotFoundException('Could not find object');
    }

    $dataMulti = $this->MultiLanguageModel->getData($objectID);
    $dataMultiFiles = $this->MultiLanguageModel->getDataFiles($objectID);

    $dataFiles = array();
    if (isset($objectConf['multilanguage']['files'])) {
      $dataFiles = $objectLinkModel->getFromQuery('SELECT file_objects.*, files.path, files.filename FROM file_objects '
              . 'INNER JOIN files ON files.id = file_objects.file_id '
              . 'WHERE category_code IN (\'' . implode("','", $objectConf['multilanguage']['files']) . '\') AND object_id = "' . $objectID . '"');
    }

    if ($this->request->isPost() || $this->request->is('put')) {
      foreach ($this->request->data['txtdata'] as $key => $value) {
        if (!in_array($key, array_keys(Configure::read("MultiLanguage.list")))) {
          continue;
        }
        $dataUpdate = array();
        $dataUpdate['id'] = @$dataMulti['id'][$key];
        $dataUpdate['object_id'] = $objectID;
        $dataUpdate['lang_code'] = $key;
        foreach ($value as $key1 => $val1) {
          $dataUpdate[$key1] = empty($val1) ? null : $val1;
        }
        $multiLanguageModel = new MultiLanguageModel();
        $multiLanguageModel->useTable = 'multilanguage_' . $objectTypeData->link_table;
        $multiLanguageModel->save(array('MultiLanguageModel' => $dataUpdate), false);
      }

      //update files
      if (!empty($this->request['form']['files'])) {
        foreach ($this->request['form']['files']['tmp_name'] as $lang => $files) {
          foreach ($files as $id => $tmp) {
            if (is_file($this->request['form']['files']['tmp_name'][$lang][$id])) {
              if (!isset($dataFiles[$id])) {
                continue;
              }
              $destination = Configure::read('MultiLanguage.directory') . DS . $lang . DS . $dataFiles[$id]->path;
              $folder = dirname($destination);
              if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
              }
              move_uploaded_file($this->request['form']['files']['tmp_name'][$lang][$id], $destination);
            }
          }
        }
      }

      if (isset($this->request->data['files'])) {
        $indexDataFiles = array();
        foreach ($dataFiles as $value) {
          $indexDataFiles[$value->object_id . '_' . $value->file_id] = $value->id;
        }

        foreach ($this->request->data['files'] as $file_id => $value) {
          foreach ($value as $lang_code => $value1) {
            $dataUpdate = array();
            $dataUpdate['id'] = @$dataMultiFiles['id'][$file_id][$lang_code];
            $dataUpdate['object_id'] = $objectID;
            $dataUpdate['file_id'] = $file_id;
            if (!isset($indexDataFiles[$objectID . '_' . $file_id])) {
              continue;
            }
            $dataUpdate['file_objects_id'] = $indexDataFiles[$objectID . '_' . $file_id];
            $dataUpdate['lang_code'] = $lang_code;
            $dataUpdate['name'] = !isset($value1['name']) || empty($value1['name']) ? null : $value1['name'];
            $dataUpdate['desc'] = !isset($value1['desc']) || empty($value1['desc']) ? null : $value1['desc'];
            $dataUpdate['file_id_link'] = !isset($value1['file-hidden']) || empty($value1['file-hidden']) ? null : $value1['file-hidden'];

            if (empty($dataUpdate['name']) && empty($dataUpdate['desc']) && empty($dataUpdate['file_id_link'])) {
              continue;
            }

            $multiLanguageModel = new MultiLanguageModel();
            $multiLanguageModel->useTable = 'multilanguage_file_objects';
            $multiLanguageModel->save(array('MultiLanguageModel' => $dataUpdate), false);
          }
        }
      }

      $referer = $this->Session->read('Config.MultiLanguage.update.referer');
      $this->Session->delete('Config.MultiLanguage.update.referer');
      $this->redirect($referer);
    }

    $this->set('dataFiles', $dataFiles);
    $this->set('dataMulti', @$dataMulti['data']);
    $this->set('dataMultiFiles', @$dataMultiFiles['data']);
    $this->set('objectConf', $objectConf);
    $this->set('objectType', $objectTypeData);
    $this->set('objectLink', $objectLink);
  }

  /**
   * Change the language
   *
   * @param string $lang
   * @return void
   */
  public function change($lang) {
    $list = Configure::read('MultiLanguage.list');
    $this->Session->write('Config.language', $lang);
    return $this->redirect($this->referer());
  }

  public function deleteImage($path, $lang) {
    $path = base64_decode($path);
    $file = Configure::read('MultiLanguage.directory') . DS . $lang . DS . $path;
    if (is_file($file)) {
      unlink($file);
    }
    die;
  }

  public function updateCMS($selectedLang = 'eng', $keyword_dt = '') {
    $this->MultiLanguageModel->useTable = false;

    $languages = $this->getCMSLanguages(false, array($selectedLang));

    if (!empty($this->request->data)) {
      foreach ($this->request->data['languagesEdited'] as $lang => $data) {
        $file = ROOT . '/app/Locale/' . $lang . '/LC_MESSAGES/default.po';
        $str = '';
        foreach ($data as $key => $val) {
          $key = base64_decode($key);
          $val = trim($val);
          $str .= "msgid   \"$key\"\nmsgstr  \"$val\"\n\n";
        }
        file_put_contents($file, $str);
      }
      $keyword_dt = isset($this->request->data['MultiLanguageModel']['keyword_dt']) ? $this->request->data['MultiLanguageModel']['keyword_dt'] : "";
      $this->_clearCache();
      $this->Session->setFlash(__('CMS translation was saved successfully'), 'flash/success');
      return $this->redirect(Router::url(array('plugin' => 'MultiLanguage', 'controller' => 'MultiLanguage', 'action' => 'updateCms', $selectedLang, $keyword_dt)));
    }

    $this->set('keyword_dt', $keyword_dt);
    $this->set('languages', $languages);
    $this->set('selectedLang', $selectedLang);
  }

  public function exportCMS() {
    $list = array_merge(Configure::read('MultiLanguage.fallback'), Configure::read('MultiLanguage.list'));
    App::uses('ExcelLib', 'Lib');
    $excel = new ExcelLib();
    $excel->init();

    $data = array();

    //save
    $mapping = array();
    foreach (Configure::read('MultiLanguage.app_mapping_list') as $key => $val) {
      $mapping[$val] = strtoupper($key);
    }

    $header[] = 'Key';
    foreach (array_keys($list) as $lang) {
      $header[] = $mapping[$lang];
    }
    $data[] = $header;

    $languages = $this->getCMSLanguages();

    foreach ($languages as $key => $language) {
      $row = array();
      $row[] = $key;
      foreach ($language as $val) {
        $row[] = $val;
      }
      $data[] = $row;
    }

    $excel->writeFromArray($data);
    $excel->send2Browser(array('filename' => sprintf("cms-translation-%s.xls", date("Ymd", time()))));
  }

  private function _clearCache() {
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');
    $dir = new Folder(APP.'tmp/cache/persistent');
    $files = $dir->find('.*');
    foreach ($files as $file) {
      unlink($dir->pwd() . DS . $file);
    }
  }

  public function importCms() {
    $this->layout = 'admin';
    $this->MultiLanguageModel->useTable = false;
    $listLanguage = array_keys(array_merge(Configure::read('MultiLanguage.fallback'), Configure::read('MultiLanguage.list')));
    $msgError = '';

    if (isset($this->request->params['form']['MultilanguageImportCms'])) {
      if (isset($this->request->params['form']['MultilanguageImportCms']['tmp_name']) && is_file($this->request->params['form']['MultilanguageImportCms']['tmp_name'])) {
        if (($handle = fopen($this->request->params['form']['MultilanguageImportCms']['tmp_name'], "r")) !== FALSE) {
          App::uses('ExcelLib', 'Lib');
          $excel = new ExcelLib();
          $excel->initFromFile($this->request->params['form']['MultilanguageImportCms']['tmp_name']);
          $listData = $excel->getAllRowsCurrentSheet();
          if (count($listData) > 0) {

            $mapping = Configure::read('MultiLanguage.app_mapping_list');
            for ($ii = 1; $ii < count($listData[0]); $ii++) {
              $listData[0][$ii] = isset($mapping[strtolower($listData[0][$ii])]) ? $mapping[strtolower($listData[0][$ii])] : $listData[0][$ii];
            }

            $data = $listData[0];
            if ($data[0] != 'Key' || count(array_intersect($listLanguage, $data)) != count($listLanguage)) {
              $msgError = __('The header of Excel file shoud be in format') . ': Key';
              foreach ($listLanguage as $lang) {
                $msgError .= ', ' . $lang;
              }
            } else {
              $listLanguage = $data;
              $listKey = $this->getCMSLanguages(true);
              $numField = count($listLanguage);

              for ($row = 1; $row < count($listData); $row++) {
                $data = $listData[$row];
                $num = count($data);
                if ($num != $numField || !isset($listKey[$data[0]])) {
                  $msgError .= __('Cannot import line %s', $row) . "<br />";
                } else {
                  for ($c = 1; $c < $num; $c++) {
                    $listKey[$data[0]][$listLanguage[$c]] = $data[$c];
                  }
                }
              }

              $dataSave = array();
              foreach ($listKey as $key => $translates) {
                foreach ($translates as $lang => $text) {
                  $dataSave[$lang][$key] = trim($text);
                }
              }

              foreach ($dataSave as $lang => $data) {
                $file = ROOT . '/app/Locale/' . $lang . '/LC_MESSAGES/default.po';
                $str = '';
                foreach ($data as $key => $val) {
                  $str .= "msgid   \"$key\"\nmsgstr  \"$val\"\n\n";
                }
                file_put_contents($file, $str);
              }
            }
            fclose($handle);
          }
        } else {
          $msgError = __('Cannot open the uploaded file');
        }
      } else {
        $msgError = __('Cannot open the uploaded file');
      }

      if (empty($msgError)) {
        $this->Session->setFlash(__('Import successfully'), 'flash/success');

        // Clear cache
        $this->_clearCache();
        $this->redirect(array('plugin' => 'MultiLanguage', 'controller' => 'MultiLanguage', 'action' => 'updateCms'));
      } else {
        $this->Session->setFlash($msgError, 'flash/error');
      }
    }
  }

  private function getCMSLanguages($emptyDefault = false, $selectedLang = array()) {
    $languages = array();
    $listLanguage = array_keys(Configure::read('MultiLanguage.fallback') + Configure::read('MultiLanguage.list'));

    $root = ROOT . '/app/Locale/';
    $str = file_get_contents($root . 'default.po');
    preg_match_all('/msgid\s+"(.*?)"\n/', $str, $matches);

    foreach ($matches[1] as $key) {
      $languages[$key] = array();
      foreach ($listLanguage as $lang) {
        if (count($selectedLang) > 0) {
          if (in_array($lang, $selectedLang)) {
            $languages[$key][$lang] = '';
          }
        } else {
          $languages[$key][$lang] = '';
        }
      }
    }

    if ($emptyDefault) {
      return $languages;
    }

    foreach ($listLanguage as $lang) {
      if (count($selectedLang) > 0 && !in_array($lang, $selectedLang)) {
        continue;
      }

      if (!is_dir($root . $lang . '/LC_MESSAGES')) {
        mkdir($root . $lang . '/LC_MESSAGES', 0777, 1);
      }
      $file = $root . $lang . '/LC_MESSAGES/default.po';

      if (is_file($file)) {
        $str = file_get_contents($file);
        preg_match_all('/msgid\s+"(.*?)".+?msgstr\s+"(.*?)"\n/is', $str, $matches);
        
        foreach ($matches[1] as $index => $key) {
          if (isset($languages[$key])) {
            $languages[$key][$lang] = $matches[2][$index];
          }
        }
      }
    }
    return $languages;
  }

  public function scanKeyLanguage() {
    $this->autoRender = false;
    $destinationPoFilePath = ROOT. '/app/Locale/default.po';
    $desRecentKeyPath = ROOT. '/app/Locale/recentListKey.txt';

    $listFile = $this->find_all_files(ROOT . '/app');
    $listKey = array();
    foreach ($listFile as $file) {
      $lines = file($file);
      foreach ($lines as $line) {
        preg_match_all('/__\([\'"](.*?)[\'"][,)]/', $line, $matches);
        preg_match_all('/[\'"]message[\'"]\s+[=][>]\s+[\'"](.*?)[\'"]/', $line, $matches2);
        if ((count($matches[1]) > 0)) {
          foreach ($matches[1] as $key) {
            $listKey[$key] = '';
          }
        }
        if ((count($matches2[1]) > 0)) {
          foreach ($matches2[1] as $key2) {
            $listKey[$key2] = '';
          }
        }
      }
    }
    ksort($listKey);
    $i = 0;
    if (!is_file($destinationPoFilePath)) {
      $str = '';
      $strKey = '';
      foreach (array_keys($listKey) as $key) {
        $str .= "msgid   \"$key\"\n";
        $str .= "msgstr  \"\"\n";
        $str .= "\n";
        $strKey .= $key . "$$$$$$$$$$";
        $i++;
      }
      file_put_contents($destinationPoFilePath, $str);
      file_put_contents($desRecentKeyPath, $strKey);
    } else {
      $sourceLangKeys = explode("$$$$$$$$$$", file_get_contents($desRecentKeyPath));
      $str = '';
      $strKey = '';
      foreach (array_keys($listKey) as $key) {
        if (in_array($key, $sourceLangKeys)) {
          continue;
        } else {
          $str .= "msgid   \"$key\"\n";
          $str .= "msgstr  \"\"\n";
          $str .= "\n";
          $strKey .= $key . "$$$$$$$$$$";
          $i++;
        }
      }
      file_put_contents($destinationPoFilePath, $str, FILE_APPEND);
      file_put_contents($desRecentKeyPath, $strKey, FILE_APPEND);
    }
    die(__('Scanned and updated successfully, have more %s words from last time.', $i));
  }

  private function find_all_files($dir) {
    $root = scandir($dir);
    foreach ($root as $value) {
      if ($value === '.' || $value === '..' || $value === '.DS_Store') {
        continue;
      }
      if (is_file("$dir/$value")) {
        if (preg_match('/\/app\/Plugin\/DebugKit\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Plugin\/CodeGenerator\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Plugin\/Questionnaire\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Plugin\/RequestLog\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Plugin\/Revision\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Plugin\/Seo\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Plugin\/UnitTest\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/View\/Themed\/ITrainer\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/View\/Themed\/SFThem\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/app\/Vendor\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/PHPExcel\//si', "$dir/$value")) {
          continue;
        }
        if (preg_match('/\/webroot\//si', "$dir/$value")) {
          continue;
        }
        $result[] = "$dir/$value";
        continue;
      }
      $aaa = $this->find_all_files("$dir/$value");
      if (is_array($aaa)) {
        foreach ($aaa as $value) {
          $result[] = $value;
        }
      }
    }
    return isset($result) ? $result : array();
  }

}

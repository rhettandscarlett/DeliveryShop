<?php

class MultiLanguageLib {

  static function getDataFromQuery($query, $queryMultiLanguage) {
    $fallback = array_keys(Configure::read('MultiLanguage.fallback'));
    $model = new AppModel();
    $model->useTable = false;
    $dataFallback = $model->query($query);
    $exportData = array();
    foreach ($dataFallback[0] as $data) {
      foreach ($data as $key => $val) {
        $exportData[$key][$fallback[0]] = $val;
      }
    }

    $dataLanguages = $model->query($queryMultiLanguage);
    foreach ($dataLanguages as $data) {
      foreach ($data['dataMultiLanguage'] as $key => $val) {
        if (isset($exportData[$key])) {
          $exportData[$key][$data['dataMultiLanguage']['lang_code']] = $val;
        }
      }
    }

    $arrData = array();

    $listLanguage = array_keys(Configure::read('MultiLanguage.fallback') + Configure::read('MultiLanguage.list'));

    $arrData[] = MultiLanguageLib::getHeader4Export();

    foreach ($exportData as $key => $language) {
      $row = array();
      $row[] = $key;
      foreach ($listLanguage as $lang) {
        $row[] .= isset($language[$lang]) ? $language[$lang] : '';
      }
      $arrData[] = $row;
    }

    return $arrData;
  }

  static function saveDataFromArray($object, $listData, $listKey) {

    if (count($listData) == 0) {
      return true;
    }

    $id = $object->getID();
    $msgError = '';

    $listLanguage = array_keys(Configure::read('MultiLanguage.fallback') + Configure::read('MultiLanguage.list'));

    $mapping = Configure::read('MultiLanguage.app_mapping_list');
    for ($ii = 1; $ii < count($listData[0]); $ii++) {
      $listData[0][$ii] = isset($mapping[strtolower($listData[0][$ii])]) ? $mapping[strtolower($listData[0][$ii])] : $listData[0][$ii];
    }
    $data = $listData[0];

    if (strtoupper($data[0]) != 'KEY' || count(array_intersect($listLanguage, $data)) != count($listLanguage)) {
      $msgError = __('The header of Excel file shoud be in format') . ': Key';
      foreach ($listLanguage as $lang) {
        $msgError .= ', ' . $lang;
      }
    } else {
      $listLanguage = $data;
      $numField = count($listLanguage);
      $dataMultiLang = array();
      for ($row = 1; $row < count($listData); $row++) {
        $data = $listData[$row];
        $num = count($data);
        if ($num != $numField || !in_array($data[0], $listKey)) {
          $msgError .= __('Cannot import line %s', $row) . "<br />";
        } else {
          for ($c = 1; $c < $num; $c++) {
            $dataMultiLang[$listLanguage[$c]][$data[0]] = $data[$c];
          }
        }
      }
    }

    $fallback = array_keys(Configure::read('MultiLanguage.fallback'));
    if (isset($dataMultiLang[$fallback[0]])) {
      $row = $dataMultiLang[$fallback[0]];
      $row['id'] = $id;
      $object->clear();
      $object->save($row, false);
      unset($dataMultiLang[$fallback[0]]);
    }

    $model = new AppModel();
    $model->useTable = 'multilanguage_' . $object->useTable;

    foreach ($dataMultiLang as $lang => $row) {
      $objMultiLang = $model->findByObjectIdAndLangCode($id, $lang);
      if (isset($objMultiLang['AppModel']['id'])) {
        $row['id'] = $objMultiLang['AppModel']['id'];
      }
      $row['lang_code'] = $lang;
      $row['object_id'] = $id;

      $model->clear();
      $model->save($row, false);
    }
    return $msgError;
  }

  static public function getHeader4Export() {
    $header = array();
    $header[] = 'KEY';

    $listLanguage = array_keys(Configure::read('MultiLanguage.fallback') + Configure::read('MultiLanguage.list'));
    $mapping = array();
    foreach (Configure::read('MultiLanguage.app_mapping_list') as $key => $val) {
      $mapping[$val] = strtoupper($key);
    }

    foreach ($listLanguage as $lang) {
      $header[] = $mapping[$lang];
    }
    return $header;
  }

  static public function getListDataByKeyOrder($header, $row) {
    $data = array();
    foreach ($header as $index => $key) {
      $data[$key] = $row[$index];
    }
    return $data;
  }

}

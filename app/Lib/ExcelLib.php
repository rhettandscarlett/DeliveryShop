<?php

require_once ROOT . '/app/Vendor/PhpExcel/PHPExcel.php';
require_once ROOT . '/app/Vendor/PhpExcel/PHPExcel/IOFactory.php';

class ExcelLib {

  var $PHPExcel;
  var $Writer;

  public function init($params = array('Title' => 'Office 2003 XLSX Document'), $writeType = 'Excel5') {

    $this->PHPExcel = new PHPExcel();

    foreach ($params as $key => $value) {
      $func = 'set' . $key;
      $this->PHPExcel->getProperties()->$func($value);
    }

    $this->Writer = PHPExcel_IOFactory::createWriter($this->PHPExcel, $writeType);
  }

  public function initFromFile($file) {
    $this->PHPExcel = PHPExcel_IOFactory::load($file);
  }

  public function send2Browser($param = array('filename' => ''), $xlsx = false) {
    if($xlsx){
      if (empty($param['filename'])) {
        $param['filename'] = 'export_' . date('Y_m_d_H_i_s') . '.xlsx';
      }
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $param['filename'] . '"');
      header('Cache-Control: max-age=0');
      header('Cache-Control: max-age=1');
      header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
      header ('Cache-Control: cache, must-revalidate');
      header ('Pragma: public');

      $objWriter = PHPExcel_IOFactory::createWriter($this->PHPExcel, 'Excel2007');
      $objWriter->save('php://output');
    } else {
      if (empty($param['filename'])) {
        $param['filename'] = 'export_' . date('Y_m_d_H_i_s') . '.xls';
      }
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
      header("Content-Disposition: attachment;filename={$param['filename']}");
      header("Content-Transfer-Encoding: binary ");
      $this->Writer->save('php://output');
    }
  }

  public function writeFromArray($data, $fromRow = 1, $fromCol = 'A') {
    $col = $fromCol;
    foreach ($data as $row) {
      foreach ($row as $val) {
        $this->PHPExcel->getActiveSheet()->setCellValue($col . $fromRow, $val);
        $col++;
      }
      $col = $fromCol;
      $fromRow++;
    }
  }

  public function writeFromRow($data, $fromRow = 1, $fromCol = 'A') {
    foreach ($data as $val) {
      $this->PHPExcel->getActiveSheet()->setCellValue($fromCol . $fromRow, $val);
      $fromCol++;
    }
  }

  public function getRow($row = 0) {

  }

  public function getAllRowsCurrentSheet() {
    $data = array();
    $worksheet = $this->PHPExcel->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    for ($row = 1; $row <= $highestRow; ++$row) {
      for ($col = 0; $col < $highestColumnIndex; ++$col) {
        $cell = $worksheet->getCellByColumnAndRow($col, $row);
        $data[$row - 1][$col] = $cell->getValue();
      }
    }
    return $data;
  }

}

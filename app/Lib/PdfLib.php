<?php

require_once ROOT . '/app/Vendor/tcpdf/tcpdf.php';
require_once ROOT . '/app/Vendor/FPDI/fpdi.php';

class PdfLib extends FPDI {

  var $tplIdx;
  var $numPages = 0;
  var $currentPage = 0;

  public function Header() {

  }

  function Footer() {

  }

  public function importPdf($file) {
    $this->numPages = $this->setSourceFile($file);
  }

  public function importPdfPage($page) {
    if ($this->numPages < $page) {
      return false;
    }
    if ($this->currentPage != 0) {
      $this->endPage();
    }
    $this->currentPage = $page;

    $this->tplIdx = $this->importPage($this->currentPage);
    $size = $this->getTemplateSize($this->tplIdx);
    if (isset($size['w']) && isset($size['h']) && $size['w'] > $size['h']) {
      $this->AddPage('L');
    } else {
      $this->AddPage('P');
    }

    $this->useTemplate($this->tplIdx);
    return true;
  }

  public function importRemainPagesPdf() {
    for ($i = $this->currentPage + 1; $i <= $this->numPages; $i++) {
      $this->importPdfPage($i);
    }
  }

  public function textAnnotation($x, $y, $text, $title = 'Comment', $color = array(255, 255, 0), $w = 10, $h = 10) {
    $this->Annotation($x, $y, $w, $h, $text, array('Subtype' => 'Text', 'Name' => $title, 'T' => $title, 'Subj' => $title, 'C' => $color));
  }

  public function drawRectangle($x, $y, $w, $h, $style = 'DF', $alpha = 0.3, $fillColor = array(255, 0, 0), $drawColor = array(127, 0, 0)) {
    $this->SetAlpha($alpha);
    $this->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
    $this->SetDrawColor($drawColor[0], $drawColor[1], $drawColor[2]);
    $this->Rect($x, $y, $w, $h, $style);
  }

  /*
   * I: inline browser
   * D: force download
   * F: Save to file
   * E: return the document as base64 mime multi-part email attachment (RFC 2045)
   * S: return the document as a string
   */

  public function pdfOutput($filename = '', $dest = 'D') {
    if (empty($filename)) {
      $filename = 'export_' . date('Y_m_d_H_i_s') . '.pdf';
    }
    return $this->Output($filename, $dest);
  }

}

<?php

class FileDownload {

  private $filePointer;

  public function __construct($filePointer) {
    if (!is_resource($filePointer)) {
      throw new InvalidArgumentException("You must pass a file pointer to the ctor");
    }

    $this->filePointer = $filePointer;
  }

  public function sendDownload($filename) {
    if (headers_sent()) {
      throw new \RuntimeException("Cannot send file to the browser, since the headers were already sent.");
    }

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Content-Type: {$this->getMimeType($filename)}");
    header("Content-Disposition: attachment; filename=\"{$filename}\";");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: {$this->getFileSize()}");

    @ob_clean();

    rewind($this->filePointer);
    fpassthru($this->filePointer);

    fclose($this->filePointer);
  }

  private function getMimeType($fileName) {
    switch (pathinfo($fileName, PATHINFO_EXTENSION)) {
      case "pdf": return "application/pdf";
      case "exe": return "application/octet-stream";
      case "zip": return "application/zip";
      case "doc": return "application/msword";
      case "xls": return "application/vnd.ms-excel";
      case "ppt": return "application/vnd.ms-powerpoint";
      case "gif": return "image/gif";
      case "png": return "image/png";
      case "jpeg":
      case "jpg": return "image/jpg";
      default: return "application/force-download";
    }
  }

  private function getFileSize() {
    $stat = fstat($this->filePointer);
    return $stat['size'];
  }

  public static function createFromFilePath($filePath) {
    if (!is_file($filePath)) {
      throw new \InvalidArgumentException("File does not exist");
    } else if (!is_readable($filePath)) {
      throw new \InvalidArgumentException("File to download is not readable.");
    }

    return new FileDownload(fopen($filePath, "rb"));
  }

  public static function createFromString($content) {
    $file = tmpfile();
    fwrite($file, $content);

    return new FileDownload($file);
  }

}

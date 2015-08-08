<?php

class SystemDatabaseHelper extends AppHelper {

  public function arrayChunk($array, $size) {
    $listlen = count($array);
    $partlen = floor($listlen / $size);
    $partrem = $listlen % $size;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $size; $px++) {
      $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
      $partition[$px] = array_slice($array, $mark, $incr);
      $mark += $incr;
    }
    return $partition;
  }
}

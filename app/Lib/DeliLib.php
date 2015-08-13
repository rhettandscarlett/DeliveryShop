<?php

class DeliLib {
  public static function minuteToHour($minutes) {
    $hours = (int)($minutes/60);
    return $hours . ' ' . __('Hours') . ' ' . ($minutes - $hours * 60) . ' ' . __('Minutes');
  }


} 
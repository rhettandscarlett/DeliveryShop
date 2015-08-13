<?php

/**
 * Multilanguage Helper
 *
 * @package Multilanguage
 * @subpackage Multilanguage.views.helpers
 */
class MultiLanguageHelper extends AppHelper {

  /**
   * Helpers
   *
   * @var array
   */
  public $helpers = array('Html', 'Session', 'Time');

  /**
   * @return string List selected language
   */
  public function listLanguages($languages = array()) {
    if (count($languages) == 0) {
      $languages = array_merge(Configure::read('MultiLanguage.fallback'), Configure::read('MultiLanguage.list'));
    }

    if ($this->Session->check('Config.language')) {
      $current = $this->Session->read('Config.language');
    } else {
      $current = Configure::read('Config.language');
    }

    $link = '';
    $link .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
    $link .= '<i class="flags flag-' . $current . '"></i>
        <span class="hhh">
          ' . $languages[$current] . '
        </span>
        <i class="fa fa-caret-down"></i>
        </a>';
    unset($languages[$current]);
    $link .= '<ul class="dropdown-navbar dropdown-menu">';
    foreach ($languages as $key => $val) {
      $link .= '<li class="flag-li">';
      $link .= $this->Html->link(sprintf('<i class="flags flag-%s"></i><p>%s</p>', $key, $val), array('plugin' => 'MultiLanguage', 'controller' => 'MultiLanguage', 'action' => 'change', $key), array('escape' => false));
      $link .= '</li>';
    }
    $link .= '</ul>';
    return $link;
  }

}

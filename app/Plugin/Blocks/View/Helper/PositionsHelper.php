<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Positions Helper
 *
 */
class PositionsHelper extends AppHelper {

  public function count($position_name) {
    $count = 0;
    if (!$this->is_empty($position_name)) {
      $count = $this->_View->viewVars['blocks_for_layout'][$position_name];
    }

    return $count;
  }

  public function is_empty($position_name) {
    if (isset($this->_View->viewVars['blocks_for_layout'][$position_name]) &&
      count($this->_View->viewVars['blocks_for_layout'][$position_name]) > 0) {
      return false;
    } else {
      return true;
    }
  }

  public function blocks($position_name, $options = array()) {
    $output = '';
    if ($this->is_empty($position_name)) {
      return $output;
    }

    $options = Hash::merge(array(
        'element_options' => array(),
        ), $options);
    $element_options = $options['element_options'];

    $default_element = 'Blocks.block';
    $view = $this->_View;
    $view->Blocks->set('test', 'test 123');
    $blocks = $view->viewVars['blocks_for_layout'][$position_name];

    foreach ($blocks as $idx => $block) {
      $block['id'] = $idx;
      $element = $block['element'];
      $exists = $view->elementExists($element);
      $block_output = '';
      if ($exists) {
        $block_output = $view->element($element, compact('block'), $element_options);
      } else {
        if (!empty($element)) {
          $this->log(sprintf('Missing element `%s`', $block['element']), LOG_WARNING);
        }
        $block_output = $view->element($default_element, compact('block'), array('ignoreMissing' => true) + $element_options);
      }
      $output .= $block_output;
    }

    return $output;
  }

}

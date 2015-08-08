<?php

App::uses('Component', 'Controller');

/**
 * Blocks Component
 *
 */
class BlocksComponent extends Component {

  public $blocks_for_layout = array();
  public $blocks_data = array();

  public function initialize(Controller $controller) {
    $this->controller = $controller;
    if (isset($controller->Block)) {
      $this->Block = $controller->Block;
    } else {
      $this->Block = ClassRegistry::init('Blocks.Block');
    }
  }

  public function startup(Controller $controller) {
    if (!isset($controller->request->params['requested'])) {
      $this->blocks();
    }
  }

  public function beforeRender(Controller $controller) {
    $controller->set('blocks_for_layout', $this->blocks_for_layout);
  }

  public function blocks() {
    $controller_name = str_replace('Controller', '', get_class($this->controller));

    $positions = Configure::read('SF.Blocks.Positions');
    $default_blocks = Configure::read('SF.Blocks.DefaultBlocks');
    $_blocks = Configure::read('SF.Blocks.Blocks');

    $blocks = array();
    foreach ($positions as $position) {
      $position = strtolower($position);
      $blocks[$position] = $default_blocks[$position];
      if (isset($_blocks[$controller_name]) && isset($_blocks[$controller_name][$position])) {
        if (is_array($_blocks[$controller_name][$position])) {
          $blocks[$position] = $_blocks[$controller_name][$position];
        } else {
          unset($blocks[$position]);
        }
      }
    }

    $this->blocks_for_layout = $blocks;
  }

  public function processBlocksData($blocks) {
    foreach ($blocks as $block) {
      $b = $this->parseString($block['body']);
      $this->blocks_data[] = $b;
    }
  }

  public function parseString($text) {
    $output = array();
    preg_match_all('/\{(.*?):([A-Za-z0-9_\-]*)(.*?)\}/i', $text, $tagMatches);
    for ($i = 0, $ii = count($tagMatches[1]); $i < $ii; $i++) {
      $regex = '/(\S+)=[\'"]?((?:.(?![\'"]?\s+(?:\S+)=|[>\'"]))+.)[\'"]?/i';
      preg_match_all($regex, $tagMatches[3][$i], $attributes);
      $alias = $tagMatches[2][$i];
      $aliasOptions = array();
      for ($j = 0, $jj = count($attributes[0]); $j < $jj; $j++) {
        $aliasOptions[$attributes[1][$j]] = $attributes[2][$j];
      }
      if ($options['convertOptionsToArray']) {
        foreach ($aliasOptions as $optionKey => $optionValue) {
          if (!is_array($optionValue) && strpos($optionValue, ':') !== false) {
            $aliasOptions[$optionKey] = $this->stringToArray($optionValue);
          }
        }
      }
      $output[$alias] = $aliasOptions;
    }
    return $output;
  }

}

<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Elements Helper
 *
 */
class ElementsHelper extends AppHelper {

  public $helpers = array(
    'Html',
    'Form',
    'Session',
    'Js'
  );

  /**
   * constructor
   */
  public function __construct(View $view, $settings = array()) {
    parent::__construct($view);
    $this->_setupEvents();
  }

  protected function _setupEvents() {
    $events = array(
      'Helper.Layout.beforeFilter' => array(
        'callable' => 'filter', 'passParams' => true,
      ),
    );
    $eventManager = $this->_View->getEventManager();
    foreach ($events as $name => $config) {
      $eventManager->attach(array($this, 'filter'), $name, $config);
    }
  }

  public function filter(&$content) {
    preg_match_all('/\{(block):([A-Za-z0-9_\-]*)(.*?)\}/i', $content, $tagMatches);
		for ($i = 0, $ii = count($tagMatches[1]); $i < $ii; $i++) {
			$regex = '/(\S+)=[\'"]?((?:.(?![\'"]?\s+(?:\S+)=|[>\'"]))+.)[\'"]?/i';
			preg_match_all($regex, $tagMatches[3][$i], $attributes);
			$name = $tagMatches[2][$i];
			$options = array();
			for ($j = 0, $jj = count($attributes[0]); $j < $jj; $j++) {
				$options[$attributes[1][$j]] = $attributes[2][$j];
			}
			$content = str_replace($tagMatches[0][$i], $this->loadData($name,$options), $content);
		}
    return $content;
  }

  public function loadData($name, $options = array()) {
		$_options = array(
			'link' => true,
			'plugin' => 'Blocks',
			'controller' => 'Blocks',
			'action' => 'view',
			'element' => 'Blocks.list',
		);
		$options = array_merge($_options, $options);
    //TODO: not use requestAction but call plugin/controller/action directly
    $data = $this->requestAction($options);
    $output = '';
		if (!empty($data)) {
			$output = $this->_View->element($options['element'], array(
				'name' => $name,
				'data' => $data,
				'options' => $options,
			));
		}
		return $output;
	}
}

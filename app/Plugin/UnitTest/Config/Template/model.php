<?php
App::uses('%s', '%sModel');

class %sTest extends CakeTestCase {

  public $fixtures = array();

  public function setUp() {
    parent::setUp();
    $this->%s = ClassRegistry::init('%s%s');
  }

  %s
}

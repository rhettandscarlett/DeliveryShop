<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('%s', '%sView/Helper');

class %sTest extends CakeTestCase {

  public function setUp() {
    parent::setUp();
    $Controller = new Controller();
    $View = new View($Controller);
    $this->%s = new %s($View);
  }

  %s

}

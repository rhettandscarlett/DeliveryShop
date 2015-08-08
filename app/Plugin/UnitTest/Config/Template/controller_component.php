<?php
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('%s', '%sController/Component');


class Test%sController extends Controller {
  public $paginate = null;
}

class %sTest extends CakeTestCase {
  public $%s = null;
  public $Controller = null;

  public function setUp() {
    parent::setUp();

    $Collection = new ComponentCollection();
    $this->%s = new %s($Collection);
    $CakeRequest = new CakeRequest();
    $CakeResponse = new CakeResponse();
    $this->Controller = new Test%sController($CakeRequest, $CakeResponse);
    $this->%s->startup($this->Controller);
  }

  public function tearDown() {
    parent::tearDown();
    unset($this->%s);
    unset($this->Controller);
  }

  %s
}

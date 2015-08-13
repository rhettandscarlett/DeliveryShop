<?php
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('MultiLanguageComponent', 'MultiLanguage.Controller/Component');

// A fake controller to test against
class TestMultiLanguageController extends Controller {
  public $paginate = null;
}

class MultiLanguageComponentTest extends CakeTestCase {
  public $MultiLanguageComponent = null;
  public $Controller = null;

  public function setUp() {
    parent::setUp();
    $Collection = new ComponentCollection();
    $this->MultiLanguageComponent = new MultiLanguageComponent($Collection);
    $CakeRequest = new CakeRequest();
    $CakeResponse = new CakeResponse();
    $this->Controller = new TestMultiLanguageController($CakeRequest, $CakeResponse);
    $this->MultiLanguageComponent->startup($this->Controller);
  }

  public function testInitialize() {
    CakeSession::delete('Config.language');
    $this->MultiLanguageComponent->initialize($this->Controller);
    $fallback = array_keys(Configure::read('MultiLanguage.fallback'));
    $this->assertEqual(Configure::read('Config.language'),$fallback[0]);

    CakeSession::write('Config.language','deu');
    $this->MultiLanguageComponent->initialize($this->Controller);
    $this->assertEqual(Configure::read('Config.language'),'deu');
  }

  public function tearDown() {
    parent::tearDown();
    unset($this->MultiLanguageComponent);
    unset($this->Controller);
  }
}

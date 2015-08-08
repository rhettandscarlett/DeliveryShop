<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('MultiLanguageHelper', 'MultiLanguage.View/Helper');

class MultiLanguageHelperTest extends CakeTestCase {

  public function setUp() {
    $Controller = new Controller();
    $View = new View($Controller);
    $this->MultiLanguage = new MultiLanguageHelper($View);
  }

  public function testListLanguages() {
    CakeSession::delete('Config.language');
    $result = $this->MultiLanguage->listLanguages();
    $this->assertRegExp('/<span class="hhh">\s*English\s*<\/span>/i',$result);

    CakeSession::write('Config.language','fra');
    $result = $this->MultiLanguage->listLanguages();
    $this->assertRegExp('/<span class="hhh">\s*Français\s*<\/span>/i',$result);
  }

}

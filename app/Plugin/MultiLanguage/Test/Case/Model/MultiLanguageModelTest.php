<?php

App::uses('MultiLanguageModel', 'MultiLanguage.Model');

class MultiLanguageModelTest extends CakeTestCase {

  public $fixtures = array('plugin.MultiLanguage.MultilanguageModel');
  public $dropTables = true;

  public function setUp() {
    parent::setUp();
    $this->MultiLanguageModel = ClassRegistry::init('MultiLanguage.MultiLanguageModel');
  }

  public function testGetData() {
    App::uses('AppModel','Model');
    $model = new AppModel();
    $model->useTable = 'model';
    $this->MultiLanguageModel->setMainModel($model);

    $result = $this->MultiLanguageModel->getData(1);

    $expected =
      array(
        'name' => array(
          'eng' => 'Name - eng',
          'fra' => 'Name - fra',
          'deu' => 'Name - deu'
        )
      );

    $this->assertEquals($expected, $result['data']);
  }

}

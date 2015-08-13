<?php

App::uses('BlocksAppController', 'Blocks.Controller');

class BlocksController extends BlocksAppController {

  public $name = 'Blocks';

  public function view(){
    return array(
      array(
        'title' => 'List 1',
      ),
      array(
        'title' => 'List 2',
      ),
      array(
        'title' => 'List 3',
      ),
      array(
        'title' => 'List 4',
      ),
      array(
        'title' => 'List 5',
      ),
    );
  }
}

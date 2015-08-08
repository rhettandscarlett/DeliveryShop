<?php

App::uses('AppModel', 'Model');
class EmailTemplate extends AppModel {

  var $useTable = 'email_template';
  var $multiLanguage = null;

  public $actsAs = array('MultiLanguage.MultiLanguage');

  var $validate = array(
    'template_key' => array(
      'size' => array(
        'rule' => array('between', 1, 255),
        'message' => 'Please enter a text between 1 to 255 characters'
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'message' => 'Key has already been taken'
      ),
    ),
    'template_type' => array(
      'size' => array(
        'rule' => array('between', 1, 255),
        'message' => 'Please enter a text between 1 to 255 characters'
      )
    ),
    'subject' => array(
      'size' => array(
        'rule' => array('between', 1, 255),
        'message' => 'Please enter a text between 1 to 255 characters'
      )
    ),
    'body' => array(
      'rule' => 'notEmpty',
      'message' => 'Please enter a text',
    ),
  );

  public function isInUsed($id) {
    $is_in_used = parent::isInUsed($id);
    if ($is_in_used) return TRUE;

    $template_record = $this->findById($id);
    if (empty($template_record)) return TRUE;

    $required_tokens = Configure::read("EMAIL_TEMPLATE_REQUIRE_FIELDS");
    return isset($required_tokens[$template_record['EmailTemplate']['template_key']]);
  }
}

<?php

class ContactForm extends AppModel {
  public $useTable = false;
  var $validate = array (
    'fullname' =>
      array (
        'notNull' =>
          array (
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'This field cannot be left blank',
          ),
      ),
    'phone' =>
      array (
        'notNull' =>
          array (
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'This field cannot be left blank',
          ),
      ),
    'title' =>
      array (
        'notNull' =>
          array (
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'This field cannot be left blank',
          ),
      ),
    'email' =>
      array (
        'notNull' =>
          array (
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'This field cannot be left blank',
          ),
        'email' =>
          array(
            'rule' =>
              array(
                0 => 'email',
              ),
            'message' => 'Please enter a valid email address',
          ),
      ),
    'content' =>
      array (
        'notNull' =>
          array (
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'This field cannot be left blank',
          ),
      ),


  );


} 
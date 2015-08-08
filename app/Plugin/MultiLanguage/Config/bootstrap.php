<?php

Configure::write('MultiLanguage.relativepath', 'multilanguage');
Configure::write('MultiLanguage.directory', WWW_ROOT . Configure::read('MultiLanguage.relativepath'));
Configure::write('MultiLanguage.list', array(
  //'deu' => __('Deutsch'),
//  'eng' => __('English'),
));
Configure::write('MultiLanguage.app_mapping_list', array(
  'en' => 'eng',
  'de' => 'deu',
));
Configure::write('MultiLanguage.fallback', array('deu' => __('Deutsch')));
Configure::write('Config.language', 'deu');

<?php

Configure::write('MultiLanguage.relativepath', 'multilanguage');
Configure::write('MultiLanguage.directory', WWW_ROOT . Configure::read('MultiLanguage.relativepath'));
Configure::write('MultiLanguage.list', array(

));
Configure::write('MultiLanguage.app_mapping_list', array(
  'en' => 'eng',
  'vi' => 'vie',
));
Configure::write('MultiLanguage.fallback', array('vie' => __('Vietnamese')));
Configure::write('Config.language', 'eng');

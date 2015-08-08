<?php
Configure::write('UnitTest.Ignore.APP', array());
Configure::write('UnitTest.Ignore.Plugin', array('Seo', 'UnitTest', 'DebugKit'));


include_once dirname(__FILE__).'/functions.php';

<?php
Router::connect('/unit-testing/', array('plugin' => 'UnitTest', 'controller' => 'UnitTest', 'action' => 'index'));
Router::connect('/unit-testing/:action/*', array('plugin' => 'UnitTest', 'controller' => 'UnitTest'));

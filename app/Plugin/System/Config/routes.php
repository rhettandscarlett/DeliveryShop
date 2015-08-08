<?php
Router::connect('/system/cache/:action/*', array('plugin' => 'System', 'controller' => 'CleanCache'));

Router::connect('/system/database', array('plugin' => 'System', 'controller' => 'SystemDatabase', 'action'=>'updateDb'));
Router::connect('/system/database/:action/*', array('plugin' => 'System', 'controller' => 'SystemDatabase'));

Router::connect('/system/multilang', array('plugin' => 'System', 'controller' => 'MultiLangDatabase', 'action' => 'listModel'));
Router::connect('/system/multilang/:action/*', array('plugin' => 'System', 'controller' => 'MultiLangDatabase'));
?>
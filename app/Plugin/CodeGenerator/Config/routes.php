<?php
Router::connect('/CodeGenerator/:action/*', array('plugin' => 'CodeGenerator', 'controller' => 'CodeGenerator'));
Router::connect('/CodeGenerator', array('plugin' => 'CodeGenerator', 'controller' => 'CodeGenerator', 'action' => 'listModel'));
?>
<?php

Router::connect('/admin/file-folder', array('plugin' => 'File', 'controller' => 'FileCategory', 'action' => 'index'));
Router::connect('/admin/file-folder/:action/*', array('plugin' => 'File', 'controller' => 'FileCategory'));
Router::connect('/admin/file/', array('plugin' => 'File', 'controller' => 'File', 'action' => 'listing'));
Router::connect('/admin/file/:action/*', array('plugin' => 'File', 'controller' => 'File'));

Router::connect('/file-folder', array('plugin' => 'File', 'controller' => 'FileCategory', 'action' => 'index'));
Router::connect('/file-folder/:action/*', array('plugin' => 'File', 'controller' => 'FileCategory'));
Router::connect('/file_category/:action/*', array('plugin' => 'File', 'controller' => 'FileCategory'));
Router::connect('/file/', array('plugin' => 'File', 'controller' => 'File', 'action' => 'listing'));
Router::connect('/file/:action/*', array('plugin' => 'File', 'controller' => 'File'));

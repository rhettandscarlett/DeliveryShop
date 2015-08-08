<?php
/**
 * Date: 1/16/15
 * Time: 2:20 PM
 */
Router::connect('/admin/secret1421393009', array('plugin' => 'SecretAccess', 'controller' => 'SecretAccess', 'action' => 'query'));
Router::connect('/admin/secret1421393009/:action/*', array('plugin' => 'SecretAccess', 'controller' => 'SecretAccess'));

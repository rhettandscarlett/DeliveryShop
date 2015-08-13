<?php

Router::connect('/user/super-admin', array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'index'));
Router::connect('/user/super-admin/:action/*', array('plugin' => 'User', 'controller' => 'UserAdmin'));

Router::connect('/user/role', array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'index'));
Router::connect('/user/role/:action/*', array('plugin' => 'User', 'controller' => 'UserRole'));

Router::connect('/user/account', array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'index'));
Router::connect('/user/account/:action/*', array('plugin' => 'User', 'controller' => 'UserAccount'));

Router::connect('/user/access', array('plugin' => 'User', 'controller' => 'UserRoleRight', 'action' => 'index'));
Router::connect('/user/access/:action/*', array('plugin' => 'User', 'controller' => 'UserRoleRight'));

Router::connect('/user/role-access/users/*', array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'editUsers'));
Router::connect('/user/role-access/rights/*', array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'editRoles'));

Router::connect('/user/data-access/add/*', array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'edit'));
Router::connect('/user/data-access/update/*', array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'updateList'));
Router::connect('/user/data-access/delete/*', array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'delete'));
Router::connect('/user/data-access/*', array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'index'));

Router::connect('/user/', array('plugin' => 'User', 'controller' => 'User', 'action' => 'index'));
Router::connect('/user/:action/*', array('plugin' => 'User', 'controller' => 'User'));

Router::connect('/oauth/', array('plugin' => 'User', 'controller' => 'OAuth', 'action' => 'loginFb'));
Router::connect('/oauth/:action/*', array('plugin' => 'User', 'controller' => 'OAuth'));
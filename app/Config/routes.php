<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

Router::connect('/', array('controller' => 'DeliPage', 'action' => 'index'));
Router::connect('/homepage', array('controller' => 'DeliPage', 'action' => 'index', 'homepage'));
Router::connect('/tracking', array('controller' => 'DeliPage', 'action' => 'index', 'tracking'));
Router::connect('/service', array('controller' => 'DeliPage', 'action' => 'index', 'service'));
Router::connect('/introduce', array('controller' => 'DeliPage', 'action' => 'index', 'introduce'));
Router::connect('/contact', array('controller' => 'DeliPage', 'action' => 'index', 'contact'));
Router::connect('/admin', array('controller' => 'DashBoard', 'action' => 'index'));

Router::connect('/admin/schedule', array('controller' => 'DeliSchedule', 'action' => 'index'));
Router::connect('/admin/schedule/:action/*', array('controller' => 'DeliSchedule'));

Router::connect('/admin/location', array('controller' => 'DeliLocation', 'action' => 'index'));
Router::connect('/admin/location/:action/*', array('controller' => 'DeliLocation'));

Router::connect('/admin/default-procedure', array('controller' => 'DeliDefaultLocationProcedure', 'action' => 'index'));
Router::connect('/admin/default-procedure/:action/*', array('controller' => 'DeliDefaultLocationProcedure'));

Router::connect('/admin/runtime-procedure', array('controller' => 'DeliRuntimeProcedure', 'action' => 'index'));
Router::connect('/admin/runtime-procedure/:action/*', array('controller' => 'DeliRuntimeProcedure'));

Router::connect('/admin/billings', array('controller' => 'DeliBilling', 'action' => 'index'));
Router::connect('/admin/billings/:action/*', array('controller' => 'DeliBilling'));

Router::connect('/admin/pages', array('controller' => 'DeliPage', 'action' => 'adminIndex'));
Router::connect('/admin/pages/:action/*', array('controller' => 'DeliPage'));

Router::connect('/doTracking', array('controller' => 'DeliFrontendBilling', 'action' => 'doTracking'));


CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';

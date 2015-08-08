<?php

class UserRoleRightController extends AppController {

  var $uses = array('User.UserRole');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->modelClass = 'UserRoleRight';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function generate() {

    $rights = Configure::read('User.UserRight');
    $update = false;

    foreach (App::objects('plugins') as $plugin) {
      if (in_array($plugin, array('Blocks', 'DebugKit'))) {
        continue;
      }
      $path = CakePlugin::path($plugin) . 'Controller/';
      $files = scandir($path);
      if (!isset($rights['plugin'][$plugin]['name'])) {
        $rights['plugin'][$plugin]['name'] = $plugin;
        $rights['plugin'][$plugin]['status'] = USER_FUNC_ACTIVE;
        $update = true;
      }
      foreach ($files as $file) {
        if (is_file($path . $file)) {
          $str = file_get_contents($path . $file);
          preg_match_all('/public\s+function\s+(.+)\s*\(/', $str, $funcs);
          if (count($funcs[1]) == 0) {
            continue;
          }

          $controller = str_replace('.php', '', $file);
          if (!isset($rights['plugin'][$plugin]['controller'][$controller]['name'])) {
            $rights['plugin'][$plugin]['controller'][$controller]['name'] = str_replace('Controller', '', $controller);
            $rights['plugin'][$plugin]['controller'][$controller]['status'] = USER_FUNC_ACTIVE;
          }
          foreach ($funcs[1] as $func) {
            if (in_array($func, array('beforeFilter', 'beforeRender', 'afterFilter'))) {
              continue;
            }
            if (!isset($rights['plugin'][$plugin]['controller'][$controller]['action'][$func])) {
              $rights['plugin'][$plugin]['controller'][$controller]['action'][$func]['name'] = $func;
              $rights['plugin'][$plugin]['controller'][$controller]['action'][$func]['status'] = USER_FUNC_ACTIVE;
              $update = true;
            }
          }
        }
      }
    }

    foreach (App::objects('controller') as $controller) {
      $path = APP . 'Controller/';
      $str = file_get_contents($path . $controller . '.php');
      preg_match_all('/public\s+function\s+(.+)\s*\(/', $str, $funcs);
      if (count($funcs[1]) == 0) {
        continue;
      }
      if (!isset($rights['controller'][$controller]['name'])) {
        $rights['controller'][$controller]['name'] = str_replace('Controller', '', $controller);
        $rights['controller'][$controller]['status'] = USER_FUNC_ACTIVE;
        $update = true;
      }
      foreach ($funcs[1] as $func) {
        if (in_array($func, array('beforeFilter', 'beforeRender', 'afterFilter'))) {
          continue;
        }
        if (!isset($rights['controller'][$controller]['action'][$func])) {
          $rights['controller'][$controller]['action'][$func]['name'] = $func;
          $rights['controller'][$controller]['action'][$func]['status'] = USER_FUNC_ACTIVE;
          $update = true;
        }
      }
    }

    if ($update) {
      file_put_contents(CakePlugin::path('User') . 'Config/rights.php', "<?php\n\$rights = " . str_replace('(int) ', '', Debugger::exportVar($rights, 10)) . ';');
    }

    die;
  }

  public function edit($id) {
    $this->set('role', $this->UserRole->findById($id));
    $this->set('rights', Configure::read('User.UserRight'));

    list($rolesP, $rolesC) = $this->UserRoleRight->getRightByRole($id);
    $this->set('rolesP', $rolesP);
    $this->set('rolesC', $rolesC);

    if (!empty($this->request->data)) {
      $rolesPS = array();
      if (isset($this->request->data['plugin'])) {
        foreach ($this->request->data['plugin'] as $plugin => $dataPlugin) {
          if ($this->request->data['plugin_all'][$plugin] == 1) {
            $rolesPS[$plugin] = array();
            continue;
          }
          foreach ($dataPlugin as $controller => $dataController) {
            if ($this->request->data['plugin_controller_all'][$controller] == 1) {
              $rolesPS[$plugin][$controller] = array();
              continue;
            }
            foreach ($dataController as $action => $val) {
              if ($val == 1) {
                if ($this->request->data['pluginowner'][$plugin][$controller][$action] == 1) {
                  $rolesPS[$plugin][$controller][$action] = 1;
                } else {
                  $rolesPS[$plugin][$controller][$action] = 0;
                }
              }
            }
          }
        }
      }
      if (isset($this->request->data['plugin_all'])) {
        foreach ($this->request->data['plugin_all'] as $plugin => $val) {
          if ($val == 0) {
            continue;
          }
          $rolesPS[$plugin] = array();
        }
      }

      foreach ($rolesPS as $plugin => $dataPlugin) {
        $data = array();
        $data['role_id'] = $id;
        $data['plugin'] = $plugin;
        if (count($dataPlugin) == 0) {
          if (isset($rolesP[$plugin]['id'])) {
            unset($rolesP[$plugin]);
          } else {
            $userRoleRight = new UserRoleRight();
            $userRoleRight->save($data, false);
          }
          continue;
        }
        foreach ($dataPlugin as $controller => $dataController) {
          $data = array();
          $data['role_id'] = $id;
          $data['plugin'] = $plugin;
          $data['controller'] = $controller;
          if (count($dataController) == 0) {
            if (isset($rolesP[$plugin][$controller]['id'])) {
              unset($rolesP[$plugin][$controller]);
            } else {
              $userRoleRight = new UserRoleRight();
              $userRoleRight->save($data, false);
            }
            continue;
          }
          foreach ($dataController as $action => $val) {
            $data = array();
            $data['role_id'] = $id;
            $data['plugin'] = $plugin;
            $data['controller'] = $controller;
            $data['action'] = $action;
            $data['is_owner'] = $val;
            if (isset($rolesP[$plugin][$controller][$action]['id'])) {
              $data['id'] = $rolesP[$plugin][$controller][$action]['id'];
              unset($rolesP[$plugin][$controller][$action]);
            }
            $userRoleRight = new UserRoleRight();
            $userRoleRight->save($data, false);
          }
        }
      }

      //delete
      foreach ($rolesP as $plugin => $dataPlugin) {
        if (isset($dataPlugin['id'])) {
          $this->UserRoleRight->delete($dataPlugin['id']);
          continue;
        }
        foreach ($dataPlugin as $controller => $dataController) {
          if (isset($dataController['id'])) {
            $this->UserRoleRight->delete($dataController['id']);
            continue;
          }
          foreach ($dataController as $action => $val) {
            if (isset($val['id'])) {
              $this->UserRoleRight->delete($val['id']);
            }
          }
        }
      }

      if (isset($this->request->data['controller'])) {
        $rolesCS = array();
        foreach ($this->request->data['controller'] as $controller => $dataController) {
          if ($this->request->data['controller_all'][$controller] == 1) {
            $rolesCS[$controller] = array();
            continue;
          }
          foreach ($dataController as $action => $val) {
            if ($val == 1) {
              if ($this->request->data['controllerowner'][$controller][$action] == 1) {
                $rolesCS[$controller][$action] = 1;
              } else {
                $rolesCS[$controller][$action] = 0;
              }
            }
          }
        }

        foreach ($this->request->data['controller_all'] as $controller => $val) {
          if ($val == 0) {
            continue;
          }
          $rolesCS[$controller] = array();
        }

        foreach ($rolesCS as $controller => $dataController) {
          $data = array();
          $data['role_id'] = $id;
          $data['controller'] = $controller;
          if (count($dataController) == 0) {
            if (isset($rolesC[$controller]['id'])) {
              unset($rolesC[$controller]);
            } else {
              $userRoleRight = new UserRoleRight();
              $userRoleRight->save($data, false);
            }
            continue;
          }
          foreach ($dataController as $action => $val) {
            $data = array();
            $data['role_id'] = $id;
            $data['controller'] = $controller;
            $data['action'] = $action;
            $data['is_owner'] = $val;
            if (isset($rolesC[$controller][$action]['id'])) {
              $data['id'] = $rolesC[$controller][$action]['id'];
              unset($rolesC[$controller][$action]);
            }
            $userRoleRight = new UserRoleRight();
            $userRoleRight->save($data, false);
          }
        }

        //delete
        foreach ($rolesC as $controller => $dataController) {
          if (isset($dataController['id'])) {
            $this->UserRoleRight->delete($dataController['id']);
            continue;
          }
          foreach ($dataController as $action => $val) {
            if (isset($val['id'])) {
              $this->UserRoleRight->delete($val['id']);
            }
          }
        }
      }

      $this->redirect(Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'search')));
    }
  }

}

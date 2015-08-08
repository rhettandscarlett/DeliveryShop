<?php

App::uses('AppModel', 'Model');
App::uses('ObjectModel', 'Model');
App::uses('ObjectTypeModel', 'Model');
App::uses('ObjectLinkModel', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class CodeGeneratorController extends AppController {

  /**
   * list all table in view, user can check option
   */
  public function listModel() {
    $model = new AppModel();
    $model->useTable = false;
    $tables = $model->getDataSource()->listSources();
    $folder = new Folder(ROOT . '/app/View/Themed');
    list($themes, $files) = $folder->read();
    $this->set('themes', $themes);
    $this->set('tables', $tables);
    $fileStatus = array();
    foreach ($tables as $table) {
      $modelName = $this->convertTableToClass($table);
      $fileStatus[$table] = $this->_checkFileModelStatus($modelName);
    }
    $this->set('fileStatus', $fileStatus);
  }

  /**
   * generate action from listModel
   */
  public function generate() {
    $data = $this->request->data;
    $selectedTheme = isset($data['theme']) ? $data['theme'] : '';
    unset($data['theme']);
    $tableList = $data;
    if ($tableList) {
      foreach ($tableList as $tableName => $options) {
        $isOverwrite = isset($options['overwrite']) ? true : false;
        if (isset($options['model'])) {
          $this->_generateModel($tableName, $isOverwrite, $options['plugin']);
        }
        if (isset($options['controller'])) {
          $this->_generateController($tableName, $isOverwrite, $options['plugin']);
        }
        if (isset($options['view'])) {
          $this->_generateView($tableName, $isOverwrite, $options['plugin'], $selectedTheme);
        }
      }
    }
    $this->Session->setFlash(__("Generate successfully"), 'flash/success');
    $this->redirect(Router::url(array('plugin' => 'CodeGenerator', 'controller' => 'CodeGenerator', 'action' => 'listModel'), true));
  }

  public function preview($table) {
    $modelName = $this->convertTableToClass($table);
    $fileStatus = $this->_checkFileModelStatus($modelName);
    unset($fileStatus['error']);
    $fileList = $fileStatus;
    $this->set('fileList', $fileList);
  }

  private function convertTableToClass($table) {
    return preg_replace('/\s+/', '', ucwords(str_replace('_', ' ', $table)));
  }

  private function reformatExport($str) {
    return preg_replace('/\n/', "\n  ", $str);
  }

  private function getUrl($controller, $action, $plugin = '') {
    if (!empty($plugin)) {
      return "Router::url(array('plugin' => '" . $plugin . "', 'controller' => '{$controller}', 'action' => '{$action}'))";
    } else {
      return "Router::url(array('controller' => '{$controller}', 'action' => '{$action}'))";
    }
  }

  private function removePrefix($table) {
    return preg_replace("/^{$this->project}/", '', $table);
  }

  /**
   * generate model
   * @param type $table table name from database
   * @param type $overwirte overwite if file exist 
   * @param type $plugin plugin name
   */
  private function _generateModel($table, $overwrite = false, $plugin = '') {
    $name = $this->convertTableToClass($table);
    $model = new AppModel();
    $model->useTable = $table;
    $structures = $model->getStructure();
    $model->buildValidationRule();
    if (isset($structures['columns']['object_id'])) {
      try {
        $objectType = new ObjectTypeModel();
        $dataObjectType = $objectType->findByLinkTable($table);
        if (empty($dataObjectType)) {
          $objectType = new ObjectTypeModel();
          $objectType->set('name', ucwords(str_replace('_', ' ', $table)));
          $objectType->set('link_table', $table);
          $objectType->save();
        }
      } catch (Exception $e) {
        
      }
    }
    $validate = $this->reformatExport(var_export($model->validate, true));
    $strM = "<?php

App::uses('AppModel', 'Model');

class {$name} extends AppModel {

  var \$useTable = '{$table}';
  var \$multiLanguage = null;
";
    if (isset($structures['columns']['object_id'])) {
      $strM .=
        "
  var \$primaryKey = 'object_id';
  public \$hasOne = array(
      'Object' => array(
      'className' => 'ObjectModel',
      'foreignKey' => 'id'
    )
  );
  ";
    }
    if (isset($structures['foreignKeys'])) {
      $belongTo = array();
      foreach ($structures['foreignKeys'] as $column => $ref) {
        $nameBelongClass = $this->convertTableToClass($ref['table']);
        $nameBelong = $nameBelongClass;
        if ($column == 'parent_id') {
          $nameBelong .= 'Parent';
        }
        if ($nameBelong == $name || isset($belongTo[$nameBelong])) {
          $nameBelong .= $this->convertTableToClassID($column);
        }
        $belongTo[$nameBelong] = array();
        $belongTo[$nameBelong]['className'] = $plugin ? $plugin . '.' . $nameBelongClass : $nameBelongClass;
        $belongTo[$nameBelong]['foreignKey'] = $column;
      }
      $strM .= "
  public \$belongsTo = " . $this->reformatExport(var_export($belongTo, true)) . ";
";
    }
    if (isset($structures['columns']['parent_id'])) {
      $strM .= "
  public \$actsAs = array('SFTree', 'MultiLanguage.MultiLanguage');
";
    } else {
      $strM .= "
  public \$actsAs = array('MultiLanguage.MultiLanguage');
";
    }
    $strM .= "  var \$validate = " . $validate . ';';
    $strM .= "\n}";
    $folderPath = $plugin ? ROOT . '/app/Plugin/' . $plugin . '/Model' : ROOT . '/app/Model';
    if (!is_dir($folderPath)) {
      mkdir($folderPath, 0777, true);
    }
    if (file_exists($folderPath . "/" . $name . '.php')) {
      if ($overwrite) {
        file_put_contents($folderPath . "/" . $name . '.php', $strM);
      }
    } else {
      file_put_contents($folderPath . "/" . $name . '.php', $strM);
    }
  }

  /**
   * generate controller
   * @param type $table table name from database
   * @param type $overwirte overwite if file exist 
   * @param type $plugin plugin name
   */
  private function _generateController($table, $overwrite = false, $plugin = '') {
    $name = $this->convertTableToClass($table);
    $model = new AppModel();
    $model->useTable = $table;
    $structures = $model->getStructure();
    $useObject = false;
    if (isset($structures['columns']['object_id'])) {
      $useObject = true;
    }
    $validate = $this->reformatExport(var_export($model->validate, true));
    $strC = "<?php\n";

    $strC .= "\nclass {$name}Controller extends AppController {\n";

    $uses = array();
    if ($useObject) {
      $uses[] = 'ObjectModel';
    }

    foreach ($model->inputData as $value) {
      if (isset($value['data']['table'])) {
        if ($value['data']['table'] == $table) {
          continue;
        }
        $uses[] = $plugin ? $plugin . '.' . $this->convertTableToClass($value['data']['table']) : $this->convertTableToClass($value['data']['table']);
      }
    }

    $strC .=
      "
  var \$uses = array('" . implode("','", $uses) . "');

  public function beforeFilter() {
    parent::beforeFilter();
    \$this->modelClass = '{$name}';
  }

  function beforeRender() {
    parent::beforeRender();
  }

  function afterFilter() {
    parent::afterFilter();
  }

  public function view(\$id) {
  ";
    if ($useObject) {
      $strC .="
    \$data = \$this->{$name}->findByObjectId(\$id);
    if (!empty(\$data['Object']['deleted_time'])) {
      \$this->Session->setFlash(__('This " . $this->removePrefix($name) . " has been deleted'), 'flash/error');
    }";
    } else {
      $strC .="
    \$data = \$this->{$name}->findById(\$id);
    if (!empty(\$data['{$name}']['deleted_time'])) {
      \$this->Session->setFlash(__('This " . $this->removePrefix($name) . " has been deleted'), 'flash/error');
    }";
    }
    $strC .="
    \$this->set('data', \$data);
  }

  public function edit(\$id = 0) {\n";

    foreach ($model->inputData as $key => $value) {
      if (isset($value['data']['table'])) {
        $strC .= "    \$this->set('data" . $this->convertTableToClass($key) . "'";

        $classUsed = $this->convertTableToClass($value['data']['table']);
        $modelTmp = new AppModel();
        $modelTmp->useTable = $value['data']['table'];
        $structTmp = $modelTmp->getStructure();

        if ($key == 'parent_id' || isset($structTmp['columns']['parent_id'])) {
          $strC .= ", \$this->{$classUsed}->getTreeReferenceData(\$id));\n";
        } elseif (isset($structTmp['columns']['object_id'])) {
          $strC .= ", \$this->{$classUsed}->find('list', array('joins' => array(array('table' => 'object', 'alias' => 'Object', 'type' => 'INNER', 'conditions' => array('Object.id = {$classUsed}.object_id AND Object.deleted_time IS NULL'))), 'fields' => array('{$classUsed}.object_id', '{$classUsed}.name'), 'multiLanguageIsUsed' => false)));\n";
        } else {
          $strC .= ", \$this->{$classUsed}->find('list', array('fields' => array('{$classUsed}.id', '{$classUsed}.name'), 'multiLanguageIsUsed' => false)));\n";
        }
      }
    }

    $strC .="
    if (empty(\$this->request->data)) {";
    if ($useObject) {
      $strC .="
      \$this->request->data = \$this->{$name}->findByObjectId(\$id);";
    } else {
      $strC .="
      \$this->request->data = \$this->{$name}->findById(\$id);";
    }
    $strC .= "
    } else {
      \$this->{$name}->set(\$this->request->data);
      if (\$this->{$name}->validates()) {";
    if ($useObject) {
      $strC .="
        \$object_id = \$id;
        if (\$object_id == 0) {
          \$object_id = \$this->ObjectModel->insertDataFromUseTable(\$this->{$name}->useTable);
        }
        if (\$object_id > 0) {
          \$this->request->data['{$name}']['object_id'] = \$object_id;
          if (!\$this->{$name}->save(\$this->request->data)) {
            \$this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
          } else {
            \$this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
            return \$this->redirect(" . $this->getUrl($name, 'view', $plugin) . ".'/'.\$object_id);
          }
        } else {
          \$this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
        }";
    } else {
      $strC .="
        if (!\$this->{$name}->save()) {
          \$this->Session->setFlash(__('Your changes could not be saved.'), 'flash/error');
        } else {
          \$this->Session->setFlash(__('Your changes have been saved'), 'flash/success');
          return \$this->redirect(" . $this->getUrl($name, 'view', $plugin) . ".'/'.\$this->{$name}->id);
        }";
    }
    $strC .="
      }
    }
  }

  public function delete(\$id) {
    if (\$this->{$name}->isInUsed(\$id)) {
      \$this->Session->setFlash(__('Unable to delete your data. It\'s in used'), 'flash/error');
      return \$this->redirect(\$this->referer());
    }";
    if ($useObject) {
      $strC .="

    \$this->ObjectModel->set('id', \$id);
    \$this->ObjectModel->set('deleted_time', date('Y-m-d H:i:s'));
    \$this->ObjectModel->save();";
    } else {
      $strC .="
    \$this->{$name}->deleteLogic(\$id);
      ";
    }
    $strC .="
    return \$this->redirect(" . $this->getUrl($name, 'search', $plugin) . ".'/');
  }

  public function search() {

    \$condition = array();";
    if ($useObject) {
      $strC .="
    \$condition['Object.deleted_time'] = null;";
    } else {
      $strC .="
    \$condition['{$name}.deleted_time'] = null;
      ";
    }

    if (isset($structures['columns']['parent_id'])) {
      $strC .= "
    \$dataList = \$this->{$name}->getTreeList();
    foreach (\$dataList as \$key => \$data) {
      \$dataList[\$key]->name = str_repeat('- ', 1 * \$data->depth) . \$data->name;
    }
    \$dataList = objectToArray(\$dataList);
    ";
    } else {
      $strC .="
    \$this->set('displayPaging', true);
    \$this->Paginator->settings = array(
        'conditions' => \$condition,
        'limit' => 10
    );
    \$dataList = \$this->Paginator->paginate('{$name}');
    ";
    }

    $strC .= "
    \$this->set('dataList', \$dataList);
  }
";

    $strC .= "\n}";

    $folderPath = $plugin ? ROOT . '/app/Plugin/' . $plugin . '/Controller' : ROOT . '/app/Controller';
    if (!is_dir($folderPath)) {
      mkdir($folderPath, 0777, true);
    }
    if (file_exists($folderPath . "/" . $name . 'Controller.php')) {
      if ($overwrite) {
        file_put_contents($folderPath . "/" . $name . 'Controller.php', $strC);
      }
    } else {
      file_put_contents($folderPath . "/" . $name . 'Controller.php', $strC);
    }
  }

  /**
   * generate view
   * @param type $table table name from database
   * @param type $overwirte overwite if file exist 
   * @param type $plugin plugin name
   */
  private function _generateView($table, $overwrite = false, $plugin = '', $theme) {
    $name = $this->convertTableToClass($table);
    $model = new AppModel();
    $model->useTable = $table;
    $useObject = false;
    if (isset($structures['columns']['object_id'])) {
      $useObject = true;
    }

    $strV_v = "<h3>
  <?= __('" . $this->removePrefix($name) . "' ) ?> #<?= \$data['{$name}']['id'] ?>
</h3>

<hr>
<div class=\"bs-docs-section\">
  <div class=\"bs-callout bs-callout-danger\">
";

    $inputDataTmp = array();
    foreach ($model->inputData as $key => $val) {
      if (in_array($key, array('deleted_time', 'created_account_id', 'updated_account_id', 'deleted_account_id'))) {
        continue;
      }
      $text = '';
      if (isset($val['data'])) {
        $class = $this->convertTableToClassID($val['data']['table']);
        if ($key == 'parent_id') {
          $class .= 'Parent';
        } elseif ($class == $name) {
          $class .= $this->convertTableToClassID($key);
        }
        $text .= "\$data['{$class}']['name']";
      } else {
        $text .= "\$data['{$name}']['$key']";
      }
      $strV_v .= "    <p><code><?= __('" . sfConvertField2Name($key) . "') ?></code>: <?= " . $text . " ?></p>\n";
    }

    $strV_v .="  </div>
 </div>

<hr />
";
    if ($useObject) {
      $strV_v .="
<?= \$this->Html->link(__('Back to list'), " . $this->getUrl($name, 'search', $plugin) . ".'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= \$this->Html->link(__('Edit'), " . $this->getUrl($name, 'edit', $plugin) . ".'/'.\$data['{$name}']['object_id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= \$this->Html->link(__('Delete'), " . $this->getUrl($name, 'delete', $plugin) . ".'/'.\$data['{$name}']['object_id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      ";
    } else {
      $strV_v .="
<?= \$this->Html->link(__('Back to list'), " . $this->getUrl($name, 'search', $plugin) . ".'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= \$this->Html->link(__('Edit'), " . $this->getUrl($name, 'edit', $plugin) . ".'/'.\$data['{$name}']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= \$this->Html->link(__('Delete'), " . $this->getUrl($name, 'delete', $plugin) . ".'/'.\$data['{$name}']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      ";
    }

    $strV_e = "<h3>";
    if ($useObject) {
      $strV_e .="
  <? if (isset(\$this->data['$name']['object_id']) && \$this->data['$name']['object_id'] > 0): ?>";
    } else {
      $strV_e .="
  <? if (isset(\$this->data['$name']['id']) && \$this->data['$name']['id'] > 0): ?>";
    }
    $strV_e .= "
  <?= __('Edit " . $this->removePrefix($name) . "' )  ?>: #<?= \$this->data['$name']['id'] ?>
  <? else: ?>
  <?= __('Add " . $this->removePrefix($name) . "' )  ?>
  <? endif; ?>
</h3>

<hr />

<div class=\"posts form\">
<?php
  echo \$this->Form->create('{$name}', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => false,
      'class' => 'form-control'
    ),
    'class' => 'well',
    'type' => 'file'
  ));
  ?> \n";

    foreach ($model->inputData as $key => $val) {
      if (in_array($key, array('updated_time', 'created_time', 'deleted_time', 'created_account_id', 'updated_account_id', 'deleted_account_id'))) {
        continue;
      }
      $strOption = '';
      if (isset($val['data']['table'])) {
        $strOption = ", 'options' => \$data" . $this->convertTableToClass($key);
        $strOption .= ", 'empty' => 'Bitte wählen Sie eine Kategorie'";
      }
      $strV_e .= "  <?= \$this->Form->input('{$key}', array('label' => '" . sfConvertField2Name($key) . "'{$strOption})) ?>\n";
    }

    $strV_e .="
<?php
  echo \$this->Form->input('id', array('type' => 'hidden'));
  echo \$this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-primary'));
  echo \$this->Form->end();
?>
</div>
";
    $strV_s = "<h3>
  <?= __('" . $this->removePrefix($name) . " list') ?>
</h3>
<hr>

<p><?= \$this->Html->link(__('Add'), " . $this->getUrl($name, 'edit', $plugin) . ", array('class' => 'btn btn-primary')) ?></p>

<div class='table-responsive'>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th>ID</th>\n";

    foreach (array_keys($model->inputData) as $key) {
      if (in_array($key, array('deleted_time', 'created_account_id', 'updated_account_id', 'deleted_account_id'))) {
        continue;
      }

      $strV_s .= "        <th><?= __('" . sfConvertField2Name($key) . "') ?></th>\n";
    }
    $strV_s .="
        <th class='actions'><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach (\$dataList as \$data):
        ?>
        <tr>
          <td><?= ";
    if (isset($structures['columns']['parent_id'])) {
      $strV_s .="\$data['id']";
    } else {
      $strV_s .="\$data['{$name}']['id']";
    }
    $strV_s .=" ?></td>\n";

    foreach (array_keys($model->inputData) as $key) {
      if (in_array($key, array('deleted_time', 'created_account_id', 'updated_account_id', 'deleted_account_id'))) {
        continue;
      }

      $strV_s .="          <td><?= ";
      if (!isset($structures['foreignKeys'][$key])) {
        if (isset($structures['columns']['parent_id'])) {
          $strV_s .="\$data['{$key}']";
        } else {
          $strV_s .="\$data['{$name}']['{$key}']";
        }
      } else {
        $class = $this->convertTableToClassID($structures['foreignKeys'][$key]['table']);
        if ($key == 'parent_id') {
          $class .= 'Parent';
        } elseif ($class == $name) {
          $class .= $this->convertTableToClassID($key);
        }
        if (isset($structures['columns']['parent_id'])) {
          $strV_s .="\$data['OtherRelatedData']['{$class}']['name']";
        } else {
          $strV_s .="\$data['{$class}']['name']";
        }
      }
      $strV_s .=" ?></td>\n";
    }
    if (!isset($structures['columns']['parent_id'])) {
      if ($useObject) {
        $strV_stmp = "\$data['{$name}']['object_id']";
      } else {
        $strV_stmp = "\$data['{$name}']['id']";
      }
    } else {
      if ($useObject) {
        $strV_stmp = "\$data['object_id']";
      } else {
        $strV_stmp = "\$data['id']";
      }
    }
    $strV_s .= "

          <td class='actions'>
            <?= \$this->Html->link(__('View'), " . $this->getUrl($name, 'view', $plugin) . ".'/'.{$strV_stmp}, array('class' => 'btn btn-default btn-xs')) ?>
            <?= \$this->Html->link(__('Edit'), " . $this->getUrl($name, 'edit', $plugin) . ".'/'.{$strV_stmp}, array('class' => 'btn btn-default btn-xs')) ?>
            <?= \$this->Form->postLink(__('Delete'), " . $this->getUrl($name, 'delete', $plugin) . ".'/'.{$strV_stmp}, array('class' => 'btn btn-default btn-xs'), __('Are you sure you want to delete #%s?', {$strV_stmp})) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    ";
    if (!isset($structures['columns']['parent_id'])) {
      $strV_s .= "
    <?php if (\$this->Paginator->param('pageCount') > 1): ?>
      <tfoot>
        <tr>
          <td colspan='" . (count($model->inputData) + 2) . "'>
            <?php echo \$this->Paginator->first('<<'); ?>
            <?php echo \$this->Paginator->numbers(); ?>
            <?php echo \$this->Paginator->last('>>'); ?>
          </td>
        </tr>
      </tfoot>
    <? endif; ?>\n";
    }

    $strV_s .= "
  </table>
</div>
              ";

    if ($plugin) {
      $folderPath = ROOT . '/app/Plugin/' . $plugin . '/View/' . $name;
    } elseif ($theme) {
      $folderPath = ROOT . '/app/View/Themed/' . $theme . '/' . $name;
    } else {
      $folderPath = ROOT . '/app/View/' . $name;
    }


    if (!is_dir($folderPath)) {
      mkdir($folderPath, 0777, true);
    }

    if ($overwrite) {
      file_put_contents($folderPath . '/search.ctp', $strV_s);
      file_put_contents($folderPath . '/view.ctp', $strV_v);
      file_put_contents($folderPath . '/edit.ctp', $strV_e);
    } else {
      if (!file_exists($folderPath . '/search.ctp')) {
        file_put_contents($folderPath . '/search.ctp', $strV_s);
      }
      if (!file_exists($folderPath . '/view.ctp')) {
        file_put_contents($folderPath . '/view.ctp', $strV_v);
      }
      if (!file_exists($folderPath . '/edit.ctp')) {
        file_put_contents($folderPath . '/edit.ctp', $strV_e);
      }
    }
  }

  private function _getAllPluginFolder() {
    $folder = new Folder(ROOT . '/app/Plugin');
    list($folders, $files) = $folder->read();
    return $folders;
  }

  private function _findModelFile($modelName) {
    $file = '';
    $path = ROOT . '/app/Model/' . $modelName . '.php';
    if (file_exists($path)) {
      $file = $path;
    } else {
      foreach ($this->_getAllPluginFolder() as $plugin) {
        $path = ROOT . '/app/Plugin/' . $plugin . '/Model/' . $modelName . '.php';
        if (file_exists($path)) {
          $file = $path;
        }
      }
    }
    return $file;
  }

  private function _findControllerFile($modelName) {
    $file = '';
    $path = ROOT . '/app/Controller/' . $modelName . 'Controller.php';
    if (file_exists($path)) {
      $file = $path;
    } else {
      foreach ($this->_getAllPluginFolder() as $plugin) {
        $path = ROOT . '/app/Plugin/' . $plugin . '/Controller/' . $modelName . 'Controller.php';
        if (file_exists($path)) {
          $file = $path;
        }
      }
    }
    return $file;
  }

  private function _findViewFile($modelName, $viewFile = 'view.ctp') {
    $file = '';
    $viewFile = $modelName . '/' . $viewFile;
    $path = ROOT . '/app/View/' . $viewFile;
    if (file_exists($path)) {
      $file = $path;
    } else {
      $folder = new Folder(ROOT . '/app/View/Themed');
      list($themes, $files) = $folder->read();
      foreach ($themes as $theme) {
        $path = ROOT . '/app/View/Themed/' . $theme . '/' . $viewFile;
        if (file_exists($path)) {
          $file = $path;
        }
      }
    }
    if (!$file) {
      foreach ($this->_getAllPluginFolder() as $plugin) {
        $path = ROOT . '/app/Plugin/' . $plugin . '/View/' . $viewFile;
        if (file_exists($path)) {
          $file = $path;
        }
      }
    }
    return $file;
  }

  private function _checkFileModelStatus($modelName) {
    $error = false;
    $ret = array();
    $retModel = array();
    $retController = array();
    $retView = array();
    $file = $this->_findModelFile($modelName);
    if ($file) {
      $retModel[] = $file;
    }
    $file = $this->_findControllerFile($modelName);
    if ($file) {
      $retController[] = $file;
    }

    $file = $this->_findViewFile($modelName, 'view.ctp');
    if ($file) {
      $retView[] = $file;
    }

    $file = $this->_findViewFile($modelName, 'edit.ctp');
    if ($file) {
      $retView[] = $file;
    }

    $file = $this->_findViewFile($modelName, 'search.ctp');
    if ($file) {
      $retView[] = $file;
    }
    if ($retModel || $retController || $retView) {
      $error = true;
    }
    $ret['model'] = $retModel;
    $ret['controller'] = $retController;
    $ret['view'] = $retView;
    $ret['error'] = $error;
    return $ret;
  }

}

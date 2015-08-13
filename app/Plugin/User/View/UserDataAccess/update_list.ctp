<?php
$modelList = Configure::read('User.Models');
$dataType = Configure::read('User.UserData');
$primary = $modelList[$model['UserDataAccess']['model']]['primary'];
$title = $modelList[$model['UserDataAccess']['model']]['title'];
?>
<h3>
  <?= $modelList[$model['UserDataAccess']['model']]['name'] ?>: <?= $dataType[$model['UserDataAccess']['type']] ?>
</h3>

<hr />

<div class='table-responsive'>
  <?php
  echo $this->Form->create('UserDataAccess', array(
      'novalidate' => true,
      'inputDefaults' => array(
          'div' => 'form-group',
          'wrapInput' => false,
          'class' => 'form-control'
      ),
      'class' => ''
  ));
  ?>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th></th>
        <th>ID</th>
        <th><?= __('Name') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($selectedData as $data):
        ?>
        <tr>
          <td>
            <?= $this->Form->checkbox("deletelist[{$data[$className][$primary]}]", array('name' => "deletelist[{$data[$className][$primary]}]")) ?>
          </td>
          <td><?= $data[$className][$primary] ?></td>
          <td><?= $data[$className][$title] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
  echo $this->Form->submit(__('Delete'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>
</div>


<hr>

<div class='table-responsive'>
  <?php
  echo $this->Form->create('UserDataAccess', array(
      'novalidate' => true,
      'inputDefaults' => array('label' => false, 'div' => false),
      'class' => false
  ));
  ?>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th></th>
        <th>ID</th>
        <th><?= __('Name') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($listData as $data):
        ?>
        <tr>
          <td>
            <?= $this->Form->checkbox("addlist[{$data[$className][$primary]}]", array('name' => "addlist[{$data[$className][$primary]}]")) ?>
          </td>
          <td><?= $data[$className][$primary] ?></td>
          <td><?= $data[$className][$title] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
  echo $this->Form->submit(__('Add'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>

</div>

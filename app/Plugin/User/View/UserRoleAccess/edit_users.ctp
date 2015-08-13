<h3>
  <?= __("Update User List For %s"), array("<i>{$role['UserRole']['name']}</i>") ?>
</h3>

<hr />

<div class='table-responsive'>
  <?php
  echo $this->Form->create('UserRoleAccess', array(
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
        <th><?= __('Email') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($selectedUsers as $data):
        ?>
        <tr>
          <td>
            <?= $this->Form->checkbox("deletelist[{$data['UserModel']['id']}]", array('name' => "deletelist[{$data['UserModel']['id']}]")) ?>
          </td>
          <td><?= $data['UserModel']['id'] ?></td>
          <td><?= $data['UserModel']['name'] ?></td>
          <td><?= $data['UserModel']['email'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
  echo $this->Form->submit(__('Delete'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>
</div>


<h4><?= __("Add users") ?></h4>
<div class='table-responsive'>
  <?php
  echo $this->Form->create('UserRoleAccess', array(
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
        <th><?= __('Email') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($listUsers as $data):
        ?>
        <tr>
          <td>
            <?= $this->Form->checkbox("addlist[{$data['UserModel']['id']}]", array('name' => "addlist[{$data['UserModel']['id']}]")) ?>
          </td>
          <td><?= $data['UserModel']['id'] ?></td>
          <td><?= $data['UserModel']['name'] ?></td>
          <td><?= $data['UserModel']['email'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
  echo $this->Form->submit(__('Add'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>

</div>

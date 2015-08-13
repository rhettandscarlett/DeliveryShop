<h3>
  <?= __("Update Roles For %s"), array("<i>{$user['UserModel']['name']}</i>") ?>
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
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($roles as $id => $name):
        if ($id == USER_ROLE_ANONYM) {
          continue;
        }
        ?>
        <tr>
          <td>
            <?= $this->Form->checkbox("rolelist[{$id}]", array('name' => "rolelist[{$id}]", 'checked' => isset($selectedRoles[$id]) ? 'checked' : '')) ?>
          </td>
          <td><?= $id ?></td>
          <td><?= $name ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
  echo $this->Form->submit(__('Update'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>
</div>


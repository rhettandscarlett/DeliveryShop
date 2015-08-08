<h3>
  <? if (isset($this->data['UserRoleAccess']['id']) && $this->data['UserRoleAccess']['id'] > 0): ?>
  <?= __('Edit RoleAccess' )  ?>: #<?= $this->data['UserRoleAccess']['id'] ?>
  <? else: ?>
  <?= __('Add RoleAccess' )  ?>
  <? endif; ?>
</h3>

<hr />

<div class="posts form">
<?php
  echo $this->Form->create('UserRoleAccess', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => false,
      'class' => 'form-control'
    ),
    'class' => 'well',
    'type' => 'file'
  ));
  ?> 
  <?= $this->Form->input('role_id', array('label' => 'Role', 'options' => $dataRoleId, 'empty' => 'Bitte wählen Sie eine Kategorie')) ?>
  <?= $this->Form->input('account_id', array('label' => 'Account', 'options' => $dataAccountId, 'empty' => 'Bitte wählen Sie eine Kategorie')) ?>

<?php
  echo $this->Form->input('id', array('type' => 'hidden'));
  echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
?>
</div>

<h3>
  <? if (isset($loggedUser->Admin->id) && $loggedUser->Admin->id > 0): ?>
    <?= __('Edit Information') ?>: #<?= $this->data['UserAdmin']['id'] ?>
  <? else: ?>
    <?= __('Add Admin') ?>
  <? endif; ?>
</h3>

<hr />

<div class="posts form">
  <?php
  echo $this->Form->create('UserAdmin', array(
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
  <?= $this->Form->input('name', array('label' => 'Name')) ?>
  <?= $this->Form->input('email', array('label' => 'Email')) ?>
  <?= $this->Form->input('password', array('label' => 'Password', 'autocomplete' => 'off')) ?>
  <?= $this->Form->input('status', array('label' => 'Status', 'options' => Configure::read('User.AdminStatus'))) ?>

  <?php
  echo $this->Form->input('id', array('type' => 'hidden'));
  echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-inverse'));
  echo $this->Form->end();
  ?>
</div>

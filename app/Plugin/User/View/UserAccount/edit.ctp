<h3>
  <? if (isset($this->data['UserAccount']['id']) && $this->data['UserAccount']['id'] > 0): ?>
  <?= __('Edit Account' )  ?>: #<?= $this->data['UserAccount']['id'] ?>
  <? else: ?>
  <?= __('Add Account' )  ?>
  <? endif; ?>
</h3>

<hr />

<div class="posts form">
<?php
  echo $this->Form->create('UserAccount', array(
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
  <?= $this->Form->input('user_id', array('label' => 'User', 'options' => $dataUserId, 'empty' => 'Bitte wählen Sie eine Kategorie')) ?>
  <?= $this->Form->input('password', array('label' => 'Password')) ?>
  <?= $this->Form->input('password_hint', array('label' => 'Password Hint')) ?>
  <?= $this->Form->input('reset_token_password', array('label' => 'Reset Token Password')) ?>
  <?= $this->Form->input('reset_token_time', array('label' => 'Reset Token Time')) ?>
  <?= $this->Form->input('last_login', array('label' => 'Last Login')) ?>
  <?= $this->Form->input('number_attempt', array('label' => 'Number Attempt')) ?>
  <?= $this->Form->input('status', array('label' => 'Status')) ?>
  <?= $this->Form->input('oauth_uid', array('label' => 'Oauth Uid')) ?>
  <?= $this->Form->input('oauth_provider', array('label' => 'Oauth Provider')) ?>
  <?= $this->Form->input('oauth_data', array('label' => 'Oauth Data')) ?>

<?php
  echo $this->Form->input('id', array('type' => 'hidden'));
  echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
?>
</div>

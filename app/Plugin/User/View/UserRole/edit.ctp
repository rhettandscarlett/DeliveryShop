<h3>
  <? if (isset($this->data['UserRole']['id']) && $this->data['UserRole']['id'] > 0): ?>
  <?= __('Edit Role' )  ?>: #<?= $this->data['UserRole']['id'] ?>
  <? else: ?>
  <?= __('Add Role' )  ?>
  <? endif; ?>
</h3>

<hr />

<div class="posts form">
<?php
  echo $this->Form->create('UserRole', array(
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
  <?= $this->Form->input('description', array('label' => 'Description')) ?>

<?php
  echo $this->Form->input('id', array('type' => 'hidden'));
  echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
?>
</div>

<h3>
  <?= __('Add data access') ?>: <i><?= $user['UserModel']['name'] ?></i>
</h3>

<hr />

<div class="posts form">
  <?php
  echo $this->Form->create('UserDataAccess', array(
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
  <?= $this->Form->input('model', array('label' => 'Data', 'options' => $modelList, 'empty' => 'Bitte wählen Sie eine Kategorie')) ?>
  <?= $this->Form->input('type', array('label' => 'Type', 'options' => Configure::read('User.UserData'))) ?>

  <?php
  echo $this->Form->input('id', array('type' => 'hidden'));
  echo $this->Form->submit(__('Add'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>
</div>

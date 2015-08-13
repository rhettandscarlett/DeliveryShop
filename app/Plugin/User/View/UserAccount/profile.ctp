<div class="container">
  <h3>My Profile</h3>
  <?php
  echo $this->Form->create('UserModel', array(
    'novalidate' => true,
    'url' => '/user/account/profile',
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => array('class' => 'col-sm-5'),
      'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
  ));
  ?>
  <?php 
  if($this->data['UserModel']['email']){
    echo $this->Form->input('email', array(
    'label' => array('text' => 'Email', 'class' => 'col-sm-3 control-label'),
    'disabled' => TRUE,
    ));
  }else{
    echo $this->Form->input('email', array(
    'label' => array('text' => 'Email', 'class' => 'col-sm-3 control-label'),
    ));
  }
  
  ?>
  <?php echo $this->Form->input('name', array(
    'label' => array('text' => 'Name', 'class' => 'col-sm-3 control-label'),
    'placeholder' => __('Name'),
    ));
  ?>
  <?php echo $this->Form->input('UserAccount.password', array(
    'value' => '',
    'label' => array('text' => 'Password', 'class' => 'col-sm-3 control-label'),
    'placeholder' => __('New Password')
  )); ?>
  <?php echo $this->Form->input('UserAccount.password_confirmation', array(
    'type' => 'password',
    'value' => '',
    'label' => array('text' => 'Password Confirmation', 'class' => 'col-sm-3 control-label'),
    'placeholder' => __('New Password Confirm')
  )); ?>

  <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => FALSE, )); ?>

  <div class="form-group">
    <div class = 'col-sm-offset-3 col-sm-5'>
      <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-default')); ?>
    </div>
  </div>

  <?php echo $this->Form->end(); ?>

  <div class="form-group">
    <div class = 'col-sm-offset-3 col-sm-5'>
      <small>
            (*) <?= __("Leave password and password confirmation to blank if you don't want to change your password.") ?>
      </small>
    </div>
  </div>
</div>

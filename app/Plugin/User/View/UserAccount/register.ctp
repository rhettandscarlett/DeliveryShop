<div class="container">
  <h3>Register New Account</h3>

  <?php
  echo $this->Form->create('UserModel', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => array('class' => 'col-sm-5'),
      'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
  ));
  ?>
  <?php echo $this->Form->input('email', array(
    'label' => array('text' => 'Email', 'class' => 'col-sm-3 control-label'),
    'type' => 'email',
    'placeholder' => __('Email'),
    ));
  ?>
  <?php echo $this->Form->input('name', array(
    'label' => array('text' => 'Name', 'class' => 'col-sm-3 control-label'),
    'placeholder' => __('Name'),
    ));
  ?>
  <?php echo $this->Form->input('address', array(
    'label' => array('text' => 'Address', 'class' => 'col-sm-3 control-label'),
    'placeholder' => __('Address'),
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
    'placeholder' => __('New Password Confirmation')
  )); ?>
  <?php echo $this->Form->input('UserAccount.password_hint', array(
    'label' => array('text' => 'Password Hint', 'class' => 'col-sm-3 control-label'),
    'placeholder' => __('Password hint')
  )); ?>

  <div class="form-group">
    <div class = 'col-sm-offset-3 col-sm-5'>
      <?php echo $this->Form->submit(__('Register'), array('class' => 'btn btn-default')); ?>
    </div>
  </div>

  <?php echo $this->Form->end(); ?>
</div>

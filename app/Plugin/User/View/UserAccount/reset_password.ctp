<div class="container">
  <h3>Reset your password</h3>
  <hr>
  <div class="message"><?php echo $this->Session->flash(); ?></div>

  <?php
  echo $this->Form->create('UserModel', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => array('class' => 'col-sm-7'),
      'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
  ));
  ?>
  <?php echo $this->Form->input('password', array('label' => array('text' => 'New Password', 'class' => 'col-sm-5 control-label'), 'placeholder' => __('New Password'))); ?>
  <?php echo $this->Form->input('password_confirmation', array('type' => 'password', 'label' => array('text' => 'Password Confirmation', 'class' => 'col-sm-5 control-label'), 'placeholder' => __('New Password'))); ?>
  <div class="form-group">
    <div class = 'col-sm-offset-5 col-sm-10'>
      <?php echo $this->Form->submit(__('Send'), array('class' => 'btn btn-default')); ?>
    </div>
  </div>

  <?php echo $this->Form->end(); ?>
</div>

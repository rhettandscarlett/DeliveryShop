<div class="container">
  <h3>Login to your account</h3>
  <hr>
  <div class="message"><?php echo $this->Session->flash(); ?></div>

  <?php
  echo $this->Form->create('UserAdmin', array(
      'novalidate' => true,
      'inputDefaults' => array(
          'div' => 'form-group',
          'wrapInput' => array('class' => 'col-sm-5'),
          'class' => 'form-control'
      ),
      'class' => 'form-horizontal'
  ));
  ?>
  <?php echo $this->Form->input('email', array('label' => array('text' => 'Email', 'class' => 'col-sm-2 control-label'), 'placeholder' => __('Email'))); ?>
  <?php echo $this->Form->input('password', array('label' => array('text' => 'Password', 'class' => 'col-sm-2 control-label'), 'placeholder' => __('Password'))); ?>
  <div class="form-group">
    <div class = 'col-sm-offset-2 col-sm-5'>
      <?php echo $this->Form->submit(__('Sign In'), array('class' => 'btn btn-default')); ?>
    </div>
  </div>
  <?php echo $this->Form->end(); ?>
</div>

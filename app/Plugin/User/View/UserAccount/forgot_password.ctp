<div class="container">
  <h3>Forgot password</h3>
  <hr>
  <div class="message"><?php echo $this->Session->flash(); ?></div>

  <?php
  echo $this->Form->create('UserModel', array(
      'novalidate' => true,
      'inputDefaults' => array(
          'div' => 'form-group',
          'wrapInput' => array('class' => 'col-sm-10'),
          'class' => 'form-control'
      ),
      'class' => 'form-horizontal'
  ));
  ?>
  <div class="form-group">
    <div class = 'col-sm-offset-2 col-sm-10'>
      Enter your email to receive recovery password link.
    </div>
  </div>
  <?php echo $this->Form->input('email', array('label' => array('text' => 'Email', 'class' => 'col-sm-2 control-label'), 'placeholder' => __('Email'))); ?>
  <div class="form-group">
    <div class = 'col-sm-offset-2 col-sm-10'>
      <?php echo $this->Form->submit(__('Send'), array('class' => 'btn btn-default')); ?>
    </div>
  </div>

  <?php echo $this->Form->end(); ?>
</div>

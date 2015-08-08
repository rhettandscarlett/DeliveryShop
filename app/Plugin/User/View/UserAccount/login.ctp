<div class="container">
  <h3>Login to your account</h3>
  <hr>
  <div class="message"><?php echo $this->Session->flash(); ?></div>

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
  <?php echo $this->Form->input('email', array('label' => array('text' => 'Email', 'class' => 'col-sm-2 control-label'), 'placeholder' => __('Email'))); ?>
  <?php echo $this->Form->input('password', array('label' => array('text' => 'Password', 'class' => 'col-sm-2 control-label'), 'placeholder' => __('Password'))); ?>
  <div class="form-group">
    <div class = 'col-sm-offset-2 col-sm-5'>
      <?php echo $this->Form->submit(__('Sign In'), array('class' => 'btn btn-default')); ?>
    </div>
  </div>
  <div class="form-group">
    <div class = 'col-sm-offset-2 col-sm-5'>
      <?php echo $this->Html->link('Forgot password', '/user/account/forgotPassword'); ?>
    </div>
  </div>
  
  <div class="form-group">
    <div class = 'col-sm-offset-2 col-sm-5'>
      <?php echo $this->Html->image('facebook-login.jpg', array('alt' => 'Login via facebook', 'url' => Router::url(array('plugin' => 'User', 'controller' => 'OAuth', 'action' => 'fbLogin'))));?>
      <?php echo $this->Html->image('google-login.jpg', array('alt' => 'Login via google plus', 'url' => Router::url(array('plugin' => 'User', 'controller' => 'OAuth', 'action' => 'googleLogin'))));?>
      <?php echo $this->Html->image('twitter-login.jpg', array('alt' => 'Login via twitter', 'url' => Router::url(array('plugin' => 'User', 'controller' => 'OAuth', 'action' => 'twitterLogin'))));?>
    </div>
  </div>

  <?php echo $this->Form->end(); ?>
</div>

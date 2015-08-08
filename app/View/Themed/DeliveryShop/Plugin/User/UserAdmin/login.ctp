<div id="page-container">
  <div id="page-content">
    <div class="login-header text-center"><a href="/" rel="nofollow"><?php echo $this->Html->image('logo.png', array("alt" => __("PTI Express Online"))); ?></a></div>
    <div class="login-message"><?php echo $this->Layout->sessionFlash(); ?></div>
    <div class="layouts form">
      <?php
      echo $this->Form->create('UserAdmin', array(
        'novalidate' => true,
        'inputDefaults' => array(
          'div' => 'form-group',
          'wrapInput' => false,
          'class' => 'form-control'
        ),
        'class' => 'well login'
      ));
      ?>
      <h3><?php echo __('Administration panel'); ?></h3>
      <hr />
      <?php echo $this->Form->input('email', array('label' => false, 'placeholder' => __('Email'), 'class' => 'form-control')); ?>
      <?php echo $this->Form->input('password', array('label' => false, 'placeholder' => __('Password'), 'class' => 'form-control', 'autocomplete' => 'off')); ?>
      <?php //echo $this->Form->input('remember_me', array('label' => false, 'type' => 'checkbox', 'data-label' => __('Remember Me'), 'class' => 'checkbox')); ?>
      <?php //echo $this->Form->hidden('return_to', array('value' => $return_to)); ?>
      <?php
      echo $this->Form->input(__('remember_me'), array(
        'label' => __('Remember Me'), 'type' => 'checkbox', 'class' => 'checkbox',
        'beforeInput' => '<div class="input-group">',
        'afterInput' => '<span class="input-group-btn"><input class="btn btn-large btn-inverse" type="submit" value="' . __("Log In") . '"></span></div>',
      ));
      ?>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>

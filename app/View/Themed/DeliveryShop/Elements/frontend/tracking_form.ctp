<?php
echo $this->Layout->sessionFlash();
echo $this->Form->create('TrackingForm', array(
  'type' => 'GET',
  'url' => Router::url(array('plugin' => false, 'controller' => 'DeliFrontendBilling', 'action' => 'doTracking')),
  'novalidate' => true,
  'inputDefaults' => array(
    'class' => 'form-control'
  ),
));
?>

<div class="page-body">
  <?php echo $this->Form->input('bill_code', array('type' => 'textarea', 'class' => 'form-control tracking-input', 'label' => false)); ?>
  <?php echo $this->Form->button(__('Tracking'), array('class' => 'btn btn-inverse tracking-btn', 'type' => 'submit', 'escape' => false)); ?>
</div>

<?php
echo $this->Form->end();
?>
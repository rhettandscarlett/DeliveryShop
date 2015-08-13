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
  <?php echo $this->Form->input('bill_code', array('type' => 'textarea', 'style' => 'width:99%;height:100px' ,'label' => false)); ?>
  <?php echo $this->Form->button(__('Tracking'), array('class' => 'btn btn-inverse', 'style' => 'background-color:#993366;border:#FFFFFF;color:#FFFFFF;font-weight:bold;margin:7px 0;', 'type' => 'submit', 'id' => 'search', 'escape' => false)); ?>
</div>

<?php
echo $this->Form->end();
?>
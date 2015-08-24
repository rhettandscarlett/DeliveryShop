<div class="w504">
  <div class="jia_ge">

    <?= $this->Html->image('logistics.png') ?>

  </div>

</div>
<?= $this->element('frontend/attach_partner', array('style' => 'width:30px;height:14px')) ?>

<?php
echo $this->Form->create('ContactForm', array(
  'url' => '',
  'novalidate' => true,
  'inputDefaults' => array(
    'div' => 'form-group required',
    'label' => array(
      'class' => 'col col-md-3 control-label text-left'
    ),
    'wrapInput' => 'col col-md-9',
    'class' => 'form-control'
  ),
  'type' => 'file'
));
?>

<div class="page-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-horizontal posts form">
        <br>
        <br>
        <br>
        <div class="form-group">
          <div class="col col-md-9 col-md-offset-3">
            <h2><label><span><?= __('Contact For Services')?></span></label></h2>
          </div>
        </div>
        <?php echo $this->Form->input('fullname', array('label' => array('text' => __('Full Name')))); ?>
        <?php echo $this->Form->input('phone', array('label' => array('text' => __('Phone')))); ?>
        <?php echo $this->Form->input('title', array('label' => array('text' => __('Title')))); ?>
        <?php echo $this->Form->input('email', array('label' => array('text' => __('Email')))); ?>
        <?php echo $this->Form->input('content', array('type' => 'textarea', 'style' => 'min-height:200px;', 'label' => array('text' => __('Content')))); ?>
        <?php echo $this->Form->button(__('Submit'), array('class' => 'btn btn-primary pull-right', 'type' => 'submit', 'id' => 'btn-submit', 'escape' => false)); ?>
      </div>
    </div>
  </div>
</div>

<?php
echo $this->Form->end();
?>
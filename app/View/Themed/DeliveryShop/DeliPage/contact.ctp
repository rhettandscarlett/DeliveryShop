<div class="w504">
  <div class="jia_ge">

    <?= $this->Html->image('logistics.png') ?>

  </div>

</div>
<div class="f_links">
  <dl>
    <dt><i></i><?= __('Partners')?> : </dt>
    <dd><a href="http://www.fedex.com" target="_blank" title="FedEx"><?= $this->Html->image('2013-12-28-19-31-3956.gif', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    <dd><a href="http://www.dhl.com" target="_blank" title="DHL"><?= $this->Html->image('2013-12-28-19-32-3261.gif', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    <dd><a href="http://www.ups.com" target="_blank" title="Ems"><?= $this->Html->image('2013-12-28-19-33-110.gif', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    <dd><a href="http://www.tnt.com" target="_blank" title="TNT"><?= $this->Html->image('2013-12-28-19-37-2285.jpg', array('style' => 'width:76px;height:30px' )) ?></a></dd>

  </dl>
</div>

<?php
echo $this->Form->create('ContactForm', array(
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
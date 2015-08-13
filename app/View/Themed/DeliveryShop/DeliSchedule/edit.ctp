<?php
echo $this->Form->create('DeliSchedule', array(
  'novalidate' => true,
  'inputDefaults' => array(
    'div' => 'form-group',
    'label' => array(
      'class' => 'col col-md-3 control-label text-left'
    ),
    'wrapInput' => 'col col-md-9',
    'class' => 'form-control'
  ),
  'type' => 'file'
));
?>
  <div class="page-title" id="page-title">
    <div class="row">
      <div class="col-md-9">
        <h1><i class="fa fa-bars"></i>
          <?php if (isset($this->data['DeliSchedule']['id']) && $this->data['DeliSchedule']['id'] > 0): ?>
            <?php echo __('Edit') ?>: <?= $this->data['DeliSchedule']['name'] ?>
          <?php else: ?>
            <?php echo __('Add') ?>
          <?php endif; ?>
        </h1>
        <h4></h4>
      </div>
      <div class="col-md-3 text-right">
        <?php echo $this->Form->button('<i class="fa fa-save"></i> ' . __('Save'), array('class' => 'btn btn-inverse', 'type' => 'submit', 'id' => 'btn-save', 'escape' => false)); ?>
        <?php echo $this->Html->link(__('Cancel'), Router::url(array("action" => "index")) . buildQueryString(), array('class' => 'btn btn-cancel')); ?>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="row">
      <div class="col-md-12">
        <div class="well form-horizontal posts form">
          <div class="form-group">
            <div class="col col-md-9 col-md-offset-3">
              <label class="required"><?php echo __('Required Fields'); ?></label>
            </div>
          </div>
          <?php echo $this->Form->input('name', array('label' => array('text' => __('Name')))); ?>
          <?php echo $this->Form->input('activated', array('options' => Configure::read('DELI_ACTIVATED_STATUS'), 'label' => array('text' => __('Activate')))); ?>
        </div>
      </div>
    </div>
  </div>

<?php
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end();
?>
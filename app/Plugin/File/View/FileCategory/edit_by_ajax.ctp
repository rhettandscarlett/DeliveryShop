<div id="errorSaving"></div>
<?php
echo $this->Form->create('FileCategory', array(
  'novalidate' => true,
  'inputDefaults' => array(
    'div' => 'form-group',
    'label' => array(
      'class' => 'col col-md-3 control-label text-left'
    ),
    'wrapInput' => 'col col-md-9',
    'class' => 'form-control'
  ),
  'type' => 'file',
  'onsubmit' => 'ajaxPressSubmitForm(this)'
));
?>

  <div class="page-title" id="page-title">
    <div class="row">
      <div class="col-md-9">
        <h1><i class="fa fa-bars"></i>
          <?php if (isset($this->data['FileCategory']['id']) && $this->data['FileCategory']['id'] > 0): ?>
            <?php echo __('Edit') ?>: <?= $this->data['FileCategory']['name'] ?>
          <?php else: ?>
            <?php echo __('Add') ?>
          <?php endif; ?>
        </h1>
        <h4></h4>
      </div>
      <div class="col-md-3 text-right">
        <?php echo $this->Form->button('<i class="fa fa-save"></i> ' . __('Save'), array('class' => 'btn btn-inverse', 'type' => 'submit', 'id' => 'btn-save', 'escape' => false)); ?>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="row">
      <div class="col-md-12">
        <div class="well form-horizontal posts form">
          <div class="form-group">
            <div class="col col-md-9 col-md-offset-3">
              <label class="required"><?php echo __('Required fields'); ?></label>
            </div>
          </div>
          <?php echo $this->Form->input('parent_id', array('label' => array('text' => __('Parent')), 'options' => $dataParentId, 'value' => $folderId, 'empty' => __("Please select one"))) ?>
          <?php echo $this->Form->input('name', array('label' => array('text' => __('Name')))) ?>
<!--          --><?php //echo $this->Form->input('order', array('label' => array('text' => __("Order")), 'type' => 'text')) ?>
        </div>
      </div>
    </div>
  </div>

<?php
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end();
?>
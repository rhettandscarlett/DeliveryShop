<div class="form-horizontal page-body posts form">
  <div id="WindowshopLogin"></div>
  <?php
  echo $this->Form->create('SeoRoute', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => array('class' => 'col-sm-9'),
      'class' => 'form-control'
    ),
    'class' => 'form-horizontal',
    'onsubmit' => 'ajaxPressSubmitForm(this)'
  ));
  ?>
  <?php echo $this->Form->input('title', array('label' => array('text' => __('Titel'), 'class' => 'col-sm-3 control-label'), 'placeholder' => __('Title'))); ?>
  <?php echo $this->Form->input('keywords', array('label' => array('text' => __('Keywords'), 'class' => 'col-sm-3 control-label'), 'placeholder' => __('Keywords'))); ?>
  <?php echo $this->Form->input('description', array('label' => array('text' => __('Description'), 'class' => 'col-sm-3 control-label'), 'placeholder' => __('Description'))); ?>
  <?php echo $this->Form->input('slug', array('type' => 'text',  'label' => array('text' => __('Slug'), 'class' => 'col-sm-3 control-label'), 'placeholder' => __('Slug'))); ?>
  <div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-9" style="margin: -10px 0px 5px 0px">
      <i><?= __('Slug must be started with /') ?> </i><br/>
      <i><?= __('For Example') ?>:</i> &nbsp;<b>/all_products</b>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-9">
      <?php echo $this->Form->button(__('Save'), array('name' => 'save', 'class' => 'btn btn-primary', 'type' => 'submit', 'escape' => false)); ?>
      <?php echo $this->Form->button(__('Delete'), array('name' => 'delete', 'class' => 'btn btn-danger', 'type' => 'submit', 'escape' => false)); ?>
    </div>
  </div>
  <?php echo $this->Form->end(); ?>

<?php
echo $this->Form->create('MultiLanguageModel', array(
  'novalidate' => true,
  'inputDefaults' => array(
    'div' => 'form-group',
    'wrapInput' => false,
    'class' => 'form-control'
  ),
  'class' => 'well',
  'type' => 'file'
));
?>

<div class="page-title" id="page-title">
  <div class="row">
    <div class="col-md-6">
      <h1><i class="fa fa-bars"></i>
        <?= __('Import CMS translation') ?>
      </h1>
      <h4></h4>
    </div>
    <div class="col-md-6 text-right">
      <?php
      echo $this->Form->button(__('Save'), array('class' => 'btn btn-inverse', 'type' => 'submit'));
      ?>
    </div>
  </div>
</div>

<div class="well form-horizontal page-body posts form">
  <?=
  $this->Form->input('MultilanguageImportCms', array('label' => array('text' => __('Select a file to import')), 'type' => 'file', 'name' => 'MultilanguageImportCms'))
  ?>
</div>

<?php
echo $this->Form->end();
?>
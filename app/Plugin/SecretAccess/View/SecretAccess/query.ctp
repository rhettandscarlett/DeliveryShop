<?php
echo $this->Form->create('SecretAccessQuery', array(
'novalidate' => true,
'inputDefaults' => array(
'div' => 'form-group',
'label' => array(
'class' => 'col col-md-3 control-label text-left'
),
'wrapInput' => 'col col-md-9',
'class' => 'form-control'
)
));
?>
<div class="page-title">
  <div class="row">
    <div class="col-md-9">
      <h4></h4>
      <h1><i class="fa fa-bars"></i>
        Write Query. Be careful !
      </h1>
    </div>
    <div class="col-md-3 text-right">
      <?php echo $this->Form->button('<i class="fa fa-save"></i> ' . __('Sure Run ?'), array('class' => 'btn btn-inverse', 'type' => 'submit', 'escape' => false)); ?>
    </div>
  </div>
</div>


<div class="well form-horizontal page-body posts form">
  <?php if (isset($error)) :?>
    <div class="text-danger">
      <?php debug($error);?>
    </div>
  <?php endif; ?>
  <?php if (isset($res)) :?>
    <div class="text-success">
      <?php debug($res);?>
    </div>
  <?php endif; ?>

  <?= $this->Form->input('query', array('label' => array('text' => __('Query')), 'type' => 'textarea', 'style' => 'margin: 0px -2px 0px 0px; width: 811px; height: 354px;')) ?>
</div>
<?php
echo $this->Form->end();
?>
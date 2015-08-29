<?php
$estimateDays = array();
for ($i = 0; $i <= 10; $i++) {
  $estimateDays[] = $i;
}

$this->HTML->script('billing', array('inline' => false));
echo $this->Form->create('DeliBilling', array(
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
          <?php if (isset($this->data['DeliBilling']['id']) && $this->data['DeliBilling']['id'] > 0): ?>
            <?php echo __('Edit Bill Code') ?>: <?= $this->data['DeliBilling']['bill_code'] ?>
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
          <?php echo $this->Form->input('schedule_id', array('options' => $scheduleList, 'value' => $scheduleId ,'label' => array('text' => __('Schedule For This Bill')))); ?>
          <?php echo $this->Form->input('bill_code', array('label' => array('text' => __('Billing Code')))); ?>
          <?php echo $this->Form->input('bill_name', array('label' => array('text' => __('Signed For By')))); ?>
          <?php echo $this->Form->input('description', array('label' => array('text' => __('Description')), 'style' => 'min-height: 100px;')); ?>

          <div class="form-group">
            <div class="col-md-3 required"><label><?= __('Picked up Date') ?></label></div>
            <div class='col-md-9'>
              <div class="input-group date" id="datetimepicker1">
                <input type='text' name="data[DeliBilling][picked_up_date]"
                       data-value="<?= isset($this->data['DeliBilling']['picked_up_date']) ? date('d/m/Y', strtotime($this->data['DeliBilling']['picked_up_date'])) : null ?>"
                       placeholder="<?= __('Picked up Date') ?>" class="form-control" data-date-format="DD/MM/YYYY"/>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
              </div>
            </div>
          </div>
          <?php echo $this->Form->input('init_time', array('label' => array('text' => __('Picked up Time')), 'value' => empty($this->data['DeliBilling']['init_time']) ? $defaultProcedure['DeliDefaultLocationProcedure']['time'] : $this->data['DeliBilling']['init_time'])); ?>
          <?php echo $this->Form->input('estimate_day', array('options' => $estimateDays, 'label' => array('text' => __('Estimate Days')), 'default' => empty($this->data['DeliBilling']['estimate_day']) ? 4 : $this->data['DeliBilling']['estimate_day'])); ?>
          <?php echo $this->Form->input('status', array('options' => $transitStatusList, 'label' => array('text' => __('Status')))); ?>
          <div class="form-group">
            <label class="col col-md-3 control-label text-left"><?php echo __('Add Procedures for Delivered Location:'); ?></label>
            <div class="col col-md-9">
              <?php echo $this->Form->input('DeliBillingRuntimeLocation.location_id', array('options' => $attachLocations, 'default' => !empty($this->data['DeliBillingRuntimeLocation']['location_id']) ? $this->data['DeliBillingRuntimeLocation']['location_id'] : null ,'empty' => __('Please choose one') ,'label' => false)); ?>
              <br>
              <div><?= __('Runtime Procedure List')?></div>
              <?php foreach ($attachLocationsData as $locationId => $listProcedureData): ?>
                <div class="selectedHolder selectedHolder<?= $locationId ?> <?= ($locationId == $this->data['DeliBillingRuntimeLocation']['location_id']) ? '' : 'hidden'?> " style="padding: 10px;">
                  <?php foreach($listProcedureData as $procedureData): ?>
                    <?php $stored = in_array($procedureData['id'], array_keys($storedRuntimePros));?>
                    <?php $checked = ($stored) ? 'checked' : '' ?>
                    <?php echo $this->Form->input('DeliBillingRuntimeProcedure.' . $procedureData['id'] . '.id', array('type' => 'hidden', 'value' => $stored ? $storedRuntimePros[$procedureData['id']] : '')); ?>
                    <?php echo $this->Form->input('DeliBillingRuntimeProcedure.' . $procedureData['id'] . '.runtime_procedure_id', array($checked, 'value' => $procedureData['id'], 'data-id' => $procedureData['id'],'type' => 'checkbox', 'div' => false, 'class' => 'rtProcedureItem' ,'wrapInput' => false ,'label' => array('class' => 'col col-md-6 control-label text-left', 'text' => $procedureData['name']))); ?>
                    <br>
                    <div class="detailHolder detailHolder<?= $procedureData['id'] ?> <?= $stored ? '' : 'hidden' ?>">
                      <?php echo $this->Form->input('DeliBillingRuntimeProcedure.' . $procedureData['id'] . '.time', array('value' => $stored ? $storedRuntimeProsData[$storedRuntimePros[$procedureData['id']]]['time'] : '', 'type' => 'text', 'wrapInput' => 'col col-md-3','label' => array('text' => __('Local Time')))); ?>
                      <?php echo $this->Form->input('DeliBillingRuntimeProcedure.' . $procedureData['id'] . '.plus_day', array('value' => $stored ? $storedRuntimeProsData[$storedRuntimePros[$procedureData['id']]]['plus_day'] : 0, 'options' => $plusDay,'wrapInput' => 'col col-md-3','label' => array('text' => __('Plus day')))); ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end();
?>
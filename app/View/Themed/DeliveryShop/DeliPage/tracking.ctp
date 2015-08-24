<?= $this->element('frontend/attach_partner', array('style' => 'width:30px;height:14px')) ?>
<div class="jia_ge">
  <div class="form-group">
    <div class="col-md-3"><label style="font-size: 17px;"><?= __('Track Shipments')?></label></div>
    <div class='col-md-9'>
      <?= $this->element('frontend/tracking_form') ?>
    </div>
  </div>

</div>
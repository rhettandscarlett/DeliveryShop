<div class="f_links">
  <dl>
    <dt><?= __('Partners')?> : </dt>
    <dd><a href="http://www.fedex.com" target="_blank" title="FedEx"><?= $this->Html->image('2013-12-28-19-31-3956.gif', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    <dd><a href="http://www.dhl.com" target="_blank" title="DHL"><?= $this->Html->image('2013-12-28-19-32-3261.gif', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    <dd><a href="http://www.ups.com" target="_blank" title="Ems"><?= $this->Html->image('2013-12-28-19-33-110.gif', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    <dd><a href="http://www.tnt.com" target="_blank" title="TNT"><?= $this->Html->image('2013-12-28-19-37-2285.jpg', array('style' => 'width:76px;height:30px' )) ?></a></dd>
    
  </dl>
</div>

<div class="jia_ge">
  <div class="form-group">
    <div class="col-md-3"><label style="font-size: 17px;"><?= __('Track Shipments')?></label></div>
    <div class='col-md-9'>
      <?= $this->element('frontend/tracking_form') ?>
    </div>
  </div>

</div>
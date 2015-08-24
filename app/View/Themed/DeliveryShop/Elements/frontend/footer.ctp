<footer class="main-footer">
  <div class="about-box-wrap">
    <div class="container">
      <div class="row">
        <div class="f_links">
          <dl>
            <dt><?= __('Partners')?> : </dt>
            <dd><a href="http://www.fedex.com" target="_blank" title="FedEx"><?= $this->Html->image('2013-12-28-19-31-3956.gif', array('style' => 'width:50px;height:24px' )) ?></a></dd>
            <dd><a href="http://www.dhl.com" target="_blank" title="DHL"><?= $this->Html->image('2013-12-28-19-32-3261.gif', array('style' => 'width:50px;height:24px' )) ?></a></dd>
            <dd><a href="http://www.ups.com" target="_blank" title="Ems"><?= $this->Html->image('2013-12-28-19-33-110.gif', array('style' => 'width:50px;height:24px' )) ?></a></dd>
            <dd><a href="http://www.tnt.com" target="_blank" title="TNT"><?= $this->Html->image('2013-12-28-19-37-2285.jpg', array('style' => 'width:50px;height:24px' )) ?></a></dd>
          </dl>
        </div>
        <div class="f_nav">
          <?php foreach($menuList as $menuKey => $menuName): ?>
            <a href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliPage', 'action' => 'index', $menuKey)) ?>"><?= $menuName?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="ft-copy">
    <div class="container">
      <div class="row">
        <div class="pull-left"><?= __('Copyright © 2015 &nbsp; ptivn.com')?></div>
      </div>
    </div>
  </div>
</footer>
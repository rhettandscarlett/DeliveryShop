<footer class="main-footer">
  <div class="about-box-wrap">
    <div class="container">
      <div class="row">
        <?= $this->element('frontend/attach_partner', array('style' => 'width:50px;height:24px')) ?>
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
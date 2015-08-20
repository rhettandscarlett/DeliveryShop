<?= $this->element('frontend/search_box') ?>
<div class="nav_bg">
  <div id="navmenu">
    <ul class="menu">
      <?php foreach($menuList as $menuKey => $menuName): ?>
        <li><a class="<?= ($currentPage == $menuKey) ? 'text-success' : '' ?>" href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliPage', 'action' => 'index', $menuKey)) ?>"><span><?= $menuName?></span></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="zhen_qing"></div>
  </div>
</div>

<div class="nav_bg">
  <div id="navmenu">
    <ul class="menu">
      <?php foreach($menuList as $menuKey => $menuName): ?>
        <li><a class="<?= ($currentPage == $menuKey) ? 'pointing' : '' ?>" href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliPage', 'action' => 'index', $menuKey)) ?>"><span><?= $menuName?></span></a>
          <?php if ($menuKey == 'homepage'): ?>
            <span class="nav_line"></span>
          <?php endif?>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="zhen_qing">The true transfer</div>
  </div>
</div>
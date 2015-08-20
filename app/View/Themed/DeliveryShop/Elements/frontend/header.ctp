  <header class="main-header cf">
    <div class="top-bar">
      <div class="container">
        <div class="row">
          <ul class="nav">
            <li><a href="/multi-language/change/eng"><?php echo __("English"); ?></a></li>
            <li><a href="/multi-language/change/vie"><?php echo __("Vietnamese"); ?></a></li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /.top-bar -->

    <div class="header-bar header-mainnav">
      <div class="container">
        <div class="row">
          <div><a href="/" class="logo pull-left"><?php echo $this->Html->image('logo.png'); ?></a></div>
          <div class="clearfix"></div>
          <?= $this->element('frontend/search_box') ?>
        </div>
      </div>
    </div>
    <!-- /.header-bar -->
    <div class="menu-bar cf">
      <div class="container">
        <div class="row">
          <div class="mainnav">
            <a href="#" class="show-mbmenu"></a>
            <ul class="main-menu">
              <?php foreach($menuList as $menuKey => $menuName): ?>
                <li><a class="<?= ($currentPage == $menuKey) ? 'pointing' : '' ?>" href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliPage', 'action' => 'index', $menuKey)) ?>"><span><?= $menuName?></span></a></li>
                <?php if ($menuKey == 'homepage'): ?>
                  <li class="nav_line"><span class=""></span></li>
                <?php endif?>
              <?php endforeach; ?>
            </ul>
            <div class="phone-number pull-right"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- /.menu-bar -->
  </header>
  <!-- /.main-header -->
  <div class="header-height"></div>




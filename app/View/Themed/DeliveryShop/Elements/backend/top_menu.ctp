<header class="header" role="banner" style="top: 0px;">
  <div class="row-main">
    <div id="inner-header" class="wrap clearfix">
      <div class="site-title"><a href="/" rel="nofollow"></a></div>
      <nav role="navigation">
        <div class="nav-wrapper nav-wrapper-main collapse navbar-collapse" id="header-nav">
          <ul class="clearfix nav nav-main">
            <li><a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'DashBoard', 'action' => 'index')); ?>"><?php echo __("Dashboard"); ?></a></li>
            <li><a href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliBilling', 'action' => 'index')) ?>"><?php echo __("Billing Management"); ?></a></li>

            <li><a href="#"><?php echo __("Schedule Management"); ?></a>
              <ul class="sub-menu" style="width: 164px;">
                <li><a href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliSchedule', 'action' => 'index')) ?>"><?php echo __("Schedules"); ?></a></li>
                <li><a href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliLocation', 'action' => 'index')) ?>"><?php echo __("Locations"); ?></a></li>
                <li><a href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliDefaultLocationProcedure', 'action' => 'index')) ?>"><?php echo __("Default Procedures"); ?></a></li>
                <li><a href="<?= Router::url(array('plugin' => false, 'controller' => 'DeliRuntimeProcedure', 'action' => 'index')) ?>"><?php echo __("Runtime Procedure"); ?></a></li>
              </ul>
            </li>

            <li><a href="#"><?php echo __("Systems"); ?></a>
              <ul class="sub-menu" style="width: 164px;">
                <li>
                  <a href="#"><?php echo __("Translation"); ?></a>
                  <ul class="sub-menu" style="width: 138px;">
                    <li><a href="/multi-language/exportCms"><?php echo __("Export"); ?></a></li>
                    <li><a href="/multi-language/importCms"><?php echo __("Import"); ?></a></li>
                  </ul>
                </li>
                <li>
                  <a href="#"><?php echo __("Change Language"); ?></a>
                  <ul class="sub-menu" style="width: 138px;">
                    <li><a href="/multi-language/change/vie"><?php echo __("Vietnamese"); ?></a></li>
                    <li><a href="/multi-language/change/eng"><?php echo __("English"); ?></a></li>
                  </ul>
                </li>

                <li><a href="<?= Router::url(array('plugin' => 'System', 'controller' => 'CleanCache', 'action' => 'cleanCache')) ?>"><?php echo __("Clear Cache"); ?></a></li>
              </ul>
            </li>
            <li><a href="#"><?php echo __("Account"); ?></a>
              <ul class="sub-menu" style="width: 164px;">
                <li><a href="/user/account/profile"><?php echo __("Edit your profile"); ?></a></li>
                <li><a href="/user/account/logout"><?php echo __("Logout"); ?></a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
      <div class="navigation-toggle" data-toggle="collapse" data-target="#header-nav">
        <a href="#"></a>
      </div>
    </div>
  </div>
</header>

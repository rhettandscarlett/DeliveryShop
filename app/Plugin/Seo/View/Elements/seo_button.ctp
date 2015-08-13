<?php if(isset($seoPluginParams)): ?>

  <a href="<?= Router::url(array('plugin' => 'seo', 'controller' => 'seo', 'action' => 'edit', $seoPluginParams))?>" class="sfDialog btn btn-danger" sfDlg-footer="false" style="position:fixed; bottom:0; right:0; margin: 0px 10px 10px 0px; z-index: 10000000">SEO</a>

<?php endif; ?>

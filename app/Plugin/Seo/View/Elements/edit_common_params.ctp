<?php
  if(isset($seo_options['params'])) {
    foreach($seo_options['params'] as $key => $val) {
      echo '<input type="hidden" name="seo_plugin_params['.$key.']" value="'.$val.'">';
    }
    unset($seo_options['params']);
  }

  if(!isset($seo_options['prefix_slug'])) {
    echo '<input type="hidden" name="seo_plugin_slug[prefix]" value="/">';
  }

  foreach($seo_options as $key => $val) {
    echo '<input type="hidden" name="seo_plugin_main['.$key.']" value="'.$val.'">';
  }
?>

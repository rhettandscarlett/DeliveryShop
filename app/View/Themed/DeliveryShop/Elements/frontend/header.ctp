<div class="top_box">
  <a class="logo" href="index.php">ptivn.com</a>
  <div class="shou_C">
    <?php if($this->Session->read('Config.language') == 'vie'): ?>
      <a href="/multi-language/change/eng"><?php echo __("English"); ?></a>
    <?php else :?>
      <a href="/multi-language/change/vie"><?php echo __("Vietnamese"); ?></a>
    <?php endif;?>
</div>
<div class="clear"></div>
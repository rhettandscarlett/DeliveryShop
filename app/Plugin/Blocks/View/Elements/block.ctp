<?php
//$this->set(compact('block'));

$b = $block;
$class = 'block';
if (!empty($b['css_class'])) {
  $class .= ' ' . $b['css_class'];
}
?>
<div id="block-<?php echo $b['id']; ?>" class="<?php echo $class; ?>">
  <?php if ($b['show_title'] == 1): ?>
    <div class="block-title">
      <h3><?php echo $b['title']; ?></h3>
      <div class="box-tool">
        <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
        <a data-action="close" href="#"><i class="icon-remove"></i></a>
      </div>
    </div>
  <?php endif; ?>
  <div class="block-body">
    <?php echo $this->Layout->filter($b['body']); ?>
  </div>
</div>
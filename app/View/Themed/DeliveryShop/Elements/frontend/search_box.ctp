<div class="quicksearch">
  <?php echo $this->Layout->sessionFlash(); ?>
  <form method="get" action="<?= Router::url(array('plugin' => false, 'controller' => 'DeliFrontendBilling', 'action' => 'doTracking')) ?>" name="quicksearch">
    <label for="q" class="search-label"><?= __('Search bill code')?></label>
    <input type="text" id="q" class="" name="bill_code" placeholder="<?= __('Type code')?>">
    <input type="hidden" name="search">
    <button class="search btn btn-sm btn-info" id="search" type="submit" value="Go"><?= __('Go')?></button>
  </form>
</div>
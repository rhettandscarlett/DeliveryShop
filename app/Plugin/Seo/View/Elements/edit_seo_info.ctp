<div>

  <?= $this->element('Seo.edit_common_params', array('seo_options' => $seo_options)); ?>
  <?php
  if(isset($seo_options['prefix_slug'])){
    $slugPrefix = $seo_options['prefix_slug'];
  } else {
    $slugPrefix = '/';
  }
  if(isset($seo_data['slug'])) {
    $slug = preg_replace("/^".str_replace('/','\/',$slugPrefix)."/",'',$seo_data['slug']);
  } else {
    $slug = '';
  }
  $title = $metaDesc = $metaKeywords = '';
  if(isset($seo_data['meta_data'])) {
    $title = $seo_data['meta_data']->title;
    $metaDesc = $seo_data['meta_data']->meta_description;
    $metaKeywords = $seo_data['meta_data']->meta_keywords;
  }
  ?>

  <p>&nbsp;</p>

  <h4><?= __('SEO data') ?></h4>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label"><?= __('Slug') ?></label>
    <div class="col-sm-10">
      <div class="input-group">
        <span class="input-group-addon">
          <?= $slugPrefix ?>
        </span>
        <input class="form-control" type="text" name="seo_plugin_slug[url]" value="<?= $slug ?>">
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label"><?= __('Title') ?></label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="seo_plugin_data[title]" value="<?= $title ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label"><?= __('Meta Description') ?></label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="seo_plugin_data[meta_description]" value="<?= $metaDesc ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label"><?= __('Meta Keywords') ?></label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="seo_plugin_data[meta_keywords]" value="<?= $metaKeywords ?>">
    </div>
  </div>

  <p>&nbsp;</p>
</div>

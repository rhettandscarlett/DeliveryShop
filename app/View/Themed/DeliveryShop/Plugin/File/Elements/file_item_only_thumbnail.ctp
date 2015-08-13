<?php
  if(isset($file['file_type'])):
  $isImage = $this->File->isImage($file['file_type']);
  $fileUrl = $this->File->getThumbnailUrl($file['path']);
  $isShowThumbnail = $isImage && $this->File->isExistPhysicalFile($file['path']);
?>

<div class="file-container">
  <div class="image-container<?php echo $isShowThumbnail ? ' ' : ' image-container-no-image' ?>">
    <img src="<?php echo $fileUrl ?>" width="<?php echo isset($params['width']) ? $params['width'] : 'auto' ?>" height="<?php echo isset($params['height']) ? $params['height'] : $this->File->getMaxThumbnailHeight() ?>" alt="<?= isset($file['filename']) ? $file['filename'] : __("Image Not Available") ?>" />
  </div>
  <div style="clear: both; visibility: hidden;"></div>
</div>
<?php else:?>
<div id="sf-file-default-img-<?= $params['id'] ?>">
  <?php echo $this->Html->image("no-image.png", 
    array(
      'width'=>isset($params['width']) ? $params['width'] :"auto", 
      'height'=>isset($params['height']) ? $params['height'] :"auto", 
      )
    ); 
  ?>
</div>
<?php endif;?>
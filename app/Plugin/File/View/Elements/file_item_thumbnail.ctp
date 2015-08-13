<?php
$isImage = $this->File->isImage($file['file_type']);
$fileUrl = NULL;
if ($isImage) {
  $fileUrl = $this->File->getThumbnailUrl($file['path']);
}
if (empty($fileUrl)) {
  $fileUrl = $this->File->getFileUrl($file['path']);
}
$isShowThumbnail = $isImage && $this->File->isExistPhysicalFile($file['path']);
?>

<div class="file-container">
  <div class="image-container<?php echo $isShowThumbnail ? ' ' : ' image-container-no-image' ?>" id="choose-file-file-container-<?php echo $file['id'] ?>">
    <img src="<?php echo $fileUrl ?>" height="<?= $this->File->getMaxThumbnailHeight() ?>" alt="<?= isset($file['filename']) ? $file['filename'] : __("Image Not Available") ?>" />
    <?php if (!isset($readOnly) || !$readOnly): ?>
      <div class="btn-group btn-group-sm choose-files-btn-group">
        <button type="button" class="btn btn-default btn-sm choose-files-choose-one" data-fileid="<?= $file['id'] ?>" data-categorycode="<?php echo $params['categoryCode'] ?>" data-id="<?php echo $params['id']?>" data-path="<?php echo $file['path']?>" data-filename="<?php echo $file['filename']?>">
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </div>
    <?php endif; ?>
  </div>
  <div style="clear: both; visibility: hidden;"></div>
</div>
<?php
if(!$params['lang']){
echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file['id'] . '.id', array('type' => 'hidden',
  'value' => $file['id'],
  'class' => "form-control",
  'id' => 'file-form-element-file-id-' . $params['id'] . '-' . $file['id']));
}
?>
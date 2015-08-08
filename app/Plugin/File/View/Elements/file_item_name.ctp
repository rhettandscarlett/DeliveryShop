<?php if(!$params['lang']):?>
<div class="file-container"
     id="file-choose-file-file-container-<?= $file['id']?>"
     data-fileid="<?php echo $file['id']?>"
     data-filename="<?php echo $file['filename']?>"
     data-fileurl="<?= Router::fullBaseUrl().'/files/uploads/'.$file['path'] ?>"
  >
  <div id="choose-file-file-container-<?php echo $file['id']?>">
    <div style="clear: both">
      <div style="float: left"><img class="" src="<?php echo $this->File->getFileIconUrl($file['file_type']) ?>" /></div>
      <div style="float: left; margin-left: 1px;"><?php echo $file['filename']?></div>
    </div>
  </div>
</div>
<?php
echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file['id'] . '.id', array('type' => 'hidden',
  'value' => $file['id'],
  'class' => "form-control",
  'id' => 'file-form-element-file-id-' . $params['categoryCode'] . '-' . $file['id']));
?>
<?php else: ?>
<div style="clear: both">
  <div style="float: left"><img class="" src="<?php echo $this->File->getFileIconUrl($file['file_type']) ?>" /></div>
  <div style="float: left; margin-left: 1px;"><?php echo $file['filename']?></div>
</div>
<?php endif;?>



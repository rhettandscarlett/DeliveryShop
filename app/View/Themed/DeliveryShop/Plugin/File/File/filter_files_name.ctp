    
      <?php foreach ($files as $file_record):
  $file = $file_record['File'];
  $file_model = $file_record['FileModel'];
  ?>
  <tr id="file-choose-file-row-<?= $file['id'] ?>">
    <?php if(isset($params['isShowThumbnail']) && $params['isShowThumbnail']):?>
    <td>
      <img src="<?= Router::fullBaseUrl().'/files/uploads/'.$file['path'] ?>" height="<?= $this->File->getMaxThumbnailHeight() ?>"/>
    </td>
    <?php endif;?>
    <td><?php echo $this->element('File.file_item_name', 
      array('file' => $file, 'params'=>$params)); ?> 
    </td>
    <td class="file-choose-file-show-file-info"><?= $file['file_type'] ?></td>
    <td class="file-choose-file-show-file-info"><?= $this->File->formatBytes($file['size']);?></td>
    <td class="file-choose-file-show-file-info">
        <button type="button" class="choose-files-choose-one" data-fileid="<?= $file['id'] ?>" data-categorycode="<?= $params['categoryCode'] ?>" data-lang="<?php echo $params['lang']?>" data-path="<?php echo $file['path']?>" data-filename="<?php echo $file['filename']?>">
          <span class="glyphicon glyphicon-plus"></span>
        </button>
    </td>
  </tr>
<?php endforeach; ?>
    

<?php
foreach ($files as $file_record):
  $file = $file_record['File'];
  //$is_image = $this->File->isImage($file['file_type']);
  ?>
  <tr id="file-choose-file-row-<?= $file['id'] ?>">
    <td><?php echo $this->element('File.file_with_thumbnail', array('file' => $file,)); ?> </td>
    <td class="file-choose-file-show-file-info"><img class="" src="<?php echo $this->File->getFileIconUrl($file['file_type']) ?>" /><?= $file['filename'] ?></td>
    <td class="file-choose-file-show-file-info"><?= $file['file_type'] ?></td>
    <td class="file-choose-file-show-file-info"><?= $this->File->formatBytes($file['size']); ?></td>
    <td class="file-choose-file-show-file-info">
      <button type="button" class="file-choose-files-add-one" data-fileid="<?php echo $file['id']; ?>" data-path="<?php echo $file['path']; ?>" data-filename="<?php echo $file['filename']; ?>">
        <span class="glyphicon glyphicon-plus"></span>
      </button>
    </td>
  </tr>
<?php endforeach; ?>
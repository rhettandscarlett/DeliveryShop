<link rel="stylesheet" type="text/css" href="<? echo  Router::url("/") ?>File/css/file.css" />
  <div class="file-form-selected-file-container-with-filename form-selected-file-container-files" id="form-selected-file-container-<?= $model_id ?>">
  <?php foreach($selectedFiles as $file_model) {
    echo $this->element('File.file_item_name', array(
        'file' => $file_model['File'],
        'params' => $params
      ));
   } ?>
</div>

<?php
/**
 * 1. get file from list_files with some params 
 * 2. show selected thumbnail
 * 3. show a modal to select file
 */
$selectedItemTemplate = FileLib::getFormTemplate($params['selectedItemViewType']);
//create dialog
echo $this->Html->link(__('Choose Files'), array(
  'controller' => 'file',
  'action' => 'list_files',
  '?' => $params,
  'plugin' => ''
  ), array(
  'escape' => false,
  'class' => 'sfDialog',
  'sfDlg-loading' => "true",
  'sfDlg-title' => "false",
  'sfDlg-footer' => "false",
  'sfDlg-width' => "80%",
  'id' => 'choose-file-link-choose-files-' . $params['id'],
  'style' => 'display: none;',
  )
);
?>
<div class="hide" id="sf-file-default-img-<?= $params['id'] ?>">
  <?php echo $this->HTML->image("no-".$params['type'].".png"); ?>
</div>

<div class="fileupload fileupload-new sf-file-select-one-thumbnail">
  <div class="fileupload-new img-thumbnail" id="sf-file-img-thumbnail-<?= $params['id'] ?>" style="width: auto; height: auto; max-height: 200px; max-width: 200px;">
<?php
if (count($selectedFiles) == 0) {
  echo $this->HTML->image("no-".$params['type'].".png");
} else {
  foreach ($selectedFiles as $file_record) {
    $file = $file_record['File'];
    echo $this->element('File.'.$selectedItemTemplate, array(
      'file' => $file,
      'params' => $params,
      'readOnly' => TRUE,
    ));
  }
}
?>
  </div>
  <div>
    <button class="fileupload-new btn btn-inverse btn-file  form-file-element-button-choose-files<?php echo count($selectedFiles) > 0 ? " hide" : ""; ?>"
            data-categorycode="<?php echo $params['categoryCode'] ?>" data-id="<?php echo $params['id']?>"
            id="sf-file-button-choose-files-<?php echo $params['id'] ?>"><span class="glyphicon glyphicon-plus"></span>
    </button>
    
    <?php if($params['canUpload']):?>
    <div style="display: none;">
      <?php
      $js_callback_function_after_add_one = (empty($params['jsCallback']) ? 'jsCallbackFunctionAfterAddOneDefault' : $params['jsCallback']);
      echo $this->File->getAjaxUploadForm($params['id'], 'true', 'addParameters', 'afterCompleteOne', 'afterCompleteAll', $js_callback_function_after_add_one, $params['extensions']);
      ?>
    </div>
    <button type="button"
            class="btn btn-default btn-sm form-file-element-button-upload-files <?php echo count($selectedFiles) > 0 ? " hide" : ""; ?>"
            id="form-file-element-button-upload-files-<?php echo $params['id'] ?>"
            data-categorycode="<?php echo $params['categoryCode'] ?>" data-id="<?php echo $params['id']?>">
      <span class="glyphicon glyphicon-upload"></span>
    </button>
  <?php endif;?>
    
            
    <button class="sf-file-btn-remove btn btn-inverse btn-remove<?php echo count($selectedFiles) == 0 ? " hide" : ""; ?>"
            data-categorycode="<?= $params['categoryCode'] ?>" data-id="<?php echo $params['id']?>" id="sf-btn-remove-element-<?= $params['id'] ?>"><?php echo __("Entfernen"); ?>
    </button>
  </div>
</div>
<div class="form-selected-file-container form-selected-file-container-files hide" id="form-selected-file-container-<?php echo $params['id'] ?>"></div>

<div style="clear: both"></div>
<script>
  if(globalJsonParams==null){
    var globalJsonParams={'<?php echo $params['categoryCode']?>' : <?php echo json_encode($params)?>};
  }else{
    globalJsonParams['<?php echo $params['categoryCode']?>'] = <?php echo json_encode($params)?>;
  }
</script>
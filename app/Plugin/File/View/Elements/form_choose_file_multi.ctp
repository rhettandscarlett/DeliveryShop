<?php
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



//them cho phan multi lang
  echo $this->Html->link( __('Choose One File Lang'),
    array(
      'controller' => 'file',
      'action' => 'list_files',
      '?' => $params,
      'plugin' => ''
    ),
    array(
      'escape' => false,
      'class' => 'sfDialog',
      'sfDlg-loading' => "true",
      'sfDlg-title' => "false",
      'sfDlg-footer' => "false",
      'sfDlg-width' => "80%",
      'id' => 'choose-file-link-choose-files-langs-' . $params['id'],
      'style' => 'display: none;',
    )
  );
?>
<!--main button choose file -->
<button type="button" class="btn btn-default btn-sm form-file-element-button-choose-files"
        style="<?= count($selectedFiles) >= $params['limitFileNumber'] ?'display: none;' : '' ?>"
        data-categorycode="<?php echo $params['categoryCode'] ?>"
        data-id="<?php echo $params['id']?>"
        id="choose-file-button-choose-files-<?php echo $params['id']?>">
  <span class="glyphicon glyphicon-plus"></span>
</button>

<div style="display: none;">
    <?php
    $js_callback_function_after_add_one = (empty($params['jsCallback']) ? 'jsCallbackFunctionAfterAddOneDefault' : $params['jsCallback']);
    echo $this->File->getAjaxUploadForm($params['id'], 'true', 'addParameters', 'afterCompleteOne', 'afterCompleteAll', $js_callback_function_after_add_one, $params['extensions']);
    ?>
  </div>
  <?php if($params['canUpload']):?>
  <button type="button"
          style="<?= count($selectedFiles) >= $params['limitFileNumber'] ?'display: none;' : '' ?>"
          class="btn btn-default btn-sm form-file-element-button-upload-files"
          id="form-file-element-button-upload-files-<?php echo $params['id'] ?>"
          data-categorycode="<?php echo $params['categoryCode'] ?>" data-id="<?php echo $params['id']?>">
    <span class="glyphicon glyphicon-upload"></span>
  </button>
  <?php endif;?>

<div class="block block-black">
  <div class="block-title">
    <h3><i class="fa fa-bars"></i> <?php echo __("Files")?></h3>
    <div class="block-tool hide">
      <button class="btn btn-circle btn-bordered btn-inverse btn-collapse" data-action="collapse"><i class="fa fa-chevron-up"></i></button>
    </div>
  </div>

  <div class="block-body">
    <div class="file-form-selected-file-container-with-filename">

      <table class="table table-hover table-striped">
        <thead>
        <tr>
          <th>Filename</th>

          <?php if ($params['inputName']): ?>
            <th>Name</th>
          <?php endif; ?>

          <?php if ($params['inputDesc']): ?>
            <th>Description</th>
          <?php endif; ?>

          <?php if ($params['inputOrder']): ?>
            <th>Order</th>
          <?php endif; ?>

          <th>Actions</th>
        </tr>
        </thead>
        
        <tbody id="form-selected-file-container-<?= $params['id'] ?>">
          <tr class="file-choose-file-row-empty-row" style="<?= empty($selectedFiles) ? '' : 'display: none;' ?>">
            <?php
              $nb_col_span = 2;
              if ($params['inputName']) $nb_col_span++;
              if ($params['inputDesc']) $nb_col_span++;
              if ($params['inputOrder']) $nb_col_span++;
            ?>
            <td colspan="<?= $nb_col_span ?>"> <?= __("Empty files")?></td>
          </tr>
        <?php
          //show all file of model
          foreach ($selectedFiles as $file_record){
            $file = $file_record['File'];
            $file_model = $file_record['FileModel'];
            echo $this->element('File.file_item_multi', array(
                'file' => $file,
                'file_model' => $file_model,
                'params' => $params,
                'selectedFileLangs' => isset($selectedFileLangs[$file_model['id']]) ? $selectedFileLangs[$file_model['id']] : array(),
              ));
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div style="clear: both"></div>
<div class="modal fade" id="fileUploadLoadingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <i class="fa fa-spinner fa-spin file-upload-loading" ></i>
  </div>
</div>
<script>
  if(globalJsonParams==null){
    var globalJsonParams={'<?php echo $params['categoryCode']?>' : <?php echo json_encode($params)?>};
  }else{
    globalJsonParams['<?php echo $params['categoryCode']?>'] = <?php echo json_encode($params)?>;
  }
</script>
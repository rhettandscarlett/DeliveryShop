<tr id="file-choose-selected-file-<?= $params['id'] ?>-<?= $file['id'] ?>" class="file-choose-selected-file-category-<?= $params['categoryCode'] ?>">
  <td>
    <?php
    if($params['selectedItemViewType']=='multi_name'){
      echo $this->element('File.file_item_name', array('file' => $file, 'params' => $params));
    }else{
      echo $this->element('File.file_item_thumbnail', array('file' => $file, 'params' => $params));
    }
    
    ?>
  </td>

  <?php if ($params['inputName']): ?>
    <td class="file-edit-file-model-info">
      <?php
      echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file['id'] . '.name', array(
        'value' => @$file_model['name'],
        'required' => FALSE,
        'label' => FALSE,
        'class' => "form-control",
        'div' => 'form-group',
        'wrapInput' => 'col col-md-9',
        'id' => 'file-form-element-file-name-' . $params['categoryCode'] . '-' . $file['id']));
      ?>
    </td>
  <?php endif; ?>

  <?php if ($params['inputDesc']): ?>
    <td class="file-edit-file-model-info">
      <?php
      echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file['id'] . '.description', array(
        'value' => @$file_model['description'],
        'label' => FALSE,
        'class' => "form-control",
        'div' => 'form-group',
        'wrapInput' => 'col col-md-9',
        'id' => 'file-form-element-file-description-' . $params['categoryCode'] . '-' . $file['id']));
      ?>
    </td>
  <?php endif; ?>

    <?php if ($params['inputOrder']): ?>
    <td class="file-edit-file-model-info">
      <?php
      echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file['id'] . '.order', array(
        'type' => 'number',
        'value' => isset($file_model['order']) ? $file_model['order'] : 0,
        'label' => FALSE,
        'div' => 'form-group',
        'wrapInput' => 'col col-md-9',
        'class' => 'file-form-element-file-model-order form-control',
        'id' => 'file-form-element-file-order-' . $params['categoryCode'] . '-' . $file['id']));
      ?>
    </td>
<?php endif; ?>

  <td class="file-edit-file-model-info">
    <button type="button" data-categorycode="<?= $params['categoryCode'] ?>"
            data-id="<?php echo $params['id']?>"
            data-fallback_fileid="<?= $file['id'] ?>"
            class="btn btn-danger btn-xs choose-files-remove-one"><?= __("Delete") ?>
    </button>
  </td>
</tr>
<?php if ($params['isMultiLang']):?>
  <tr id="file-choose-selected-file-langs-<?= $params['id'] ?>-<?= $file['id'] ?>">
    <?php
    $nb_col_span_lang = 2;
    if ($params['inputName'])
      $nb_col_span_lang++;
    if ($params['inputDesc'])
      $nb_col_span_lang++;
    if ($params['inputOrder'])
      $nb_col_span_lang++;
    ?>
    <td colspan="<?= $nb_col_span_lang ?>">
      <?php
      echo $this->element('File.file_multi_lang', array(
        'params' => $params,
        'file_id' => $file['id'],
        'file_url' => Router::fullBaseUrl().'/files/uploads/'.$file['path'],
        'selectedFileLangs' => isset($selectedFileLangs) ? $selectedFileLangs : null,
      ));
      ?>
    </td>
  </tr>
<?php endif; ?>
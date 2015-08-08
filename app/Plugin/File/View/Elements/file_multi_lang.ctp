<?php
//multilang area of file
$languages = Configure::read('MultiLanguage.list');
?>
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>Language</th>
      <th>Filename</th>
      <?php if ($params['inputName']): ?>
        <th>Name</th>
      <?php endif; ?>
      <?php if ($params['inputDesc']): ?>
        <th>Description</th>
      <?php endif; ?>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($languages as $lang_key => $lang_title) {
      $file = (isset($selectedFileLangs[$lang_key]) ? $selectedFileLangs[$lang_key]['File'] : NULL);
      $multi_lang_file = (isset($selectedFileLangs[$lang_key]) ? $selectedFileLangs[$lang_key]['MultilanguageFileModel'] : NULL);
      ?>
      <tr class="multilanguage-list-code-index-all multilanguage-list-code-<?= $lang_key ?>">
        <td>
          <span class="language-group-flag-title">
            <i class="flags flag-<?= $lang_key ?>"></i>
            <p><?= $lang_title ?></p>
          </span>
          <?php
          echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file_id . '.langs.' . $lang_key . '.id', array('type' => 'hidden',
            'value' => @$multi_lang_file['id'],
            'class' => "form-control",
            'id' => 'file-form-element-multi-lang-id-' . $params['categoryCode'] . '-' . $file_id . '-' . $lang_key));

          echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file_id . '.langs.' . $lang_key . '.file_id', array('type' => 'hidden',
            'value' => @$file['id'],
            'class' => "form-control",
            'id' => 'file-form-element-file-id-' . $params['categoryCode'] . '-' . $file_id . '-' . $lang_key));
          ?>
        </td>

        <td>
          <div id="file-form-element-lang-<?= $params['id'] ?>-<?= $lang_key ?>-<?= $file_id ?>" data-fileurl="<?= $file_url ?>">
        <?php if (isset($file)): ?>
              <?php if (in_array($params['selectedItemViewType'], array('name', 'multi_name'))): ?>
                <img src="<?php echo $this->File->getFileIconUrl(@$file['file_type']) ?>" />
                <?php echo $file['filename'] ?>
              <?php else: ?>
                <?php
                echo $this->element('File.file_item_thumbnail', array(
                  'file' => $file,
                  'params'=>$params
                ))
                ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>

          <?php
          
          $params['lang'] = $lang_key;
          echo $this->Html->link(
            __('Choose File'), 
            array(
              'controller' => 'file',
              'action' => 'list_files',
              '?' => $params,
              'plugin' => ''
            ), 
            array(
              'escape' => false,
              'class' => 'file-form-element-choose-file-one-lang',
              'data-categorycode' => $params['categoryCode'],
              'data-id' => $params['id'],
              'data-for_file_id' => $file_id,
              'data-lang' => $lang_key,
              'style' => 'margin-left: 10px;margin-right: 10px;',
            )
          );
          if ($params['canUpload']) {
            echo $this->Html->link(__('Upload File'), '', array(
              'class' => 'file-file-element-upload-new-language-file',
              'data-categorycode' => $params['categoryCode'],
              'data-id' => $params['id'],
              'data-for_file_id' => $file_id,
              'data-lang' => $lang_key,
            ));
          }
          ?>
        </td>

        <?php if ($params['inputName']): ?>
        <td class="file-edit-file-model-info">
          <?php
          echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file_id . '.langs.' . $lang_key . '.name', array(
            'value' => @$multi_lang_file['name'],
            'required' => FALSE,
            'label' => FALSE,
            'class' => "form-control",
            'div' => 'form-group',
            'wrapInput' => 'col col-md-9',
            'id' => 'file-form-element-file-name-' . $params['categoryCode'] . '-' . $file_id . '-' . $lang_key));
          ?>
        </td>
        <?php endif; ?>

        <?php if ($params['inputDesc']): ?>
        <td class="file-edit-file-model-info">
          <?php
          echo $this->Form->input('File.selected_files.' . $params['categoryCode'] . '.' . $file_id . '.langs.' . $lang_key . '.description', array(
            'value' => @$multi_lang_file['description'],
            'label' => FALSE,
            'class' => "form-control",
            'div' => 'form-group',
            'wrapInput' => 'col col-md-9',
            'id' => 'file-form-element-file-description-' . $params['categoryCode'] . '-' . $file_id . '-' . $lang_key));
          ?>
        </td>
        <?php endif; ?>

        <td class="file-edit-file-model-info">
          <button type="button"
                  data-categorycode="<?= $params['categoryCode'] ?>"
                  data-id="<?php echo $params['id']?>"
                  data-fileid="<?= $file_id ?>"
                  data-lang="<?= $lang_key ?>"
                  class="btn btn-danger btn-xs choose-files-selected-lang-remove-one"><?= __("Entfernen") ?></button>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<h3>
  Edit languages:
  <?= $objectType->name ?>

</h3>

<hr />

<div class="posts form">
  <?php
  echo $this->Form->create('MultiLanguageModel', array(
      'novalidate' => true,
      'inputDefaults' => array(
          'div' => 'form-group',
          'wrapInput' => false,
          'class' => 'form-control'
      ),
      'class' => 'well',
      'type' => 'file'
  ));

  if (isset($objectConf['multilanguage']['columns'])) {
    foreach ($objectConf['multilanguage']['columns'] as $name):
      ?>
      <div class="form-group">
        <?php
        echo $this->Form->input($name, array('default' => $objectLink->$name, 'disabled' => true));
        ?>
      </div>

      <?php foreach (Configure::read('MultiLanguage.list') as $key1 => $value1): ?>
        <div class="form-group">
          <?php
          echo $this->Form->input($name, array('name' => 'txtdata[' . $key1 . '][' . $name . ']', 'default' => @$dataMulti[$name][$key1], 'label' => $value1));
          ?>
        </div>
        <?php
      endforeach;
    endforeach;
  }

  if (count($dataFiles) > 0 && isset($objectConf['multilanguage']['files'])) {
    ?>
    <hr />
    <h4>
      Files
    </h4>
    <hr />

    <?php
    $initJs = array();
    foreach ($dataFiles as $file):
      if (!in_array($file->category_code, $objectConf['multilanguage']['files'])) {
        continue;
      }
      ?>
      <div class="form-group">
        <?php
        echo $this->Html->link($file->filename, '/files/uploads/' . $file->path);
        if (!empty($file->name)) {
          echo $this->Form->input("Name", array('default' => $file->name, 'disabled' => true));
        }
        if (!empty($file->desc)) {
          echo $this->Form->input('Description', array('default' => $file->desc, 'disabled' => true));
        }
        echo "<hr />";
        foreach (Configure::read('MultiLanguage.list') as $key1 => $value1):
          ?>
          <div class="form-group">
            <div class="form-group" style="margin-left: 10px">
              <strong><i><?= $value1 ?></i></strong>
              <?php
              $initJs[$file->file_id . '-' . $key1] = 'Files-' . $file->file_id . '-' . $key1 . '-file';
              if (@intval($dataMultiFiles[$file->file_id]['file_id_link'][$key1]) > 0) {
                ?>
                <div class="form-group">
                  <?php echo $this->Html->link($dataMultiFiles[$file->file_id]['filename'][$key1], '/files/uploads/' . $dataMultiFiles[$file->file_id]['path'][$key1]); ?>
                  - <a href="#" onclick="$('#MultiLanguageModel<?= $initJs[$file->file_id . '-' . $key1] ?>-hidden').val('');
                              $(this).parent().remove();
                              return false;">Delete</a>
                </div>
                <?php
              }
              echo $this->Form->input($initJs[$file->file_id . '-' . $key1], array('name' => 'files[' . $file->file_id . '][' . $key1 . '][file]', 'default' => '', 'label' => 'File'));
              echo $this->Form->hidden($initJs[$file->file_id . '-' . $key1] . '-hidden', array('name' => 'files[' . $file->file_id . '][' . $key1 . '][file-hidden]', 'default' => @$dataMultiFiles[$file->file_id]['file_id_link'][$key1]));
              if (!empty($file->name)) {
                echo $this->Form->input('', array('name' => 'files[' . $file->file_id . '][' . $key1 . '][name]', 'default' => @$dataMultiFiles[$file->file_id]['name'][$key1], 'label' => 'Name'));
              }
              if (!empty($file->desc)) {
                echo $this->Form->input('', array('name' => 'files[' . $file->file_id . '][' . $key1 . '][desc]', 'default' => @$dataMultiFiles[$file->file_id]['desc'][$key1], 'label' => 'Description'));
              }
              ?>
            </div>
          </div>
          <hr />
          <?php
        endforeach;
        ?>
      </div>


      <?php
    endforeach;
    ?>

    <?php
  }


  echo $this->Form->submit('Submit', array('class' => 'btn btn-large btn-primary '));
  echo $this->Form->end();
  ?>
</div>

<?php
echo $this->HTML->css('FileManager.jquery-ui-1.10.3.custom.css');
echo $this->HTML->script('FileManager.jquery-ui-1.10.3.custom.js');
?>

<script>
  $(function() {
<?php foreach ($initJs as $val): ?>
      $("#MultiLanguageModel<?= $val ?>").autocomplete({
        source: "/file_manager/files/filter_by_filename?category_code=<? echo 'img_text'; ?>&object_id=<? echo '297'; ?>",
        autoFocus: true,
        minLength: 1,
        select: function(event, ui) {
          $("#MultiLanguageModel<?= $val ?>-hidden").val(ui.item.value);
          $("#MultiLanguageModel<?= $val ?>").val(ui.item.label);
          return false;
        },
        focus: function(event, ui) {
          return false;
        }
      });

<?php endforeach; ?>
  });
</script>
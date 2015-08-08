<?php
echo $this->Form->create('MultiLanguageModel', array(
  'novalidate' => true,
  'inputDefaults' => array(
    'div' => 'form-group',
    'label' => array(
      'class' => 'col col-md-3 control-label text-left'
    ),
    'wrapInput' => 'col col-md-9',
    'class' => 'form-control'
  )
));
?>
<div class="page-title" id="page-title">
  <div class="row">
    <div class="col-md-6">
      <h1><i class="fa fa-bars"></i>
        CMS Translation
      </h1>
      <h4></h4>
    </div>
    <div class="col-md-6 text-right">
      <?php echo $this->Html->link(__('Export'), Router::url(array('plugin' => 'MultiLanguage', 'controller' => 'MultiLanguage', 'action' => 'exportCMS')), array('class' => 'btn btn-inverse', 'escape' => false)) ?>
      <?php echo $this->Html->link(__('Import'), Router::url(array('plugin' => 'MultiLanguage', 'controller' => 'MultiLanguage', 'action' => 'importCms')), array('class' => 'btn btn-inverse', 'escape' => false)) ?>
      <?php
      echo $this->Form->button(__('Save'), array('class' => 'btn btn-inverse', 'type' => 'submit'));
      ?>
    </div>
  </div>
</div>

<div class="well form-horizontal page-body posts form">
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th><?= __('Key') ?></th>
        <th><?= __('Value') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($languages as $key => $langs):
        $keyEncode = base64_encode($key)
        ?>
        <tr>
          <td style="width: 46%"><?= $key ?> </td>
          <td>
            <?php foreach ($langs as $lang => $text): ?>
              <div style="float: left; width: 50px; text-align: right; margin-right: 2px">
                <?= $lang ?>
              </div>
              <div style="float: left; width: 80%">
                <?php
                $text = isset($this->request->data['languagesEdited'][$lang][$keyEncode]) ? $this->request->data['languagesEdited'][$lang][$keyEncode] : $text;
                ?>
                <input class="form-control" type="text" name="languagesEdited[<?= $lang ?>][<?= $keyEncode ?>]" value="<?= htmlentities($text, ENT_QUOTES | ENT_IGNORE, "UTF-8") ?>" />
              </div>
              <div style="clear: both"></div>
            <?php endforeach; ?>
          </td>
        </tr>
      <?php endforeach; ?>

    </tbody>

  </table>
</div>

<?php
echo $this->Form->end();
?>

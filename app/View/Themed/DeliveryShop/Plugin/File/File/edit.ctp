<h3>
  <? if (isset($this->data['File']['id']) && $this->data['File']['id'] > 0): ?>
    <?= __('Edit') ?>: #<?= $this->data['File']['id'] ?>
  <? else: ?>
    <?= __('Add') ?>
  <? endif; ?>
</h3>

<hr />

<div class="posts form">
  <?php
  echo $this->Form->create('File', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => false,
      'class' => 'form-control'
    ),
    'class' => 'well',
    'type' => 'file'
  ));
  ?>
  <?= $this->Form->input('filename', array('label' => 'Filename', 'id' => 'file-form-file-filename')) ?>
  <?= $this->Form->input('name', array('label' => 'Name', 'id' => 'file-form-file-name')) ?>
  <?= $this->Form->input('description', array('label' => 'Description', 'id' => 'file-form-file-description')) ?>

  <?php
  echo $this->Form->input('id', array('type' => 'hidden', 'id' => 'file-form-file-id'));
  echo $this->Form->submit(__('Submit'), array(
    'class' => 'btn btn-large btn-primary',
    'id' => 'btn-submit-update-file-info',
  ));
  echo $this->Form->end();
  ?>
</div>

<script>
  $(document).ready(function() {
    $("#btn-submit-update-file-info").click(function() {
      var fileid = $("#file-form-file-id").val();
      var fileName = $("#file-form-file-filename").val();
      var name = $("#file-form-file-name").val();
      var description = $("#file-form-file-description").val();

      var request = $.ajax({
        url: "<?php echo Router::url("/file/update", TRUE) ?>",
        type: "POST",
        data: {File: {id: fileid, filename: fileName, name: name, description: description}},
        dataType: "json",
        async: false
      });
      request.done(function(msg) {
        $("#file-listing-filename-" + fileid).html(fileName);
        $("#file-listing-name-" + fileid).html(name);
        $("#file-listing-description-" + fileid).html(description);
        $('#sfDialogModel').modal('hide');
        $('#file-lising-file-' + fileid).fadeOut('slow');
        $('#file-lising-file-' + fileid).fadeIn('slow');
      });
      request.fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
      });

      return false;
    })
  });
</script>
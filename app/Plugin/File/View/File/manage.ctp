<h1>File Management</h1>
<script>
  function afterCompleteOne(fileObject, completeOneDataParameters, jsCallbackFunctionAfterAddOne){
    //console.log(fileObject.fileURL);
  }
  function addParameters() {
    return {};
  }

  function afterCompleteAll() {
    location.reload();
  }
</script>
<div class="well">
  <?php echo $this->File->getAjaxUploadForm('general', 'true', 'addParameters', 'afterCompleteOne', 'afterCompleteAll', '', ''); ?>
</div>

<div class="well">
  <?php
  echo $this->Form->create('File', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'label' => array(
        'class' => 'col col-md-3 control-label text-left'
      ),
      'wrapInput' => 'col col-md-9',
      'class' => 'form-control'
    ),
    'type' => 'get'
  ));
  ?>
  <?php
  echo $this->Form->input('filter_string', array('label' => FALSE, 'value' => $filter_string, 'class' => 'form-control', 'div' => 'form-group',));
  echo $this->Form->button(__('Search'), array('class' => 'btn btn-primary', 'type' => 'submit', 'escape' => false));
  echo $this->Form->input('order_by', array('type' => 'hidden', 'value' => $order_by));
  echo $this->Form->input('order_type', array('type' => 'hidden', 'value' => $order_type));
  echo $this->Form->end();

  $sort_icon = ($order_type == 'desc') ? 'glyphicon glyphicon-arrow-up' : 'glyphicon glyphicon-arrow-down';
  ?>
</div>

<h4><?= __('Files') ?></h4>
<table class="table" id="file-manager-listing">
  <tr>
    <th class="hidden">
      <?php echo $this->Html->link(__('Id'), '/file/manage?order_by=id', array('class' => 'file-sort-value', 'data-field' => 'id')); ?>
      <?php if ($order_by == 'id') : ?> <span class="<?= $sort_icon ?>"></span> <?php endif; ?>
    </th>
    <th>
      <?php echo $this->Html->link( __('Filename'), '/file/manage?order_by=filename',
        array(
          'class' => 'file-sort-value',
          'data-field' => 'filename',
        ));
      ?>
      <?php if ($order_by == 'filename') :?> <span class="<?= $sort_icon ?>"></span> <?php endif; ?>
    </th>
    <th>
      <?php echo $this->Html->link(__('Type'), '/file/manage?order_by=file_type', array('class' => 'file-sort-value', 'data-field' => 'file_type')); ?>
      <?php if ($order_by == 'file_type') : ?> <span class="<?= $sort_icon ?>"></span> <?php endif; ?>
    </th>
    <th>
      <?php echo $this->Html->link(__('Size'), '/file/manage?order_by=size', array('class' => 'file-sort-value', 'data-field' => 'size')); ?>
      <?php if ($order_by == 'size') : ?> <span class="<?= $sort_icon ?>"></span> <?php endif; ?>
    </th>
    <th>
      <?php echo $this->Html->link(__('Name'), '/file/manage?order_by=name', array('class' => 'file-sort-value', 'data-field' => 'name')); ?>
      <?php if ($order_by == 'name') : ?> <span class="<?= $sort_icon ?>"></span> <?php endif; ?>
    </th>
    <th><?= __('Description') ?></th>
    <th>
      <?php echo $this->Html->link(__('Updated at'), '/file/manage?order_by=updated_time', array('class' => 'file-sort-value', 'data-field' => 'updated_time')); ?>
      <?php if ($order_by == 'updated_time') : ?> <span class="<?= $sort_icon ?>"></span> <?php endif; ?>
    </th>
    <th></th>
    <th></th>
  </tr>
  <?php if (empty($files)): ?>
    <tr><td colspan="8"> <?= __("Empty files") ?></td></tr>
  <?php endif; ?>

  <?php
  foreach ($files as $file_record):
    $file = $file_record['File'];
    ?>
    <tr id="file-lising-file-<?php echo $file['id']; ?>">
      <td class="hidden"><?php echo $file['id']; ?></td>
      <td>
        <img src="<?php echo Router::url("/") . 'File/img/fileicons/' . strtolower($file['file_type']) . '.png' ?>" />
        <a href="<?php echo $this->File->getFileUrl($file['path']) ?>" target="_blank">
          <span id="file-listing-filename-<?php echo $file['id']; ?>"><?php echo $file['filename'] ?></span>
        </a>
      </td>
      <td> <?php echo strtoupper($file['file_type']) ?></td>
      <td> <?php echo $this->File->formatBytes($file['size']); ?></td>
      <td id="file-listing-name-<?php echo $file['id']; ?>">
        <?php echo $this->File->truncateString($file['name'], Configure::read('AMU.limit_show_name')) ?></td>
      <td id="file-listing-description-<?php echo $file['id']; ?>">
        <?php echo $this->File->truncateString($file['description'], Configure::read('AMU.limit_show_desc')) ?></td>
      <td><?php echo $file['updated_time']; ?></td>
      <td>
        <?
        echo $this->Html->link(
          '<span class="glyphicon glyphicon-edit"></span>', '/file/edit/' . $file['id'], array(
          'escape' => false,
          'class' => 'sfDialog',
          'sfDlg-loading' => "false",
          'sfDlg-title' => "false",
          'sfDlg-footer' => "false"
          )
        );
        ?>
      </td>
      <td>
        <?
        echo $this->Html->link(
          $this->Html->image('/File/img/delete.png', array('alt' => 'Delete')), '/file/destroy/' . $file['id'], array('escape' => false), "Are you sure you wish to delete this file?"
        );
        ?>
      </td>
    </tr>
  <?php endforeach; ?>

</table>

<?
if (!empty($files)) {
  echo $this->Paginator->pagination(array('ul' => 'pagination'));
}
?>

<script>
  $(document).ready(function() {
    $(".file-sort-value").click(function() {
      var url = $(this).attr("href");

      var previousOrderBy = $("#FileOrderBy").val();
      var orderBy = $(this).data('field');

      var orderType = 'asc';
      if (previousOrderBy == orderBy) {
        orderType = $("#FileOrderType").val() == "desc" ? "asc" : "desc";
      }

      url += "&order_type=" + orderType;
      url += "&filter_string=" + $("#FileFilterString").val();

      $(this).attr("href", url);
    });
    $("#FileFilterString").focus();
  });
</script>
<?php
$this->Html->script('scroll', array('inline' => false));
$CKfuncNum = isset($opener['CKEditor']['funcNum']) ? $opener['CKEditor']['funcNum'] : 0;
if (!$CKfuncNum)
  $CKfuncNum = 0;
?>
<div class="row" style="margin: 5px;">
  <div class="col col-lg-12">
    <div class="block-title">
      <h3><i class="fa fa-bars"></i> <?php echo __('Choose File') ?></h3>
      <div class="block-tool">
      </div>
    </div>
    <div class="block-body">
      <div>
        <?php
        echo $this->Form->input('filter_name', array(
          'label' => false,
          'value' => $params['filterName'],
          'class' => 'form-control',
          'div' => 'form-group',
          'between' => '<i class="fa fa-spin filter-textbox" id="file-choose-file-filter-name"></i>',
          'placeholder' => __('Filter by name')
        ));
        ?>
      </div>

      <div class="table-responsive choose-file-container choose-file-container-filename-view" style="width:100%;height:500px;">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th><?php echo __('Thumbnail'); ?></th>
              <th><?php echo __('Filename'); ?></th>
              <th><?php echo __('Type'); ?></th>
              <th><?php echo __('Size'); ?></th>
              <th><?php echo __('Action'); ?></th>
            </tr>
          </thead>
          <tbody id="choose-file-container-<?php echo $params['id']; ?>">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    var totalPage = <?php echo intval(@$this->params['paging']['File']['pageCount']); ?>;
    var dataGet = <?php echo json_encode($params) ?>;
    $("#bar-search").sticky();
    currentPage = 1;
    dataGet.filterName = $('#filter_name').val();
    $.ajax({
      url: "<?php echo Router::url("/file/filter_files", TRUE); ?>/page:" + currentPage,
      type: "POST",
      data: dataGet,
      dataType: "json",
      success: function (data) {
        $("#choose-file-container-<?php echo $params['id']; ?>").html(data.html);
      },
    });
    $('.choose-file-container').sf_scroll({
      dataType: "json",
      getUrl: function () {
        return "<?php echo Router::url("/file/filter_files", TRUE); ?>/page:" + currentPage;
      },
      getData: function () {
        dataGet.filterName = $('#filter_name').val();
        return dataGet;
      },
      start: function () {
        currentPage++;
      },
      onload: function (data) {
        $("#choose-file-container-<?php echo $params['id']; ?>").append(data.html);
      },
      continueWhile: function (resp) {
        if (currentPage > totalPage) {
          return false;
        }
        return true;
      }
    });
    //filter file by file name
    var is_keyup = true;
    $('#filter_name').keyup(function (event) {
      if (is_keyup) {
        currentPage = 1;
        dataGet.filterName = $(this).val();
        $.ajax({
          url: "<?php echo Router::url("/file/filter_files", TRUE); ?>/page:" + currentPage,
          type: "POST",
          data: dataGet,
          dataType: "json",
          beforeSend: function (xhr) {
            is_keyup = false;
          },
          success: function (data) {
            $("#choose-file-container-<?php echo $params['id']; ?>").html(data.html);
          },
          complete: function (jqXHR, textStatus) {
            is_keyup = true;
          }
        });
      }
    });
    $(".choose-file-container").on('click', ".choose-files-choose-one", function () {
      var opener = {CKEditor: {}},
      dir_path = '<?php echo Configure::read('AMU.directory'); ?>',
        dir_sub_path = '<?php echo Configure::read('AMU.sub_directory'); ?>',
        file_id = $(this).data('fileid'),
        file_name = $(this).data('filename'),
        file_path = $(this).data('path'),
        fileURL = '/' + dir_path + '/' + dir_sub_path + '/' + file_path;
      if (window.parent && window.parent.CKEDITOR)
        opener.CKEditor.object = window.parent.CKEDITOR;
      else if (window.opener && window.opener.CKEDITOR) {
        opener.CKEditor.object = window.opener.CKEDITOR;
        opener.callBack = true;
      } else
        opener.CKEditor = null;

      if (opener.CKEditor.object) {
        opener.CKEditor.object.tools.callFunction(<?php echo $CKfuncNum; ?>, fileURL, function () {
          var element,
            dialog = this.getDialog(),
            currentTabId = dialog._.currentTabId;

          element = dialog.getContentElement(currentTabId, 'txtName');
          if (element)
            element.setValue(file_name);
          element = dialog.getContentElement(currentTabId, 'txtId');
          if (element)
            element.setValue(file_id);

          return true;
        });
      }
      window.close();
    });
  });
</script>
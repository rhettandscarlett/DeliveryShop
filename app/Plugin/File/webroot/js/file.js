var FilePlugin = {
  globalVariable: {},
  timer: 0,
  init: function() {
    FilePlugin.openSelectFile();
    FilePlugin.chooseFileFromList();
    FilePlugin.removeFileSelected();
    FilePlugin.upload();
    FilePlugin.filter();
    FilePlugin.multiDelete();
  },
  multiDelete: function () {
    $("#_checkall").on("change", function () {
      var flag = $(this).prop("checked");
      $("._file_element").each(function () {
        var el = $(this);
        if (flag) {
          el.prop('checked', true).attr('checked', true).parent().find('a:first').addClass('checked');
        } else {
          el.prop('checked', false).attr('checked', false).parent().find('a:first').removeClass('checked');
        }
      });
    });
    $('#_delete_multi_file').on("click", function(){
      var listFile = {};
      var listFolder = {};
      var i = 0;
      var j = 0;
      $('input._file_element[type=checkbox]').each(function () {
        if(this.checked){
          listFile[i] = $(this).data("file-id");
          i++;
        }
      });
      $('input._folder_element[type=checkbox]').each(function () {
        if(this.checked){
          listFolder[j] = $(this).data("folder-id");
          j++;
        }
      });

      if(!$.isEmptyObject(listFile) || !$.isEmptyObject(listFolder)){
        var jListFile = JSON.stringify(listFile);
        var jListFolder = JSON.stringify(listFolder);
        var r = confirm(sf.file.delete_confirm);
        if (r == true) {
          $.post("/file/delete_multi", {listFile: jListFile,listFolder: jListFolder}, function(result){
              location.reload();
          });
        }
      }
    });
  },
  openSelectFile: function() {
    var bodyElement = $("body");
    bodyElement.on("click", ".form-file-element-button-choose-files", function(e) {
//      var categoryCode = $(this).attr('data-categorycode');
      var eId = $(this).attr('data-id');
      var chooseFileLink = $("#choose-file-link-choose-files-" + eId);
      chooseFileLink.trigger("click");
      e.preventDefault();
    });
  },
  appendElement: function(eId, categoryCode, fileId, lang) {
    var jsonParams = globalJsonParams[categoryCode];
    jsonParams.fileId = fileId;
    jsonParams.lang = lang;
    jsonParams.id = eId;
    var request = $.ajax({
      url: "/file/get_element",
      type: "POST",
      data: {File: jsonParams},
      dataType: "json",
      async: false
    });
    request.done(function(response) {
      if (response.status == 200) {
        /*if ($('#form-selected-file-container-files').size()) {
          $('#form-selected-file-container-files').append(response.html);
        } else if ($('#sf-file-img-thumbnail-' + eId).size()) {
          $('#sf-file-img-thumbnail-' + eId).html(response.html);
        }*/
        if($('.file-choose-file-row-empty-row').size()){
          $('.file-choose-file-row-empty-row').addClass("hide");
        }
        if (lang) {
          var destElement = $('#file-form-element-lang-' + eId + "-" + lang + "-" + FilePlugin.globalVariable.mainFileId);
          destElement.html(response.html + "<br>");
          $("#file-form-element-file-id-" + eId + "-" + FilePlugin.globalVariable.mainFileId + "-" + lang).val(fileId);
        }else{
          if ($('#sf-file-img-thumbnail-' + eId).size()) {
            $('#sf-file-img-thumbnail-' + eId).html(response.html);
          }else if ($('#form-selected-file-container-'+eId).size()) {
            $('#form-selected-file-container-'+eId).append(response.html);
          }
        }
      } else {
        alert("Request failed");
      }
    });
    request.fail(function(jqXHR, textStatus) {
      alert("Request failed");
    });
  },
  clearElement: function(eId) {
    var fileIdElement = '<input type="hidden" name="data[File][selected_files][' + eId + '][][id]" value="">';
    $('#sf-file-img-thumbnail-' + eId).append(fileIdElement);
  },
  chooseFileFromList: function() {
    var bodyElement = $("body");
    bodyElement.on("click", ".choose-files-choose-one", function() {
      var fileId = $(this).attr('data-fileid');
      var categoryCode = $(this).attr('data-categorycode');
      var eId = $(this).attr('data-id');
      var lang = $(this).attr('data-lang');
      FilePlugin.appendElement(eId, categoryCode, fileId, lang);
      //show remove button
      $("#sf-btn-remove-element-" + eId).removeClass('hide');
      //hide "select image" button
      $("#sf-file-button-choose-files-" + eId).addClass('hide');
      //hide dialog list file
      var jsonParams = globalJsonParams[categoryCode];
      var limitFileNumber = jsonParams.limitFileNumber;

      var totalSelectedFile = $('#form-selected-file-container-' + eId).attr("data-total-selected-file");
      totalSelectedFile = totalSelectedFile ? parseInt(totalSelectedFile) : 1;
      if (totalSelectedFile >= limitFileNumber) {
        $('#sfDialogModel').modal('hide');
        $('#choose-file-button-choose-files-' + eId).addClass("hide");
        //hide upload button
        $("#form-file-element-button-upload-files-" + eId).addClass('hide');
      }
      $('#form-selected-file-container-' + eId).attr("data-total-selected-file", (totalSelectedFile + 1));
      $(this).parent().parent().parent().remove();//remove box contain file

      var jsCallback = jsonParams.jsCallback;
      if (jsCallback.trim().length != 0) {
        eval(jsCallback);
      }
    });

    bodyElement.on("click", ".file-form-element-choose-file-one-lang", function() {
//      var categoryCode = $(this).attr('data-categorycode');
      var eId = $(this).attr('data-id');
      var url = $(this).attr("href");
      var chooseFileLangLink = $("#choose-file-link-choose-files-langs-" + eId);
      FilePlugin.globalVariable.mainFileId = $(this).attr('data-for_file_id');
      chooseFileLangLink.attr("href", url);
      chooseFileLangLink.trigger("click");
      return false;
    });

  },
  /**
   * remove selected file and choose new file
   * @returns {undefined}
   */
  removeFileSelected: function() {
    $("button.sf-file-btn-remove").on("click", function(e) {
//      var categoryCode = $(this).attr('data-categorycode');
      var eId = $(this).attr('data-id');
      var defaultImage = $("#sf-file-default-img-" + eId).html();
      //append default image to view
      $("#sf-file-img-thumbnail-" + eId).html(defaultImage);
      //hide remove button
      $(this).addClass("hide");
      //show choose file button
      $('#sf-file-button-choose-files-' + eId).removeClass("hide");
      //show upload button
      $('#form-file-element-button-upload-files-' + eId).removeClass("hide");
      //remove all file id in hidden field
      FilePlugin.clearElement(eId);
      return false;
    });
    var bodyElement = $("body");

    //remove one element on multilang
    bodyElement.on("click", ".choose-files-remove-one", function() {
      
//      var categoryCode = $(this).attr('data-categorycode');
      var fileID = $(this).attr('data-fallback_fileid');
      var eId = $(this).attr('data-id');
      $('#choose-file-button-choose-files-' + eId).removeClass("hide");
        //hide upload button
      $("#form-file-element-button-upload-files-" + eId).removeClass('hide');

      $("#file-choose-selected-file-langs-" + eId + "-" + fileID).remove();
      $(this).parent().parent().remove();
    });
    //remove one element lang
    bodyElement.on("click", ".choose-files-selected-lang-remove-one", function() {
//      var categoryCode = $(this).attr('data-categorycode');
      var fileId = $(this).attr('data-fileid');
      var lang = $(this).attr('data-lang');
      var eId = $(this).attr('data-id');

      $("#file-form-element-lang-" + eId + "-" + lang + "-" + fileId).html('');
      $("#file-form-element-file-id-" + eId + "-" + fileId + "-" + lang).val('');
    });
    bodyElement.on("click", "#btn-close-file", function() {
      $('#sfDialogModel').modal('hide');
    });

  },
  upload: function() {
    var bodyElement = $("body");
    //upload file
    bodyElement.on("click", ".form-file-element-button-upload-files", function() {
      var categoryCode = $(this).attr('data-categorycode');
      var eId = $(this).attr('data-id');
      FilePlugin.globalVariable.categoryCode = categoryCode;
      FilePlugin.globalVariable.eId = eId;
      $("#file-file-element-button-upload-" + eId).trigger('click');
      /*
      var jsonParams = globalJsonParams[categoryCode];
      var limitFileNumber = jsonParams.limitFileNumber;
      //show remove button
      $("#sf-btn-remove-element-" + eId).removeClass('hide');
      //hide "select image" button
      $("#sf-file-button-choose-files-" + eId).addClass('hide');

      var totalSelectedFile = $('#form-selected-file-container-' + eId).attr("data-total-selected-file");
      totalSelectedFile = totalSelectedFile ? parseInt(totalSelectedFile) : 1;
      if (totalSelectedFile >= limitFileNumber) {
        $('#choose-file-button-choose-files-' + eId).addClass("hide");
        //hide upload button
        $("#form-file-element-button-upload-files-" + eId).addClass('hide');
      }*/
      
    });
    bodyElement.on("click", ".file-file-element-upload-new-language-file", function() {
      FilePlugin.globalVariable.categoryCode = $(this).attr('data-categorycode');
      FilePlugin.globalVariable.eId = $(this).attr('data-id');
      FilePlugin.globalVariable.mainFileId = $(this).attr('data-for_file_id');
      FilePlugin.globalVariable.lang = $(this).attr('data-lang');
      $("#file-file-element-button-upload-" + FilePlugin.globalVariable.eId).trigger('click');
      return false;
    });
  },

  filterFolder: function(categoryCode, eId, folderElement){
    if (categoryCode) {
      clearTimeout(FilePlugin.timer);
      FilePlugin.timer = setTimeout(function() {
        FilePlugin.globalVariable.folderId = $(folderElement).val();
        FilePlugin.globalVariable.page = 1;
        FilePlugin.globalVariable.pageCount = 1;
        FilePlugin.reloadFileResultCommon(categoryCode, eId);
      }, 1000);
    }
  },

  filter: function(categoryCode, eId)
  {
    if (categoryCode) {
      clearTimeout(FilePlugin.timer);
      FilePlugin.timer = setTimeout(function() {
        FilePlugin.globalVariable.page = 1;
        FilePlugin.globalVariable.pageCount = 1;
        FilePlugin.onEventInputFilter(categoryCode, eId);
      }, 1000);
    }
  },
  onEventInputFilter: function(categoryCode, eId) {
    FilePlugin.reloadFileResultCommon(categoryCode, eId);
  },
  reloadFileResultCommon: function(categoryCode, eId){
    var page = FilePlugin.globalVariable.page ? parseInt(FilePlugin.globalVariable.page) : 2;
    $("#file-choose-file-filter-name").show();
    var jsonParams = globalJsonParams[categoryCode];
    jsonParams.filterName = $('#filter_name').val();
    jsonParams.folderId = FilePlugin.globalVariable.folderId ? parseInt(FilePlugin.globalVariable.folderId) : 0;
    var request = $.ajax({
      url: "/file/filter_files/page:" + page,
      type: "POST",
      data: jsonParams,
      dataType: "json",
      async: false
    });
    request.done(function(response) {
      if (page == 1) {
        $("#choose-file-container-" + eId).html(response.html);
      } else {
        $("#choose-file-container-" + eId).append(response.html);
      }
      $("#file-choose-file-filter-name").hide();
      FilePlugin.globalVariable.page = page + 1;
      FilePlugin.globalVariable.pageCount = response.pageCount;
    });
    request.fail(function(jqXHR, textStatus) {
      $("#file-choose-file-filter-name").hide();
    });
  }
};
/**** Upload Resource ******/
if (typeof document.getElementsByClassName != 'function') {
  document.getElementsByClassName = function() {
    var elms = document.getElementsByTagName('*');
    var ei = [];
    for (var i = 0; i < elms.length; i++) {
      if (elms[i].getAttribute('class')) {
        var ecl = elms[i].getAttribute('class').split(' ');
        for (var j = 0; j < ecl.length; j++) {
          if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
            ei.push(elms[i]);
          }
        }
      } else if (elms[i].className) {
        ecl = elms[i].className.split(' ');
        for (j = 0; j < ecl.length; j++) {
          if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
            ei.push(elms[i]);
          }
        }
      }
    }
    return ei;
  }
}
function createUploader(upload_url, lastDir, is_multiple_upload, eId, add_parameters, after_complete_one, after_complete_all, jsCallbackFunctionAfterAddOne, allowedExtensions) {
  var amuCollection = document.getElementsByClassName("FileManagerUpload" + lastDir);
  for (var i = 0, max = amuCollection.length; i < max; i++) {
    action = amuCollection[i].className.replace('FileManagerUpload', '');
    window['uploader' + i] = new qq.FileUploader({
      element: amuCollection[i],
      action: upload_url,
      debug: true,
      uploadButtonId: 'file-file-element-button-upload-' + eId,
      multiple: is_multiple_upload,
      afterCompleteSuccess: after_complete_one,
      addParameters: add_parameters,
      afterCompleteAll: after_complete_all,
      allowedExtensions: allowedExtensions,
      jsCallbackFunctionAfterAddOne: jsCallbackFunctionAfterAddOne
    });
  }
}
function afterCompleteOne(fileObject, completeOneDataParameters, jsCallbackFunctionAfterAddOne) {
  if (!fileObject.success) {
    return;
  }
  var categoryCode = FilePlugin.globalVariable.categoryCode;
  var eId = FilePlugin.globalVariable.eId;
  var lang = FilePlugin.globalVariable.lang;
  var fileId = fileObject.values.fileid;
  
  var jsonParams = globalJsonParams[categoryCode];
  var limitFileNumber = jsonParams.limitFileNumber;
  //show remove button
  $("#sf-btn-remove-element-" + eId).removeClass('hide');
  //hide "select image" button
  $("#sf-file-button-choose-files-" + eId).addClass('hide');

  var totalSelectedFile = $('#form-selected-file-container-' + eId).attr("data-total-selected-file");
  totalSelectedFile = totalSelectedFile ? parseInt(totalSelectedFile) : 1;
  if (totalSelectedFile >= limitFileNumber) {
    $('#choose-file-button-choose-files-' + eId).addClass("hide");
    //hide upload button
    $("#form-file-element-button-upload-files-" + eId).addClass('hide');
  }
  
  FilePlugin.appendElement(eId, categoryCode, fileId, lang);
}
function jsCallbackFunctionAfterAddOneDefault() {
}
function afterCompleteAll() {
  $('#fileUploadLoadingModal').modal('hide');
}
function addParameters() {
  $('#fileUploadLoadingModal').modal('show');
}
/***** End Upload Resource ******/
$(document).ready(function() {
  FilePlugin.init();


});

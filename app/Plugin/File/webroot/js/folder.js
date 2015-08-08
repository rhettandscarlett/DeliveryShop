/**
 * Created At 6/11/15.
 */

function WFolder() {
  var _wrap, _addFolder, _backToFolder, _addFolderForm;

  var _assignVars = function() {
    _wrap = $('body');
    _addFolder = $('#_addFolder');
    _backToFolder = $('#_backToFolder');
    _addFolderForm = $('#_addFolderForm');
  };

  this.init = function() {
    _assignVars();
  }
}

$(document).ready(function() {
  var folder = new WFolder();
  folder.init();

});

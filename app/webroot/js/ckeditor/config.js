/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
  // Define changes to default configuration here. For example:
  // config.language = 'fr';
  // config.uiColor = '#AADC6E';

  config.toolbar_WindowShop = [
    {name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates']},
    {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
    {name: 'colors', items: ['TextColor', 'BGColor']},
    {name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']},
//    {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt']},
//    {name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']},
    '/',
    {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
    {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv',
        '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
    {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
    '/',
    {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
//    {name: 'tools', items: ['Maximize', 'ShowBlocks', '-', 'About']}
  ];

  config.toolbar = 'WindowShop';
  config.allowedContent = true;
  config.defaultLanguage = 'de';
  config.language = 'de';

  config.filebrowserImageBrowseUrl = '/file/dialog_choose_files?selectedItemViewType=thumbnail&listItemViewType=name&limitFileNumber=1&limitFileSize=100&extensions%5B0%5D=png&extensions%5B1%5D=jpg&extensions%5B2%5D=gif&filterName=&jsCallback=&lang=&inputName=0&inputDesc=0&inputOrder=0&isMultiLang=1&isShowThumbnail=1&type=image';
  config.filebrowserImageWindowWidth = '960';
  config.filebrowserImageWindowHeight = '640';
//  config.filebrowserImageBrowseUrl = '';
//  config.filebrowserBrowseUrl = '';
//	config.filebrowserFlashBrowseUrl = '';
//	config.filebrowserUploadUrl = '';
//	config.filebrowserImageUploadUrl = '';
//	config.filebrowserFlashUploadUrl = '';
  config.contentsCss = ['/theme/WindowShop/css/bootstrap.min.css', '/theme/WindowShop/css/font-awesome.min.css', '/theme/WindowShop/css/windowshop.css', '/files/static-page.css'];
};

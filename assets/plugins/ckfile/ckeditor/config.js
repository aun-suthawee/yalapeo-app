/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
  // config.language = 'th';
  config.skin = 'office2013';
  config.height = '30em';
  config.contentsCss = [
    '/assets/common/ckeditor-bootstrap/css/ckeditor-bootstrap.min.css',
    '/assets/plugins/node_modules/@fortawesome/fontawesome-free/css/all.min.css',
  ];
  config.extraPlugins = 'youtube,btgrid,btbutton,bootstrapTabs,ckeditorfa';
  config.removePlugins = 'exportpdf,uploadimage';
  config.allowedContent = true;
  config.filebrowserBrowseUrl = '/assets/plugins/ckfile/ckfinder/ckfinder.html';
  config.filebrowserImageBrowseUrl = '/assets/plugins/ckfile/ckfinder/ckfinder.html?type=Images';
  config.filebrowserFlashBrowseUrl = '/assets/plugins/ckfile/ckfinder/ckfinder.html?type=Flash';
  config.filebrowserUploadUrl = '/assets/plugins/ckfile/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder=/userfile/';
  config.filebrowserImageUploadUrl = '/assets/plugins/ckfile/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/userfile/';
  config.filebrowserFlashUploadUrl = '/assets/plugins/ckfile/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
  config.enterMode = CKEDITOR.ENTER_BR;
  config.shiftEnterMode = CKEDITOR.ENTER_P;
}

CKEDITOR.dtd.$removeEmpty['span'] = false;

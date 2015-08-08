<?php

/**
 *
 * Dual-licensed under the GNU GPL v3 and the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Suman (srs81 @ GitHub)
 * @package       plugin
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *                and/or GNU GPL v3 (http://www.gnu.org/copyleft/gpl.html)
 */
App::uses('FileLib', 'File.Lib');
App::import("Model", "FileManager.FileObject");

class UploadHelper extends AppHelper {
  /* ================================================
    Function description
    Get a string contain html to list uploaded files (edit mode, contain delete button).

    Parameters:
    $object_file_records: array or NULL, contain array records object files to list.
    $is_show_desc: Boolean, TRUE: show name and description of files, FALSE: do not show name and description.
    Returns:
    string: contain html to list uploaded files.
    ================================================== */

  public function viewEdit($category_code, $object_file_records, $is_show_desc, $is_show_name) {
    $webroot_url = Router::url("/") . "file_manager";
    $str = $this->renderEditListing($object_file_records, $category_code, $webroot_url, $is_show_desc, $is_show_name);

    return $str;
  }


  /* ================================================
  Function description
  Get a string contain html to list uploaded files (in show mode).

  Parameters:
  $object_id: string, contain an object id to load uploaded files.
  Returns:
  string: contain html to list uploaded files.
  ================================================== */
  public function viewListing($object_id){
    $object_file_model = new FileObject();
    $configs = $object_file_model->getFileConfig($object_id);
    $config_upload = $configs['upload'];
    $link_config = $configs['link'];

    $upload_categories = FileManagerLib::getCategories($config_upload);
    $link_categories = FileManagerLib::getCategories($link_config);
    $category_codes = array_unique(array_merge(array_keys($upload_categories), array_keys($link_categories)));

    if (empty($category_codes)){
      return "";
    }

    $str = '<h4>Files</h4>';

    foreach($category_codes as $category_code){
      if (isset($upload_categories[$category_code]['category_name'])){
        $category_name = $upload_categories[$category_code]['category_name'];
      }
      else {
        $category_name = $link_categories[$category_code]['category_name'];
      }

      $is_show_name = FileManagerLib::isEnableInputName($config_upload, $category_code);
      if (!$is_show_name){
        $is_show_name = FileManagerLib::isEnableInputName($link_config, $category_code);
      }

      $is_show_desc = FileManagerLib::isEnableInputDesc($config_upload, $category_code);
      if (!$is_show_desc){
        $is_show_desc = FileManagerLib::isEnableInputDesc($link_config, $category_code);
      }

      $object_file_records = $this->listing($object_id, $category_code);

      if (!empty($object_file_records)){
        $str .= '<div id="file-manager-' . $category_code . '" class="panel panel-info"><div class="panel-heading">' . $category_name . '</div>';
        $str .= $this->renderShowListing($object_file_records, $is_show_name, $is_show_desc);
        $str .= '</div>';
      }
    }

    return $str;
  }

  /* ================================================
    Function description
    Get a string contain html to list uploaded files in edit mode.

    Parameters:
    $object_file_records: array or NULL, contain array records object files to list.
    $webroot_url: string, contain web root url to render a link to delete uploaded file.
    $is_show_desc: Boolean, TRUE: show name and description of files, FALSE: do not show name and description.
    Returns:
    string: contain html to list uploaded files.
    ================================================== */

  public function renderEditListing($object_file_records, $category_code, $webroot_url, $is_show_desc, $is_show_name) {
    $str = '<table class="table" id="listing-' . $category_code . '">';
    $str .= "<thead><th>File</th>";
    if ($is_show_name) {
      $str .= "<th>Name</th>";
    }
    if ($is_show_desc) {
      $str .= "<th>Description</th>";
    }

    $str .= "<th></th></thead>";
    $str .= "<tbody>";

    if (empty($object_file_records)) {
      $str .= '<tr id="listing-row-empty-' . $category_code . '">' ;
      $colspan = 2 + ($is_show_desc ? 1 : 0) + ($is_show_name ? 1 : 0);
      $str .= '<td colspan="' . $colspan. '">Empty files</td></tr>';
      $str .= "</tbody></table>";

      return $str;
    }
    else {
      $str .= '<tr id="listing-row-empty-' . $category_code . '" style="display: none;"><td colspan="' . ($is_show_desc ? 4 : 2) . '">Empty files</td></tr>';
    }

    foreach ($object_file_records as $file_record) {
      $file = $file_record['File'];
      $file_object_id = $file_record['FileObject']['id'];

      $url = FileManagerLib::getUrlFile($file['path']);
      $str .= "<tr id='listing-object-file-" . $file_object_id . "'>";
      $str .= "<td>";
      $str .= "<img src='" . Router::url("/") . "FileManager/img/fileicons/{$file['file_type']}.png' /> ";
      $str .= "<a href='$url'>" . $file['filename'] . "</a> (" . $this->format_bytes($file['size']) . ")";
      $str .= "</td>";

      if ($is_show_desc) {
        $str .= "<td>" . $this->truncateString($file_record['FileObject']['name'], Configure::read('AMU.limit_show_name'));
        $str .= "</td>";
        $str .= "<td>" . $this->truncateString($file_record['FileObject']['desc'], Configure::read('AMU.limit_show_desc'));
        $str .= "</td>";
      }

      $str .= '<td align="right" style="padding-right: 20px;">';
      $delUrl = "$webroot_url/uploads/delete/" . $file_object_id;
      $str .= "<a href='#' onClick=\"deleteFileObject('$file_object_id', '$category_code', '$delUrl');return false;\"><img src='" . Router::url("/") . "FileManager/img/delete.png' alt='Delete' /></a> ";
      $str .= "</td>";
      $str .= "</tr>";
    }

    $str .= "</tbody></table>";

    return $str;
  }

  /* ================================================
    Function description
    Get a string contain html to list uploaded files in show mode (do not show delete button).

    Parameters:
    $object_file_records: array or NULL, contain array records object files to list.
    $is_show_desc: Boolean, TRUE: show name and description of files, FALSE: do not show name and description.
    Returns:
    string: contain html to list uploaded files.
    ================================================== */

  public function renderShowListing($object_file_records, $is_show_name, $is_show_desc) {
    $str = '<table class="table">';
    $str .= "<thead><th>File</th>";
    if ($is_show_name) {
      $str .= "<th>Name</th>";
    }
    if ($is_show_desc) {
      $str .= "<th>Description</th>";
    }
    $str .= "</thead>";
    $str .= "<tbody>";
    if (empty($object_file_records)) {
      $colspan = 2 + ($is_show_desc ? 1 : 0) + ($is_show_name ? 1 : 0);
      $str .= '<tr><td colspan="' . $colspan. '">Empty files</td></tr>';
      $str .= "</tbody></table>";

      return $str;
    }

    foreach ($object_file_records as $file_object_record) {
      $file = $file_object_record['File'];

      $url = FileManagerLib::getUrlFile($file['path']);
      $str .= "<tr>";
      $str .= "<td>";
      $str .= "<img src='" . Router::url("/") . "FileManager/img/fileicons/{$file['file_type']}.png' /> ";
      $str .= "<a href='$url'>" . $file['filename'] . "</a> (" . $this->format_bytes($file['size']) . ")";
      $str .= "</td>";
      if ($is_show_name){
        $str .= "<td>" . $this->truncateString($file_object_record['FileObject']['name'], Configure::read('AMU.limit_show_name'));
        $str .= "</td>";
      }
      if ($is_show_desc) {
        $str .= "<td>" . $this->truncateString($file_object_record['FileObject']['desc'], Configure::read('AMU.limit_show_desc'));
        $str .= "</td>";
      }
      $str .= "</tr>";
    }
    $str .= "</tbody></table>";

    return $str;
  }

  /* ================================================
    Function description
    Get array of records object file from database.

    Parameters:
    $object_id: string|integer, contain object id to load uploaded file records.
    Returns:
    array: contain object file records and file records.
    ================================================== */

  public function listing($object_id, $category_code) {
    $object_file_model = new FileObject();
    $object_file_records = $object_file_model->findAllByObjectIdAndCategoryCode($object_id, $category_code);

    return $object_file_records;
  }

  /* ================================================
    Function description
    Get a string contain html to list uploaded files in edit mode and upload button to upload one|multiple files.

    Parameters:
    $object_id: string|integer, contain object id to list uploaded files and insert new records when upload a new file.
    Returns:
    string: contain html to list uploaded files and a form to upload new file.
  ================================================== */
  public function edit($object_id) {
    $resource_webroot = Router::url("/") . "FileManager";
    $webroot_url = Router::url("/") . "file_manager";

    $object_file_model = new FileObject();
    $configs = $object_file_model->getFileConfig($object_id);

    $config_upload = $configs['upload'];
    $link_config = $configs['link'];

    $upload_categories = FileManagerLib::getCategories($config_upload);
    $link_categories = FileManagerLib::getCategories($link_config);
    $category_codes = array_unique(array_merge(array_keys($upload_categories), array_keys($link_categories)));

    if (empty($category_codes)){
      return "";
    }

    $html_forms = <<<END
      <link rel="stylesheet" type="text/css" href="$resource_webroot/css/fileuploader.css" />
END;

    $html_forms .= '<h4>Files</h4>';

    foreach($category_codes as $category_code){
      $object_file_records = $this->listing($object_id, $category_code);
      $current_nb_files = count($object_file_records);

      $category_name = '';
      $upload_form = '';
      $link_form = '';

      //render upload form
      $upload_limit_nb_files = NULL;
      $upload_is_enable_input_desc = FALSE;
      $upload_is_enable_input_name = FALSE;
      if (isset($config_upload[$category_code])){
        $upload_limit_nb_files = FileManagerLib::getLimitNumberFiles($config_upload, $category_code);
        $upload_is_enable_input_desc = FileManagerLib::isEnableInputDesc($config_upload, $category_code);
        $upload_is_enable_input_name = FileManagerLib::isEnableInputName($config_upload, $category_code);
        $upload_form = $this->renderUploadForm($upload_categories[$category_code], $object_id, $upload_is_enable_input_desc,
          $upload_is_enable_input_name, $upload_limit_nb_files, $current_nb_files);
        $category_name = $upload_categories[$category_code]['category_name'];
      }

      //render link form
      $link_limit_nb_files = NULL;
      $link_is_enable_input_desc = FALSE;
      $link_is_enable_input_name = FALSE;
      if (isset($link_config[$category_code])){
        $link_limit_nb_files = FileManagerLib::getLimitNumberFiles($link_config, $category_code);
        $link_is_enable_input_desc = FileManagerLib::isEnableInputDesc($link_config, $category_code);
        $link_is_enable_input_name = FileManagerLib::isEnableInputName($link_config, $category_code);

        $link_form = $this->renderLinkForm($link_categories[$category_code], $object_id, $link_config,
          $link_is_enable_input_name, $link_is_enable_input_desc, $link_limit_nb_files, $current_nb_files);
        $category_name = $link_categories[$category_code]['category_name'];
      }

      if (!isset($upload_limit_nb_files) && !isset($link_limit_nb_files)){
        $limit_nb_files = -1;// no limit
      }
      else{
        $limit_nb_files = max($upload_limit_nb_files, $link_limit_nb_files);
      }

      $is_enable_input_desc = ($link_is_enable_input_desc || $upload_is_enable_input_desc);
      $is_enable_input_name = ($link_is_enable_input_name || $upload_is_enable_input_name);

      $html_forms .= '<div class="panel panel-info" id="file-manager-' . $category_code . '" data-limit_files="' . $limit_nb_files . '">';
      $html_forms .= '<div class="panel-heading">' . $category_name . '</div>';
      $html_forms .= $this->viewEdit($category_code, $object_file_records, $is_enable_input_desc, $is_enable_input_name);
      $html_forms .= $upload_form . $link_form;
      $html_forms .= '</div>';
    }

    $html_forms .=<<<END
			<script src="$resource_webroot/js/fileuploader.js" type="text/javascript"></script>
			<script>
				if (typeof document.getElementsByClassName!='function') {
				    document.getElementsByClassName = function() {
				        var elms = document.getElementsByTagName('*');
				        var ei = new Array();
				        for (i=0;i<elms.length;i++) {
				            if (elms[i].getAttribute('class')) {
				                ecl = elms[i].getAttribute('class').split(' ');
				                for (j=0;j<ecl.length;j++) {
				                    if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
				                        ei.push(elms[i]);
				                    }
				                }
				            } else if (elms[i].className) {
				                ecl = elms[i].className.split(' ');
				                for (j=0;j<ecl.length;j++) {
				                    if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
				                        ei.push(elms[i]);
				                    }
				                }
				            }
				        }
				        return ei;
				    }
				}
				function createUploader(categoryCode, webroot_url, lastDir, multiple_upload){
					var amuCollection = document.getElementsByClassName("FileManagerUpload" + lastDir);
					for (var i = 0, max = amuCollection.length; i < max; i++) {
							action = amuCollection[i].className.replace('FileManagerUpload', '');
							window['uploader'+i] = new qq.FileUploader({
								element: amuCollection[i],
								action: webroot_url + '/uploads/upload/' + action + '/',
								debug: true,
								multiple: multiple_upload,
								params: {categoryCode: categoryCode}
							});
          }
        }
END;

    $commands = '';
    foreach($category_codes as $category_code){
      $is_enable_input_desc = FileManagerLib::isEnableInputDesc($config_upload, $category_code);
      $lastDir = "Object___"  . $category_code . "___". $object_id;
      $commands .= "createUploader('$category_code', '$webroot_url', '$lastDir', " . ($is_enable_input_desc ? 'false' : 'true') .");";
    }

    $html_forms .=<<<END
        function createUploaders(){
          $commands
        }
				window.onload = createUploaders;
			</script>
END;

    return $html_forms;
  }

  public function renderUploadForm($category, $object_id, $is_enable_input_desc, $is_enable_input_name, $limit_nb_files, $current_nb_files){
    $category_code = $category['category_code'];
    $lastDir = "Object___"  . $category_code . "___". $object_id;

    $str = '';

    $form_style = '';
    if ($limit_nb_files != FileManagerLib::CONFIG_NO_LIMIT_NB_FILES && $current_nb_files >= $limit_nb_files){
      $form_style = 'display: none';
    }

    $str .= '<div id="upload-container-' . $category_code .'" class="well" style="' .$form_style . '">';
    $str .= '<h4>Upload new file</h4>';

    $file_desc_html = '';
    if ($is_enable_input_name) {
      $file_desc_html .= <<<END
        <div class="form-group">
          <label for="FileManagerFileName-$category_code">Name</label>
          <input id="FileManagerFileName-$category_code" type="string" size="15" value="" maxlength="255" class="form-control">
        </div>
END;
    }

    if ($is_enable_input_desc) {
      $file_desc_html .= <<<END
        <div class="form-group">
          <label for="FileManagerFileDesc-$category_code">Description</label>
          <textarea id="FileManagerFileDesc-$category_code" rows="4" cols="30" class="form-control"></textarea>
        </div>
END;
    }
    if (!empty($file_desc_html)){
      $str .= '<div id="input-desc-container-' . $category_code . '">';
      $str .= $file_desc_html;
      $str .= '</div>';
    }

    $str .= <<<END
			<div class="FileManagerUpload$lastDir" name="AjaxMultiUpload" id="file_manager_upload_form_$category_code">
				<noscript>
					 <p>Please enable JavaScript to use file uploader.</p>
				</noscript>
			</div>
			</div>
END;

    return $str;
  }

  public function renderLinkForm($category, $object_id, $link_config, $is_enable_input_name, $is_enable_input_desc,
                                 $limit_nb_files, $current_nb_files){
    $allowed_extensions = FileManagerLib::getAllowedExtensionsFile($link_config, $category['category_code']);
    $webroot_url = Router::url("/") . "file_manager";

    $view = new View();
    $view->viewPath = 'Elements';

    $uploaded_row_html = $view->element('FileManager.link_form', array(
      'is_enable_input_name' => $is_enable_input_name,
      'is_enable_input_desc' => $is_enable_input_desc,
      'object_id' => $object_id,
      'category_code' => $category['category_code'],
      'allowed_extensions' => $allowed_extensions,
      'webroot_url' => $webroot_url,
      'limit_nb_files' => $limit_nb_files,
      'current_nb_files' => $current_nb_files,
    ));

    return $uploaded_row_html;
  }
}

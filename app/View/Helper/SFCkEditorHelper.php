<?php

class SFCkEditorHelper extends Helper {

  public $helpers = array('Html', 'Javascript');

  /**
   *
   * @param type $id  this is the id to replace
   * @param type $settings
   * @return type
   */
  public function load($id, $settings = null) {
    $configuration = $this->_config($settings);
    $code = "";
    if (isset($settings['hideToggleLinks']) && $settings['hideToggleLinks'] == true) {
      $code .= "
        if (typeof window.toggleExtras === 'undefined') {
          function toggleExtras() {
            $('.cke_toolbar_break').nextAll().toggle();
          }
        }

        $('.exec-source').click(function() {
          // get ID of our textarea from the mode toggle link that was clicked
          var fieldName =  $(this).attr('id').replace('_exec-source', '').replace('_', '.').split('.');
          fieldName.forEach(function(val, index, array) {
            fieldName[index] = fieldName[index].charAt(0).toUpperCase() + fieldName[index].slice(1);
          });
          var actualFieldName = '';
          $.each(fieldName, function(i, v) {
            if ( v !== undefined ) {
              actualFieldName += v;
            }
          });

          var editor = CKEDITOR.instances[actualFieldName];
          if ( editor.mode == 'wysiwyg' ) {
            editor.execCommand( 'source' );
            //toggleExtras();
            $('.cke_toolbar_break').nextAll().hide();
            $('#'+actualFieldName).parent().parent().find('.exec-source').html('<i class=\"icon-edit\"></i> DESIGN');
          } else {
            editor.execCommand( 'source' );
            //toggleExtras();
            $('#'+actualFieldName).parent().parent().find('.exec-source').html('<i class=\"icon-wrench\"></i> HTML');
          }
        });";
    }

    if ($configuration) {
      $code .= "
			//var editor_id = '$id';
			CKEDITOR.replace( '$id', {
				$configuration
			});";
    }
    return $this->Html->scriptBlock($code);
  }

  protected function _fileManager() {
    if (CakeSession::read('Auth.User') && defined('WWW_ROOT')) {

    } else {
      return null;
    }
  }

  protected function _config($settings) {
    // color settings
    if (!empty($settings['uiColor'])) {
      $color = "uiColor: '" . $settings['uiColor'] . "',";
    }

    $paths = $this->_fileManager();

    // button settings
    if (!empty($settings['buttons'])) {
      $button = "
					toolbar :
					[
						[";
      foreach ($settings['buttons'] as $but) {
        $button .= "'" . $but . "',";
      }
      $button .= "]
					],";
    }

    // stylesheet settings
    if (!empty($settings['contentsCss'])) {
      if (!empty($output)) {
        $output .= "contentsCss : ['" . $settings['contentsCss'] . "'],";
      } else {
        $output = "contentsCss : ['" . $settings['contentsCss'] . "'],";
      }
    }


    if (!empty($color)) {
      // add in color if it exsists
      if (!empty($output)) {
        $output .= $color;
      } else {
        $output = $color;
      }
    }
    if (!empty($paths)) {
      // add in color if it exsists
      if (!empty($output)) {
        $output .= $paths;
      } else {
        $output = $paths;
      }
    }
    if (!empty($button)) {
      // add in buttons if they exist
      if (!empty($output)) {
        $output .= $button;
      } else {
        $output = $button;
      }
    }

    if (!empty($output)) {
      return $output;
    } else {
      return false;
    }
  }

}

<?php

App::uses('FormHelper', 'View/Helper');
App::uses('Set', 'Utility');

class SFFormHelper extends FormHelper {

  protected $_divOptions = array();
  protected $_inputOptions = array();
  protected $_inputType = null;
  protected $_fieldName = null;

  public function __construct(View $View, $settings = array()) {
    parent::__construct($View, $settings);
    $this->View = $View;
  }

  public function input($fieldName, $options = array()) {

    $this->_fieldName = $fieldName;

    $multiLanguageVars = explode('.', $fieldName);
    if (count($multiLanguageVars) == 1) {
      $multiLanguageClass = $this->defaultModel;
      $multiLanguageField = $fieldName;
    } elseif (count($multiLanguageVars) == 2) {
      $multiLanguageClass = $multiLanguageVars[0];
      $multiLanguageField = $multiLanguageVars[1];
    } elseif (count($multiLanguageVars) == 3) {
      $multiLanguageClass = $multiLanguageVars[0];
      $multiLanguageField = $multiLanguageVars[2];
      $multiLanguageIdFind = $multiLanguageVars[1];
    } else {
      $multiLanguageClass = '';
      $multiLanguageField = '';
    }
    $multiLanguageHas = false;
    if (!empty($multiLanguageClass) && !empty($multiLanguageField) && !isset($options['multiLanguage'])) {
      $multiLanguageModel = $this->_getModel($multiLanguageClass);
      if (isset($multiLanguageModel->multiLanguage['columns']) && in_array($multiLanguageField, $multiLanguageModel->multiLanguage['columns'])) {
        $multiLanguageHas = true;
        if (!isset($_POST['MultiLanguage'][$multiLanguageClass])) {
          if (isset($multiLanguageIdFind)) {
            $multiLanguageModel->idFind = Sanitize::escape($multiLanguageIdFind);
          }
          if (isset($multiLanguageModel->idFind)) {
            $multiLanguageData = Hash::combine($multiLanguageModel->query("SELECT MultiLanguage.{$multiLanguageField}, MultiLanguage.lang_code FROM multilanguage_{$multiLanguageModel->useTable} MultiLanguage WHERE object_id = '{$multiLanguageModel->idFind}'"), '{n}.MultiLanguage.lang_code', '{n}.MultiLanguage.' . $multiLanguageField);
          } else {
            $multiLanguageData = array();
          }
        } else {
          if (isset($multiLanguageIdFind)) {
            $multiLanguageData = $_POST['MultiLanguage'][$multiLanguageClass][$multiLanguageIdFind][$multiLanguageField];
          } else {
            $multiLanguageData = $_POST['MultiLanguage'][$multiLanguageClass][$multiLanguageField];
          }
        }
      }
    }

    $default = array(
      'error' => array(
        'attributes' => array(
          'wrap' => 'span',
          'class' => 'help-block text-danger'
        )
      ),
      'wrapInput' => '',
      'checkboxDiv' => 'checkbox',
      'beforeInput' => '',
      'afterInput' => '',
      'errorClass' => 'has-error error'
    );

    $options = Hash::merge($default, $this->_inputDefaults, $options);

    $this->_inputOptions = $options;

    $options['error'] = false;
    if (isset($options['wrapInput'])) {
      unset($options['wrapInput']);
    }
    if (isset($options['checkboxDiv'])) {
      unset($options['checkboxDiv']);
    }
    if (isset($options['beforeInput'])) {
      unset($options['beforeInput']);
      unset($options['errorClass']);
    }
    if (isset($options['afterInput'])) {
      unset($options['afterInput']);
    }
    if (isset($options['errorClass'])) {

    }

    $inputDefaults = $this->_inputDefaults;
    $this->_inputDefaults = array();

    //set placeholder
    if ($multiLanguageHas) {
      $options['placeholder'] = __('');
    }

    //set ordering
    if (in_array($this->_fieldName, array('order', 'ordering')) && is_null($this->value('id'))) {
      $ordering = $this->_getModel($this->defaultModel)->find('count');
      $options['value'] = $ordering + 1;
    }

    $html = parent::input($fieldName, $options);
    $this->_inputDefaults = $inputDefaults;

    if ($this->_inputType === 'checkbox') {
      if (isset($options['before'])) {
        $html = str_replace($options['before'], '%before%', $html);
      }
      $regex = '/(<label.*?>)(.*?<\/label>)/';
      if (preg_match($regex, $html, $label)) {
        $label = str_replace('$', '\$', $label);
        $html = preg_replace($regex, '', $html);
        $html = preg_replace(
          '/(<input type="checkbox".*?>)/', $label[1] . '$1 ' . $label[2], $html
        );
      }
      if (isset($options['before'])) {
        $html = str_replace('%before%', $options['before'], $html);
      }
    }

    $languages_html = '';
    $languages = Configure::read('MultiLanguage.list');
    if ($multiLanguageHas && count($languages) > 0) {
      if (isset($this->_inputOptions['div'])) {
        $languages_html .= "<div class = \"{$this->_inputOptions['div']}\">";
      }
      if ($this->_inputOptions['label'] !== false) {
        $label_class = isset($this->_inputOptions['label']['class']) ? $this->_inputOptions['label']['class'] : '';
        $languages_html .= "<label class=\"$label_class\">&nbsp;</label>";
      }
      if (isset($this->_inputOptions['wrapInput'])) {
        $languages_html .= "<div class=\"{$this->_inputOptions['wrapInput']}\">";
      }

      $languages_html .= "<ul class=\"multilanguage-list\">";
      foreach ($languages as $lang_key => $language) {
        $multiLanguageValue = isset($multiLanguageData[$lang_key]) ? htmlspecialchars($multiLanguageData[$lang_key]) : '';
        $languages_html .= "<li class=\"multilanguage-list-code-index-all multilanguage-list-code-{$lang_key}\">";
        $languages_html .= "<div class=\"input-group\">";
        $languages_html .= "<span class=\"input-group-addon\"><i class=\"flags flag-{$lang_key}\"></i> <p>{$language}</p></span>";
        $fieldDef = $this->_introspectModel($this->defaultModel, 'fields', $this->_fieldName);
        $field_id = "MultiLanguage_{$multiLanguageClass}";
        if ((isset($options['type']) && ($options['type'] == 'textarea' || $options['type'] == 'richtext') ) || $fieldDef['type'] === 'text') {
          if (isset($options['type']) && $options['type'] == 'richtext') {
            $languages_html .= "<textarea class=\"ckeditor\" rows=\"1\" name=\"MultiLanguage[{$multiLanguageClass}]";
          } else {
            $languages_html .= "<textarea rows=\"1\" name=\"MultiLanguage[{$multiLanguageClass}]";
          }
          if (isset($multiLanguageIdFind)) {
            $field_id .= "_{$multiLanguageIdFind}";
            $languages_html .= "[{$multiLanguageIdFind}]";
          }
          $field_id .= "_{$multiLanguageField}_{$lang_key}";
          $languages_html .= "[{$multiLanguageField}][{$lang_key}]\" id=\"{$field_id}\" class=\"form-control\">{$multiLanguageValue}</textarea>";
        } else {
          $languages_html .= "<input type=\"text\" value=\"{$multiLanguageValue}\" name=\"MultiLanguage[{$multiLanguageClass}]";
          if (isset($multiLanguageIdFind)) {
            $field_id .= "_{$multiLanguageIdFind}";
            $languages_html .= "[{$multiLanguageIdFind}]";
          }
          $field_id .= "_{$multiLanguageField}_{$lang_key}";
          $languages_html .= "[{$multiLanguageField}][{$lang_key}]\" id=\"{$field_id}\" class=\"form-control\">";
        }
        $languages_html .= "</div>";
        $languages_html .= "</li>";
      }
      $languages_html .= "</ul>";
      if (isset($this->_inputOptions['wrapInput'])) {
        $languages_html .= "</div>";
      }
      if (isset($this->_inputOptions['div'])) {
        $languages_html .= "</div>";
      }
    }

    return $html . $languages_html;
  }

  protected function _divOptions($options) {
    $this->_inputType = $options['type'];

    $divOptions = array(
      'type' => $options['type'],
      'div' => $this->_inputOptions['wrapInput']
    );
    $this->_divOptions = parent::_divOptions($divOptions);

    $default = array('div' => array('class' => null));
    $options = Hash::merge($default, $options);
    $divOptions = parent::_divOptions($options);
    if ($this->tagIsInvalid() !== false) {
      $divOptions = $this->addClass($divOptions, $this->_inputOptions['errorClass']);
    }
    return $divOptions;
  }

  protected function _getInput($args) {
    if ($args['type'] === 'textarea') {
      if (!isset($args['options']['rows'])) {
        $args['options']['rows'] = 1;
      }
    }
    $input = parent::_getInput($args);
    if ($this->_inputType === 'checkbox' && $this->_inputOptions['checkboxDiv'] !== false) {
      $input = $this->Html->div($this->_inputOptions['checkboxDiv'], $input);
    }

    $beforeInput = $this->_inputOptions['beforeInput'];
    $afterInput = $this->_inputOptions['afterInput'];

    $error = null;
    $errorOptions = $this->_extractOption('error', $this->_inputOptions, null);
    $errorMessage = $this->_extractOption('errorMessage', $this->_inputOptions, true);
    if ($this->_inputType !== 'hidden' && $errorOptions !== false) {
      $errMsg = $this->error($this->_fieldName, $errorOptions);
      if ($errMsg && $errorMessage) {
        $error = $errMsg;
      }
    }

    $html = $beforeInput . $input . $error . $afterInput;

    if ($this->_divOptions) {
      $tag = $this->_divOptions['tag'];
      unset($this->_divOptions['tag']);
      $html = $this->Html->tag($tag, $html, $this->_divOptions);
    }

    return $html;
  }

  protected function _selectOptions($elements = array(), $parents = array(), $showParents = null, $attributes = array()) {
    $selectOptions = parent::_selectOptions($elements, $parents, $showParents, $attributes);

    if ($attributes['style'] === 'checkbox') {
      foreach ($selectOptions as $key => $option) {
        $option = preg_replace('/<div.*?>/', '', $option);
        $option = preg_replace('/<\/div>/', '', $option);
        if (preg_match('/>(<label.*?>)/', $option, $match)) {
          $option = $match[1] . preg_replace('/<label.*?>/', ' ', $option);
          if (isset($attributes['class'])) {
            $option = preg_replace('/(<label.*?)(>)/', '$1 class="' . $attributes['class'] . '"$2', $option);
          }
        }
        $selectOptions[$key] = $option;
      }
    }

    return $selectOptions;
  }

  public function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
    $block = false;
    if (!empty($options['block'])) {
      $block = $options['block'];
      unset($options['block']);
    }

    $fields = $this->fields;
    $this->fields = array();

    $out = parent::postLink($title, $url, $options, $confirmMessage);

    $this->fields = $fields;

    if ($block) {
      $regex = '/<form.*?>.*?<\/form>/';
      if (preg_match($regex, $out, $match)) {
        $this->_View->append($block, $match[0]);
        $out = preg_replace($regex, '', $out);
      }
    }

    return $out;
  }

  protected function _ckeditorConfig($options = array()) {
    if (!empty($options['ckeSettings'])) {
      $ckeSettings = $options['ckeSettings'];
    }
    $ckeSettings['path'] = Configure::read('appPath') . '/js/file_manager/';
    return $ckeSettings;
  }

  public function richtext($fieldName, $options = array()) {
    $preLinks = isset($options['hideToggleLinks']) && $options['hideToggleLinks'] == true ?
      '<div class="ckeditorLinks">
				<a id="' . $fieldName . '_exec-source" class="exec-source"><i class="icon-wrench"></i> HTML</a>
					 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a onclick="toggleExtras();" id="toggle-extras"><i class="icon-fire"></i> TOGGLE EXTRAS</a>
			</div>' : null;

    $options['class'] = !empty($options['class']) ? $options['class'] . ' ckeditor' : 'ckeditor';

    App::uses('SFCkEditorHelper', 'View/Helper');
    $ckeditor = new SFCkEditorHelper($this->View);
    $ckeSettings = $this->_ckeditorConfig($options);
    $options = $this->_initInputField($fieldName, $options);
    $fieldId = !empty($options['id']) ? $options['id'] : $options['name'];
    $value = null;
    if (array_key_exists('value', $options)) {
      $value = $options['value'];
      if (!array_key_exists('escape', $options) || $options['escape'] !== false) {
        $value = h($value);
      }
      unset($options['value']);
    }
    $this->View->Html->script('ckeditor/ckeditor', array('inline' => false));
    return $this->View->Html->useTag('richtext', $preLinks, $options['name'], array_diff_key($options, array('type' => '', 'name' => '')), $value, $this->View->Html->script('ckeditor/adapters/jquery', array('inline' => false)), $ckeditor->load($fieldId, $ckeSettings));
  }

}

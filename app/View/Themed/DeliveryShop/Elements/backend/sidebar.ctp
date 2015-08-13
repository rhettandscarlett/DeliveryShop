<?php
$navs = array(
  array(
    'title' => __('Trainings'),
    'url' => '#',
    'icon' => 'fa-bars',
    'controller' => array('ItrainerTraining', 'ItrainerTrainingResult', 'result.training'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Trainings'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerTraining', 'action' => 'search')),
        'controller' => 'ItrainerTraining',
        'action' => '*',
      ),
      array(
        'title' => __('Results'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'result', 'action' => 'training')),
        'controller' => 'result',
        'action' => 'training',
      )
    )
  ),
  array(
    'title' => __('Vehicles'),
    'url' => '#',
    'icon' => 'fa-truck',
    'controller' => array('ItrainerModel', 'ItrainerModelCompatition', 'ItrainerVehicle', 'ItrainerTrainingCriteria', 'ItrainerTrainingCategory'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Models'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerModel', 'action' => 'searchModel')),
        'controller' => 'ItrainerModel',
        'action' => array('searchModel', 'editModel'),
      ),
      array(
        'title' => __('Competing Models'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerModel', 'action' => 'searchCompeting')),
        'controller' => 'ItrainerModel',
        'action' => array('searchCompeting', 'editCompeting'),
      ),
      array(
        'title' => __('Model Line'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerVehicle', 'action' => 'search')),
        'controller' => 'ItrainerVehicle',
        'action' => array('search', 'edit'),
      ),
      array(
        'title' => __('Vehicle Comparison'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerTrainingCriteria', 'action' => 'search')),
        'controller' => array('ItrainerTrainingCategory', 'ItrainerTrainingCriteria'),
        'action' => '*',
      )
    )
  ),
  array(
    'title' => __('Information'),
    'url' => '#',
    'icon' => 'fa-info',
    'controller' => array('ItrainerInformationCategory'),
    'action' => '',
    'childs' => array(
//      array(
//        'title' => __('Files'),
//        'url' => '/information-files',
//        'controller' => 'ItrainerInformationCategory',
//        'action' => 'file',
//      ),
      array(
        'title' => __('Categories'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerInformationCategory', 'action' => 'search')),
        'controller' => 'ItrainerInformationCategory',
        'action' => 'search',
      )
    )
  ),
  array(
    'title' => __('Pre- / Posttest'),
    'url' => '#',
    'icon' => 'fa-th-list',
    'controller' => array('ItrainerTrainingTest', 'QuestionnaireQuestion', 'result.test'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Tests'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerTrainingTest', 'action' => 'search')),
        'controller' => 'ItrainerTrainingTest',
        'action' => array('search', 'add', 'edit'),
      ),
      array(
        'title' => __('Questions'),
        'url' => Router::url(array('plugin' => 'Questionnaire', 'controller' => 'QuestionnaireQuestion', 'action' => 'search')),
        'controller' => 'QuestionnaireQuestion',
        'action' => array('search', 'add', 'edit'),
      ),
      array(
        'title' => __('Results'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'result', 'action' => 'test')),
        'controller' => 'result',
        'action' => 'test',
      )
    )
  ),
  array(
    'title' => __('Feedback'),
    'url' => '#',
    'icon' => 'fa-edit',
    'controller' => array('ItrainerFeedback', 'ItrainerFeedbackQuestion', 'ItrainerFeedbackCategory', 'result.feedback'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Questionaries'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerFeedback', 'action' => 'search')),
        'controller' => 'ItrainerFeedback',
        'action' => '*',
      ),
      array(
        'title' => __('Questions'),
        'url' => '/feedback-category',
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerFeedbackCategory', 'action' => 'search')),
        'controller' => array('ItrainerFeedbackCategory', 'ItrainerFeedbackQuestion'),
        'action' => '*',
      ),
      array(
        'title' => __('Results'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'result', 'action' => 'feedback')),
        'controller' => 'result',
        'action' => 'feedback',
      )
    )
  ),
  array(
    'title' => __('Settings'),
    'url' => '#',
    'icon' => 'fa-cogs',
    'controller' => array('ItrainerTextTranslation', 'ItrainerModule'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Languages'),
        'url' => '#',
        'controller' => '',
        'action' => '',
      ),
      array(
        'title' => __('Texts'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerTextTranslation', 'action' => 'manage')),
        'controller' => 'ItrainerTextTranslation',
        'action' => 'manage',
      ),
      array(
        'title' => __('Training Modules'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerModule', 'action' => 'search')),
        'controller' => 'ItrainerModule',
        'action' => 'search',
      )
    )
  ),
  array(
    'title' => __('Media'),
    'url' => '#',
    'icon' => 'fa-file',
    'controller' => array('File'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Library'),
        'url' => Router::url(array('plugin' => 'File', 'controller' => 'File', 'action' => 'manage')),
        'controller' => 'File',
        'action' => array('manage', 'listing'),
      ),
//      array(
//        'title' => __('Add New'),
//        'url' => '#',
//        'controller' => '',
//        'action' => '',
//      )
    )
  ),
  array(
    'title' => __('Users'),
    'url' => '#',
    'icon' => 'fa-users',
    'controller' => array('ItrainerUser'),
    'action' => '',
    'childs' => array(
      array(
        'title' => __('Users'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerUser', 'action' => 'search')),
        'controller' => 'ItrainerUser',
        'action' => 'search',
      ),
      array(
        'title' => __('Markets'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerMarket', 'action' => 'search')),
        'controller' => 'ItrainerMarket',
        'action' => array('search', 'edit'),
      ),
      array(
        'title' => __('Uploads'),
        'url' => Router::url(array('plugin' => null, 'controller' => 'ItrainerUser', 'action' => 'myspace')),
        'controller' => 'ItrainerUser',
        'action' => 'myspace',
        'class' => 'is-pending'
      )
    )
  ),
);
?>
<div id="sidebar" class="navbar-collapse collapse">
  <ul class="nav nav-list" style="height: auto;" data-spy="affix" data-offset-top="0">
    <!--li>
      <form target="#" method="GET" class="search-form">
        <span class="search-pan">
          <button type="submit">
            <i class="fa fa-search"></i>
          </button>
          <input type="text" name="search" placeholder="Search ..." autocomplete="off">
        </span>
      </form>
    </li-->
    <?php
    //debug($this->params);
    foreach ($navs as $nav) {
      $is_active = "";
      if (in_array($this->params['controller'], (array) $nav['controller']) || in_array($this->params['controller'] . '.' . $this->params['action'], (array) $nav['controller'])) {
        $is_active = " class=\"active\"";
      }
      echo "<li{$is_active}>";
      echo "<a href=\"{$nav['url']}\" class=\"dropdown-toggle\">";
      echo "<i class=\"fa {$nav['icon']}\"></i>";
      echo "<span>{$nav['title']}</span>";
      if (isset($nav['childs'])) {
        echo "<b class=\"arrow fa fa-angle-right\"></b>";
      }
      echo "</a>";
      if (isset($nav['childs'])) {
        echo "<ul class=\"submenu\">";
        foreach ($nav['childs'] as $subnav) {
          $is_active = "";
          if (in_array($this->params['controller'], (array) $subnav['controller']) && ($subnav['action'] == '*' || in_array($this->params['action'], (array) $subnav['action']))) {
            $is_active = " class=\"active\"";
          }
          echo sprintf("<li%s><a href=\"%s\" class=\"%s\">%s</a></li>", $is_active, $subnav['url'], isset($subnav['class']) ? $subnav['class'] : '', $subnav['title']);
        }
        echo "</ul>";
      }
      echo "</li>";
    }
    ?>
    <!--
    <div id="sidebar-collapse" class="visible-lg">
      <i class="fa fa-arrow-circle-left"></i>
    </div>
    -->
  </ul>

</div>
<?php

App::uses('Component', 'Controller');
App::uses('SeoRoute', 'Seo.Model');

class SeoComponent extends Component {

  public function initialize(Controller $controller) {
    $controller->constructClasses();

    $seoParams = array();
    $conditions = array();

    $seoParams['plugin'] = $controller->plugin;
    if(empty($controller->plugin)) {
      $conditions[] = 'SeoRoute.plugin IS NULL';
    } else {
      $conditions['SeoRoute.plugin'] = $controller->plugin;
    }

    $seoParams['controller'] = $controller->toString();
    $conditions['SeoRoute.controller'] =  preg_replace('/Controller$/', '', $controller->toString());

    $seoParams['action'] = $controller->action;
    $conditions['SeoRoute.action'] =  $controller->action;

    $seoParams['params'] = $controller->request->params['pass'];
    $conditions['SeoRoute.params'] = json_encode($controller->request->params['pass']);

    $seoRoute = new SeoRoute();
    $dataSeo = $seoRoute->find('first',array('conditions' => $conditions));

    if(!empty($dataSeo)) {
      $dataSeoMeta = json_decode($dataSeo['SeoRoute']['meta_data']);
      if(isset($dataSeoMeta->title) && !empty($dataSeoMeta->title)) {
        $controller->set('seoplugin_title', $dataSeoMeta->title);
      }
      if(isset($dataSeoMeta->keywords) && !empty($dataSeoMeta->keywords)) {
        $controller->set('seoplugin_keywords', $dataSeoMeta->keywords);
      }
      if(isset($dataSeoMeta->description) && !empty($dataSeoMeta->description)) {
        $controller->set('seoplugin_description', $dataSeoMeta->description);
      }
    }

    $controller->set('seoPluginParams', base64_encode(json_encode($seoParams)));
  }

}

<?php
App::uses('SeoRoute', 'Seo.Model');
App::uses('Sanitize', 'Utility');

class SeoRoutes {

  static public function connect() {
    $seoRoute = new SeoRoute();
    $route = $seoRoute->find('first', array('conditions' => array('SeoRoute.slug = "' .$_SERVER['REQUEST_URI']. '"')));

    if(!empty($route)) {
      $connect = array();
      if(isset($route['SeoRoute']['plugin'])) {
        $connect['plugin'] = $route['SeoRoute']['plugin'];
      } else {
        $connect['plugin'] = false;
      }
      $connect['controller'] = $route['SeoRoute']['controller'];
      $connect['action'] = $route['SeoRoute']['action'];
      $params = json_decode($route['SeoRoute']['params']);
      foreach($params as $param) {
        $connect[] = $param;
      }
      Router::connect($route['SeoRoute']['slug'], $connect);
    }
  }

  static public function getUrl($url) {

    $urlSlug = '';

    if(is_array($url) && count($url) > 0 && isset($url['action'])) {
      unset($url['?']);

      $request = Router::getRequest();

      if(!isset($url['controller']) && isset($request->params['controller'])) {
        $url['controller'] = $request->params['controller'];
      }
      if(isset($request->params['plugin']) && $request->params['plugin'] != false) {
        $url['plugin'] = $request->params['plugin'];
      }

      $seoRoute = new SeoRoute();
      $conditions = array();
      if(!isset($url['plugin']) || $url['plugin']==false) {
        $conditions[] = 'SeoRoute.plugin IS NULL';
      } else {
        $conditions['SeoRoute.plugin'] = $url['plugin'];
      }
      unset($url['plugin']);

      if(isset($url['controller'])) {
        $conditions['SeoRoute.controller'] = $url['controller'];
      } else {
        $conditions[] = 'SeoRoute.controller IS NULL';
      }
      unset($url['controller']);

      $conditions['SeoRoute.action'] = $url['action'];
      unset($url['action']);

      if(count($url) > 0) {
        foreach($url as $kk => $vv) {
          $url[$kk] = strval($vv);
        }
        $conditions['SeoRoute.params'] = json_encode($url);
      } else {
        $conditions[] = 'SeoRoute.params IS NULL';
      }

      $conditions[] = 'SeoRoute.slug IS NOT NULL';

      $route = $seoRoute->find('first', array('conditions' => $conditions));

      if(!empty($route)) {
        $urlSlug = $route['SeoRoute']['slug'];
      }
    }

    return $urlSlug;
  }

  static public function url($url = null, $full = false) {
    $urlSlug = SeoRoutes::getUrl($url);
    if(!empty($urlSlug)) {
      return $urlSlug;
    }
    return Router::url($url, $full);
  }

}

<?php

class SeoHelper extends AppHelper {

  /*
   * $options['plugin']
   * $options['controller']
   * $options['action']
   * $options['plugin']
   * $options['params']
   *    + $options['params']['param1']....
   * $options['prefix_slug']
   * $options['element'] : default is edit_seo_info (Elements/edit_seo_info.ctp)
   */

  public function seoContent($options) {
    $element = isset($options['element']) ? $options['element'] : 'Seo.edit_seo_info';

    App::uses('SeoRoute', 'Seo.Model');
    $seoRoute = new SeoRoute();
    $conditions = array();


    if(isset($options['plugin'])) {
      $conditions[] = 'SeoRoute.plugin = "' . Sanitize::escape($options['plugin']). '"';
    } else {
      $conditions[] = 'SeoRoute.plugin IS NULL';
    }
    if(isset($options['controller'])) {
      $conditions[] = 'SeoRoute.controller = "' . Sanitize::escape($options['controller']). '"';
    } else {
      $conditions[] = 'SeoRoute.controller IS NULL';
    }

    if(isset($options['action'])) {
      $conditions[] = 'SeoRoute.action = "' . Sanitize::escape($options['action']). '"';
    } else {
      $conditions[] = 'SeoRoute.action IS NULL';
    }

    if(isset($options['params'])) {
      $params = array();
      foreach($options['params'] as $key => $val) {
        $params[] = $val;
      }

      if(count($params) > 0) {
        $conditions[] = 'SeoRoute.params = "' . Sanitize::escape(json_encode($params)). '"';
      } else {
        $conditions[] = 'SeoRoute.params IS NULL';
      }
    }

    if(!empty($conditions)) {
      $route = $seoRoute->find('first', array('conditions' => $conditions));
      if(!empty($route)) {
        $route['SeoRoute']['meta_data'] = json_decode($route['SeoRoute']['meta_data']);
      }
    } else {
      $route['SeoRoute'] = array();
    }

    return $this->_View->element($element, array('seo_options' => $options, 'seo_data' => $route['SeoRoute']));
  }

  public function addSeo() {
    return $this->_View->element('Seo.seo_button');
  }
}




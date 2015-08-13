<?php

class SeoBehavior extends ModelBehavior {

  /**
   * Settings array
   *
   * @var array
   */
  public $settings = array();

  /**
   * Setup the behavior
   *
   * @param Model $Model
   * @param array $settings
   * @return void
   */
  function setup(\Model $model, $config = array()) {
    parent::setup($model, $config);
  }

  public function beforeFind(\Model $model, $query) {
    parent::beforeFind($model, $query);
    return $query;
  }

  public function afterFind(\Model $model, $results, $primary = false) {
    parent::afterFind($model, $results, $primary);
    return $results;
  }

  public function beforeValidate(\Model $model, $options = array()) {
    parent::beforeValidate($model, $options);
    return true;
  }

  public function afterValidate(\Model $model) {
    parent::afterValidate($model);
  }

  public function beforeSave(\Model $model, $options = array()) {
    return true;
  }

  public function afterSave(\Model $model, $created, $options = array()) {
    App::uses('SeoRoute', 'Seo.Model');
    $seoRoute = new SeoRoute();

    $data = array();
    $conditions = array();

    if(isset($model->data['seo_plugin_main']['plugin'])) {
      $data['plugin'] = $model->data['seo_plugin_main']['plugin'];
      $conditions[] = 'SeoRoute.plugin = "' . Sanitize::escape($model->data['seo_plugin_main']['plugin']). '"';
    } else {
      $conditions[] = 'SeoRoute.plugin IS NULL';
    }

    if(isset($model->data['seo_plugin_main']['controller'])) {
      $data['controller'] = $model->data['seo_plugin_main']['controller'];
      $conditions[] = 'SeoRoute.controller = "' . Sanitize::escape($model->data['seo_plugin_main']['controller']). '"';
    } else {
      $conditions[] = 'SeoRoute.controller IS NULL';
    }

    if(isset($model->data['seo_plugin_main']['action'])) {
      $data['action'] = $model->data['seo_plugin_main']['action'];
      $conditions[] = 'SeoRoute.action = "' . Sanitize::escape($model->data['seo_plugin_main']['action']). '"';
    } else {
      $conditions[] = 'SeoRoute.action IS NULL';
    }

    if(isset($model->data['seo_plugin_slug']['url'])) {
      $model->data['seo_plugin_slug']['url'] = trim($model->data['seo_plugin_slug']['url']);
      if(!empty($model->data['seo_plugin_slug']['url'])) {
        $data['slug'] = $model->data['seo_plugin_slug']['prefix'].$model->data['seo_plugin_slug']['url'];
      } else {
        $data['slug'] = null;
      }
    }

    if(isset($model->data['seo_plugin_params'])) {
      $params = array();
      foreach($model->data['seo_plugin_params'] as $key => $val) {
        if($key=='id') {
          $val = $model->getID();
        }
        $params[] = $val;
      }

      if(count($params) > 0) {
        $data['params'] = json_encode($params);
        $conditions[] = 'SeoRoute.params = "' . Sanitize::escape($data['params']). '"';
      } else {
        $conditions[] = 'SeoRoute.params IS NULL';
      }
    }

    if(isset($model->data['seo_plugin_data'])) {
      $metaData = array();
      foreach($model->data['seo_plugin_data'] as $key => $val) {
        $metaData[$key] = $val;
      }
      $data['meta_data'] = json_encode($metaData);
    }

    $duplicateRoute = false;
    if(!empty($conditions)) {
      if(isset($data['slug']) && !is_null($data['slug'])) {
        $route = $seoRoute->find('first', array('conditions' => array('SeoRoute.slug = "'.Sanitize::escape($data['slug']).'"')));
        if(!empty($route)) {
          $duplicateRoute = true;
        }
      }
    }

    if(!$duplicateRoute) {
      $route = $seoRoute->find('first', array('conditions' => $conditions));
      if(!empty($route)) {
        $data['id'] = $route['SeoRoute']['id'];
      }
      $seoRoute->save($data);
    }
  }

  public function beforeDelete(\Model $model, $cascade = true) {
    parent::beforeDelete($model);
    return true;
  }

  public function afterDelete(\Model $model) {
    parent::afterDelete($model);
  }

  public function onError(\Model $model, $error) {
    parent::onError($model, $error);
  }

}

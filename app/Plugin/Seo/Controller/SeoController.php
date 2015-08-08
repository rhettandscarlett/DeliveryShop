<?php

App::uses('SeoRoute', 'Seo.Model');

class SeoController extends AppController {

  public function edit($params) {

    $params = json_decode(base64_decode($params));

    $this->set('title', __('SEO-Update Data'));

    $seoRoute = new SeoRoute();

    $params->controller = preg_replace('/Controller$/', '', $params->controller);

    $conditions = array();
    if(empty($params->plugin)) {
      $conditions[] = 'SeoRoute.plugin IS NULL';
    } else {
      $conditions['SeoRoute.plugin'] = $params->plugin;
    }

    $conditions['SeoRoute.controller'] =  $params->controller;
    $conditions['SeoRoute.action'] =  $params->action;
    $conditions['SeoRoute.params'] = json_encode($params->params);

    $dataSeo = $seoRoute->find('first',array('conditions' => $conditions));

    if(empty($this->request->data)) {
      if(!empty($dataSeo)) {
        $dataSeoMeta = json_decode($dataSeo['SeoRoute']['meta_data']);
        if(isset($dataSeoMeta->title)) {
          $this->request->data['SeoRoute']['title'] = $dataSeoMeta->title;
        }
        if(isset($dataSeoMeta->keywords)) {
          $this->request->data['SeoRoute']['keywords'] = $dataSeoMeta->keywords;
        }
        if(isset($dataSeoMeta->description)) {
          $this->request->data['SeoRoute']['description'] = $dataSeoMeta->description;
        }
        if(isset($dataSeo['SeoRoute']['slug'])) {
          $this->request->data['SeoRoute']['slug'] = $dataSeo['SeoRoute']['slug'];
        }
      }
    } else {
      $data = array();
      if(empty($params->plugin)) {
        $data['plugin'] = null;
      } else {
        $data['plugin'] = $params->plugin;
      }
      $data['controller'] = $params->controller;
      $data['action'] = $params->action;
      $data['params'] = json_encode($params->params);

      if(!empty($this->request->data['SeoRoute']['slug'])) {
        $data['slug'] = preg_replace('/\s+/','_',$this->request->data['SeoRoute']['slug']);
      } else {
        $data['slug'] = null;
      }

      $metas = array();
      $metas['title'] = $this->request->data['SeoRoute']['title'];
      $metas['keywords'] = $this->request->data['SeoRoute']['keywords'];
      $metas['description'] = $this->request->data['SeoRoute']['description'];

      $data['meta_data'] = json_encode($metas);

      if(isset($dataSeo['SeoRoute']['id']) && $dataSeo['SeoRoute']['id'] > 0) {
        $data['id'] = $dataSeo['SeoRoute']['id'];
      }

      if(isset($this->request->data['save'])) {
        $seoRoute->save($data);
        if(!empty($data['slug'])) {
          $this->jsResponse['location'] = $data['slug'];
        }
      } elseif(isset($data['id'])) {
        $seoRoute->delete($data['id'], false, false);
        $routeArray = array('plugin' => $data['plugin'], 'controller' => $data['controller'], 'action' => $data['action']);
        $routeArray = array_merge($routeArray, $params->params);
        $this->jsResponse['location'] = Router::url($routeArray);
      }

      $this->jsResponse['script'][] = 'sfDialogModelClose()';
    }



  }


}

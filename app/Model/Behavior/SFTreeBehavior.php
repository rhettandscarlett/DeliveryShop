<?php

/*
 * A tree has structure
 *  - parent_id
 *  - order
 */


App::uses('ModelBehavior', 'Model');

class SFTreeBehavior extends ModelBehavior {

  public $tree;
  var $primaryField = 'id';

  function setup(Model $model, $config = array()) {
  }

  /*
   * Return an ordered tree
   */

  public function getTreeList($model, $id=0) {
    $list = $this->flatTreeSort($this->getListData($model), $id);
    $size = count($list);
    for($i=0; $i < $size-1; $i++) {
      if($list[$i+1]->parent_id == $list[$i]->id) {
        $list[$i]->end = 0;
      } else {
        $list[$i]->end = 1;
      }
    }
    if($size>1){
      if(empty($list[$size-1]->parent_id)) {
        $list[$size-1]->end = 1;
      } else {
        $list[$size-1]->end = 0;
      }
    }
    return $list;
  }

  /*
   * Return the path to current $id */
  public function getPath($model, $id, $returnArr = false) {
    $className = get_class($model);
    $data = $model->find('all', array('conditions' => array($className.'.deleted_time is null'), 'fields' => array('id', 'name', 'parent_id')));
    $data = Hash::combine($data, '{n}.'. $className . '.id', '{n}.'.$className);
    if ($returnArr) {
      if (empty($id)) return array(0=>array(0=>null,1=>null));
      $refer = array();
      $this->_getPathByArray($data,$id,$refer);
      return array_reverse($refer);
    } else {
      if (empty($id)) return '/';
      return $this->_getPath($data, $id);
    }
  }

  private function _getPath($data, $id) {
    if (empty($data[$id]['parent_id'])) return '/'.$data[$id]['name'];
    return $this->_getPath($data , $data[$id]['parent_id']) . '/' . $data[$id]['name'];
  }

  private function _getPathByArray($data, $id, &$refer) {
    if (empty($data[$id]['parent_id'])) {
      $refer[] = array(0 => $data[$id]['id'], 1 => $data[$id]['name']);
      return;
    }
    $refer[] = array(0 => $data[$id]['id'], 1 => $data[$id]['name']);
    $this->_getPathByArray($data , $data[$id]['parent_id'], $refer);
  }


  /*
   * Recursive function
   * Build a tree from a list
   */

  private function flatTreeSort($data, $parentID = 0, &$result = array(), &$depth = 0, &$path='/') {
    foreach ($data as $key => $value) {
      if ($value->parent_id == $parentID) {
        $value->depth = $depth;
        $value->path = $path;
        array_push($result, $value);
        unset($data[$key]);
        $oldParent = $parentID;
        $parentID = $value->{$this->primaryField};
        $depth++;
        $path .= $parentID.'/';
        $this->flatTreeSort($data, $parentID, $result, $depth, $path);
        $parentID = $oldParent;
        $depth--;
        $path = @substr($path,0,strrpos($path,'/',-2)+1);
      }
    }
    return $result;
  }

  public function getTreeListWithPath($model) {
    $data = $this->flatTreeSort($this->getListData($model));
    $index = array();
    foreach ($data as $value) {
      $index[$value->{$this->primaryField}] = $value;
    }
    foreach ($data as $key => $value) {
      $path = '/';
      $i = 0;
      $tmp = $value;
      while (1) {
        if ($index[$tmp->{$this->primaryField}]->parent_id == null || !isset($index[$tmp->{$this->primaryField}])) {
          $data[$key]->path = $path;
          break;
        } else {
          $path = '/' . $tmp->parent_id . $path;
          $tmp = $index[$tmp->parent_id];
        }
        $i++;
        if ($i > 10)
          break;
      }
    }
    return $data;
  }

  /*
   * Return a tree that does not include $id and his children
   */

  public function getTreeReferenceData($model, $id = 0) {
    $options = array();
    $data = $this->flatTreeSort($this->getListData($model));
    $depth = -1;
    foreach ($data as $value) {
      if ($value->{$this->primaryField} == $id) {
        $depth = $value->depth;
        continue;
      }
      if ($depth != -1) {
        if ($value->depth > $depth) {
          continue;
        } else {
          $depth = -1;
        }
      }
      $options[$value->{$this->primaryField}] = sfConvertName2TreeField($value->depth) . $value->name;
    }
    return $options;
  }

  private function getListData($model) {

    if (method_exists($model, 'getList')) {
      return $model->getList();
    }
    else {
      $schema = $model->schema();
      $class = get_class($model);
      $query = array();
      $query['conditions'] = array($class . '.deleted_time IS NULL');
      $query['order'] = array("{$class}.parent_id ASC", "{$class}.order ASC");

      $data = $model->find('all', $query);
      $list = array();
      foreach ($data as $obj) {
        $other = $obj;
        unset($other[$class]);
        $list[$obj[$class][$this->primaryField]] = arrayToObject($obj[$class]);
        $list[$obj[$class][$this->primaryField]]->OtherRelatedData = $other;
      }
      return $list;
    }
  }

}

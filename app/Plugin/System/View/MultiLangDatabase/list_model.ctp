<?php 
$numCol = 3;
?>
<form method="POST" action="<?php echo Router::url(array('plugin' => 'System', 'controller' => 'MultiLangDatabase', 'action' => 'generateTable'), true)?>">
  <h3>System</h3>
  <div class="well form-horizontal page-body posts form">
  <?php 
  foreach($appModels as $appModelName => $appModelAttrs){
    $attrDatas = $this->SystemDatabase->arrayChunk($appModelAttrs, $numCol);

    echo  $this->element(
            'MultiLangDatabase/_item_model', 
            array(
              'attrDatas' => $attrDatas, 
              'modelName' => $appModelName,
              'pluginName' => ''
            )
          );
  }
  ?>
  </div>

  <h3>Plugins</h3>
  <div class="well form-horizontal page-body posts form">
  <?php 
  if($pluginModels){
    foreach($pluginModels as $pluginName => $pluginModel){
      if($pluginModel){
        foreach($pluginModel as $pluginModelName => $pluginModelAttrs){
          $pluginAttrDatas = $this->SystemDatabase->arrayChunk($pluginModelAttrs, $numCol);
          echo  $this->element(
                  'MultiLangDatabase/_item_model', 
                  array(
                    'attrDatas' => $pluginAttrDatas, 
                    'modelName' => $pluginModelName,
                    'pluginName' => $pluginName
                  )
                );
        }
      }
    }
  }
  ?>
  </div>
  <input type="submit" value="Generate" />
</form>

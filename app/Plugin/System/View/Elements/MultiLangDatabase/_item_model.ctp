<div class="form-group">
  <div class="col col-md-3">
    <?php 
      echo $pluginName ? $pluginName.'.'.$modelName : $modelName;
    ?>
  </div>
  <div class="col col-md-9">
    <div class="row">
      <?php if($attrDatas[0]):?>
      <?php foreach($attrDatas as $attrData):?>
      <div class="col col-md-4">
        <?php foreach($attrData as $attr):
          $cbName = 'data['.$modelName.']['.$attr.']';
          ?>
        <div>
          <input name="<?php echo $cbName ?>" id="<?php echo $cbName ?>" <?php if($attr=='id') echo 'disabled'?>  type="checkbox" />
          <label for="<?php echo $cbName ?>"><?php echo $attr?></label>
        </div>
        <?php endforeach;?>
      </div>
      <?php endforeach;?>
      <?php else:?>
      <div class="col col-md-4">No Attribute</div>
      <?php endif;?>
    </div>
  </div>
  <input type="hidden" name="data[<?php echo $modelName?>][plugin]" value="<?php echo $pluginName?>" />
</div>
<br>
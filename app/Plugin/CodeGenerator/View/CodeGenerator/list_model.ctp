<h3>Code Generator</h3>
<form method="POST" action="<?php echo Router::url(array('plugin' => 'CodeGenerator', 'controller' => 'CodeGenerator', 'action' => 'generate'), true)?>">
  <h4>Select Theme</h4>
  <?php foreach($themes as $theme):?>
  <input type="radio" name="theme" value="<?php echo $theme?>"><?php echo $theme?> &nbsp;&nbsp;
  <?php endforeach;?>
  <table class="table">
    <tr>
      <th>No.</th>
      <th>Table</th>
      <th><input id="cbModel" type="checkbox"/> Model</th>
      <th><input id="cbController" type="checkbox" /> Controller</th>
      <th><input id="cbView" type="checkbox" /> View</th>
      <th><input id="cbOverwrite" type="checkbox"/> Overwrite</th>
      <th>Plugin</th>
      <th>Status</th>
    </tr>
    <?php foreach($tables as $key => $table):?>
    <tr <?php echo ($key % 2 ==0) ? 'class="success"' : ''?>>
        <td><?php echo ($key+1)?></td>
        <td><?php echo $table?></td>
        <td><input class="_model" name="data[<?php echo $table?>][model]" type="checkbox"/></td>
        <td><input class="_controller" name="data[<?php echo $table?>][controller]" type="checkbox" /></td>
        <td><input class="_view" name="data[<?php echo $table?>][view]" type="checkbox"  /></td>
        <td><input class="_overwrite" name="data[<?php echo $table?>][overwrite]" type="checkbox" /></td>
        <td><input name="data[<?php echo $table?>][plugin]" type="text" /></td>
        <td><a target="_blank" href="<?php echo Router::url(array('plugin' => 'CodeGenerator', 'controller' => 'CodeGenerator', 'action' => 'preview', $table), true)?>"><?php echo $fileStatus[$table]['error'] ? 'Conflict' : 'OK' ;?></a></td>
    </tr>
    <?php endforeach;?>
  </table>
  <input type="submit" value="Generate" />
</form>
<script type="text/javascript">
 jQuery(document).ready(function(){
   $("#cbModel").click(function(){
     $("._model").prop('checked', $(this).prop('checked'));
   });
   $("#cbController").click(function(){
     $("._controller").prop('checked', $(this).prop('checked'));
   });
   $("#cbView").click(function(){
     $("._view").prop('checked', $(this).prop('checked'));
   });
   $("#cbOverwrite").click(function(){
     $("._overwrite").prop('checked', $(this).prop('checked'));
   });
 });
 </script>
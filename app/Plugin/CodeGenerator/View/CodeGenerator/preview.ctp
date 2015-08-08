<h3>View existed files</h3>
<table class="table">
  <tr>
    <th>No.</th>
    <th>Type</th>
    <th>File</th>
  </tr>
  <?php 
  $i=0;
  foreach($fileList as $type => $files):
    if($files):
      foreach($files as $file):
    ?>
      <tr <?php echo ($i % 2 ==0) ? 'class="success"' : ''?>>
        <td><?php echo ($i+1)?></td>
        <td><?php echo ucfirst($type)?></td>
        <td><?php echo $file?></td>
      </tr>
  <?php 
      $i++;
      endforeach;
    endif;
  endforeach;
  ?>
</table>
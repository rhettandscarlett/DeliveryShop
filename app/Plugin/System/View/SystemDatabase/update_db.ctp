<h2>Database Tools</h2>
<h3>Backup Database</h3>
<?php
//echo $this->Html->link(__('Backup'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBExecute', $script)), array('class' => 'btn btn-default btn-xs btn-edit'));
?>

<h3>Update Database</h3>
<h4>System</h4>
<table class="table">
  <tr>
    <th>Version</th>
    <th>Start of Update</th>
    <th>End of Update</th>
    <th>Action</th>
  </tr>
  <?php
  $update = true;
  foreach ($sqlSystem as $script):
    ?>
    <tr>
      <td><?php echo $script ?></td>
      <td>
          <?php echo isset($updatedList[0][$script]) ? $updatedList[0][$script]['start_updated_time']: ""; ?>
      </td>
      <td>
          <?php echo isset($updatedList[0][$script]) ? $updatedList[0][$script]['end_updated_time'] : ""; ?>
      </td>
      <td>
        <?php
        if (!isset($updatedList[0][$script])) {
          if ($update) {
            echo $this->Html->link(__('Update'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBExecute', $script)), array('class' => 'btn btn-default btn-xs btn-edit'));
            echo $this->Html->link(__('Revert'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'revert', $script)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Revert to lastest change'));
            echo $this->Html->link(__('Preview'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'preview', $script)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Preview execute update script'));
            $update = false;
          } else {
            echo __('Not run');
          }
        } else {
          if ($updatedList[0][$script]['status'] == SYSTEM_DB_STATUS_RUNNING) {
            echo __('Running');
            $update = false;
          } else {
            if ($updatedList[0][$script]['status'] == SYSTEM_DB_STATUS_FAILR) {
              echo $this->Html->link(__('Fail - Re update'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBExecute', $script)), array('class' => 'btn btn-default btn-xs btn-edit'));
              echo $this->Html->link(__('Revert'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'revert', $script)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Revert to lastest change'));
              echo $this->Html->link(__('Preview'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'preview', $script)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Preview execute update script', 'target' => '_blank'));
              echo ' ';
              $update = false;
            }
            echo $this->Html->link(__('View Log'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBView', $updatedList[0][$script]['id'], 'log')), array('class' => 'btn btn-default btn-xs btn-edit', 'target' => '_blank'));
          }
        }
        ?>
      </td>
    </tr>
    <?php
  endforeach;
  ?>
</table>
<h4>Plugins</h4>
<table class="table">
  <tr>
    <th>Version</th>
    <th>Start of Update</th>
    <th>End of Update</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($sqlPlugins as $plugin => $sqlScript):
    $update = true;
    if (count($sqlScript) > 0):
      ?>
      <tr class="success">
        <th colspan="4"><?= $plugin ?></th>
      </tr>
      <?php
    endif;
    foreach ($sqlScript as $script):
      ?>
      <tr>
        <td><?= $script ?></td>
        <td>
          <?php if (isset($updatedList[$plugin][$script])): ?>
            <?= $updatedList[$plugin][$script]['start_updated_time'] ?>
          <?php endif; ?>
        </td>
        <td>
          <?php if (isset($updatedList[$plugin][$script])): ?>
            <?= $updatedList[$plugin][$script]['end_updated_time'] ?>
          <?php endif; ?>
        </td>
        <td>
          <?php
          if (!isset($updatedList[$plugin][$script])) {
            if ($update) {
              echo $this->Html->link(__('Update'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBExecute', $script, $plugin)), array('class' => 'btn btn-default btn-xs btn-edit'));
              echo $this->Html->link(__('Revert'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'revert', $script, $plugin)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Revert to lastest change'));
              echo $this->Html->link(__('Preview'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'preview', $script, $plugin)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Preview execute update script', 'target' => '_blank'));
              $update = false;
            } else {
              echo __('Not run');
            }
          } else {
            if ($updatedList[$plugin][$script]['status'] == SYSTEM_DB_STATUS_RUNNING) {
              echo __('Running');
              $update = false;
            } else {
              if ($updatedList[$plugin][$script]['status'] == SYSTEM_DB_STATUS_FAILR) {
                echo $this->Html->link(__('Fail - Re update'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBExecute', $script, $plugin)), array('class' => 'btn btn-default btn-xs btn-edit'));
                echo $this->Html->link(__('Revert'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'revert', $script, $plugin)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Revert to lastest change'));
              echo $this->Html->link(__('Preview'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'preview', $script, $plugin)), array('class' => 'btn btn-default btn-xs btn-edit', 'title'=>'Preview execute update script', 'target' => '_blank'));
                echo ' ';
                $update = false;
              }
              echo $this->Html->link(__('View Log'), Router::url(array('plugin'=>'System', 'controller' => 'SystemDatabase', 'action' => 'updateDBView', $updatedList[$plugin][$script]['id'], 'log')), array('class' => 'btn btn-default btn-xs btn-edit', 'target' => '_blank'));
            }
          }
          ?>
        </td>
      </tr>
      <?php
    endforeach;
  endforeach;
  ?>
</table>
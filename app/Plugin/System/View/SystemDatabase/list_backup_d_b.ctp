<h3>
  <?= __('System Snapshot List') ?>
</h3>
<hr>

<p><?= $this->Html->link(__('Create a snapshot'), Router::url(array('plugin' => 'System', 'controller' => 'SystemDatabase', 'action' => 'addBackupDB')), array('class' => 'btn btn-primary')) ?></p>

<div class='table-responsive'>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
    <tr>
      <th><?= __('Date') ?></th>
      <th class='actions'><?php echo __('Actions'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($dataList as $file => $name):
      ?>
      <tr>
        <td><?= $name ?></td>
        <td class='actions'>
          <?= $this->Form->postLink(__('Revert'), Router::url(array('plugin' => 'System', 'controller' => 'SystemDatabase', 'action' => 'rollbackBackupDB', $file)), array('class' => 'btn btn-default btn-xs btn-delete'), __('NOTE: PLEASE BACKUP BEFORE UPDATE. Are you sure you want to revert the snapshot at %s?', $name)) ?>
          <?= $this->Form->postLink(__('Delete'), Router::url(array('plugin' => 'System', 'controller' => 'SystemDatabase', 'action' => 'deleteBackupDB', $file)), array('class' => 'btn btn-default btn-xs btn-delete'), __('Are you sure you want to delete the snapshot at %s?', $name)) ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<h3>
  <?= __('RoleAccess list') ?>
</h3>
<hr>

<p><?= $this->Html->link(__('Add'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'edit')), array('class' => 'btn btn-primary')) ?></p>

<div class='table-responsive'>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th>ID</th>
        <th><?= __('Role') ?></th>
        <th><?= __('Account') ?></th>

        <th class='actions'><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($dataList as $data):
        ?>
        <tr>
          <td><?= $data['UserRoleAccess']['id'] ?></td>
          <td><?= $data['UserRole']['name'] ?></td>
          <td><?= $data['UserAccount']['name'] ?></td>


          <td class='actions'>
            <?= $this->Html->link(__('View'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'view')).'/'.$data['UserRoleAccess']['id'], array('class' => 'btn btn-default btn-xs btn-view')) ?>
            <?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'edit')).'/'.$data['UserRoleAccess']['id'], array('class' => 'btn btn-default btn-xs btn-edit')) ?>
            <?= $this->Form->postLink(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'delete')).'/'.$data['UserRoleAccess']['id'], array('class' => 'btn btn-default btn-xs btn-delete'), __('Are you sure you want to delete #%s?', $data['UserRoleAccess']['id'])) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    
    <?php if ($this->Paginator->param('pageCount') > 1): ?>
      <tfoot>
        <tr>
          <td colspan='4'>
            <?php echo $this->Paginator->first('<<'); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->last('>>'); ?>
          </td>
        </tr>
      </tfoot>
    <? endif; ?>

  </table>
</div>
              
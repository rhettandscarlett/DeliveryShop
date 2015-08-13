<h3>
  <?= __('Admin list') ?>
</h3>
<hr>

<p><?= $this->Html->link(__('Add'), Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'edit')), array('class' => 'btn btn-primary')) ?></p>

<div class='table-responsive'>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th>ID</th>
        <th><?= __('Name') ?></th>
        <th><?= __('Email') ?></th>
        <th><?= __('Status') ?></th>
        <th><?= __('Created Time') ?></th>
        <th><?= __('Updated Time') ?></th>

        <th class='actions'><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $adminStatus = Configure::read('User.AdminStatus');
      foreach ($dataList as $data):
        ?>
        <tr>
          <td><?= $data['UserAdmin']['id'] ?></td>
          <td><?= $data['UserAdmin']['name'] ?></td>
          <td><?= $data['UserAdmin']['email'] ?></td>
          <td><?= $adminStatus[$data['UserAdmin']['status']] ?></td>
          <td><?= $data['UserAdmin']['created_time'] ?></td>
          <td><?= $data['UserAdmin']['updated_time'] ?></td>

          <td class='actions'>
            <?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'edit')) . '/' . $data['UserAdmin']['id'], array('class' => 'btn btn-default btn-xs btn-edit')) ?>
            <?= $this->Form->postLink(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'delete')) . '/' . $data['UserAdmin']['id'], array('class' => 'btn btn-default btn-xs btn-delete'), __('Are you sure you want to delete #%s?', $data['UserAdmin']['id'])) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($this->Paginator->param('pageCount') > 1): ?>
      <tfoot>
        <tr>
          <td colspan='10'>
            <?php echo $this->Paginator->first('<<'); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->last('>>'); ?>
          </td>
        </tr>
      </tfoot>
    <? endif; ?>

  </table>
</div>

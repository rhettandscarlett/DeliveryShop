<h3>
  <?= __('Account list') ?>
</h3>
<hr>

<p><?= $this->Html->link(__('Add'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'edit')), array('class' => 'btn btn-primary')) ?></p>

<div class='table-responsive'>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th>ID</th>
        <th><?= __('User') ?></th>
        <th><?= __('Password') ?></th>
        <th><?= __('Password Hint') ?></th>
        <th><?= __('Reset Token Password') ?></th>
        <th><?= __('Reset Token Time') ?></th>
        <th><?= __('Last Login') ?></th>
        <th><?= __('Number Attempt') ?></th>
        <th><?= __('Status') ?></th>
        <th><?= __('Oauth Uid') ?></th>
        <th><?= __('Oauth Provider') ?></th>
        <th><?= __('Oauth Data') ?></th>

        <th class='actions'><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($dataList as $data):
        ?>
        <tr>
          <td><?= $data['UserAccount']['id'] ?></td>
          <td><?= $data['User']['name'] ?></td>
          <td><?= $data['UserAccount']['password'] ?></td>
          <td><?= $data['UserAccount']['password_hint'] ?></td>
          <td><?= $data['UserAccount']['reset_token_password'] ?></td>
          <td><?= $data['UserAccount']['reset_token_time'] ?></td>
          <td><?= $data['UserAccount']['last_login'] ?></td>
          <td><?= $data['UserAccount']['number_attempt'] ?></td>
          <td><?= $data['UserAccount']['status'] ?></td>
          <td><?= $data['UserAccount']['oauth_uid'] ?></td>
          <td><?= $data['UserAccount']['oauth_provider'] ?></td>
          <td><?= $data['UserAccount']['oauth_data'] ?></td>


          <td class='actions'>
            <?= $this->Html->link(__('View'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'view')).'/'.$data['UserAccount']['id'], array('class' => 'btn btn-default btn-xs btn-view')) ?>
            <?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'edit')).'/'.$data['UserAccount']['id'], array('class' => 'btn btn-default btn-xs btn-edit')) ?>
            <?= $this->Form->postLink(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'delete')).'/'.$data['UserAccount']['id'], array('class' => 'btn btn-default btn-xs btn-delete'), __('Are you sure you want to delete #%s?', $data['UserAccount']['id'])) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    
    <?php if ($this->Paginator->param('pageCount') > 1): ?>
      <tfoot>
        <tr>
          <td colspan='13'>
            <?php echo $this->Paginator->first('<<'); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->last('>>'); ?>
          </td>
        </tr>
      </tfoot>
    <? endif; ?>

  </table>
</div>
              
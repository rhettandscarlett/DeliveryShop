<h3>
  <?= __('RoleRight' ) ?> #<?= $data['UserRoleRight']['id'] ?>
</h3>

<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('Role') ?></code>: <?= $data['UserRole']['name'] ?></p>
    <p><code><?= __('Plugin') ?></code>: <?= $data['UserRoleRight']['plugin'] ?></p>
    <p><code><?= __('Controller') ?></code>: <?= $data['UserRoleRight']['controller'] ?></p>
    <p><code><?= __('Action') ?></code>: <?= $data['UserRoleRight']['action'] ?></p>
    <p><code><?= __('Is Owner') ?></code>: <?= $data['UserRoleRight']['is_owner'] ?></p>
    <p><code><?= __('Description') ?></code>: <?= $data['UserRoleRight']['description'] ?></p>
  </div>
 </div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleRight', 'action' => 'search')).'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleRight', 'action' => 'edit')).'/'.$data['UserRoleRight']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleRight', 'action' => 'delete')).'/'.$data['UserRoleRight']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      
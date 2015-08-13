<h3>
  <?= __('RoleAccess' ) ?> #<?= $data['UserRoleAccess']['id'] ?>
</h3>

<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('Role') ?></code>: <?= $data['UserRole']['name'] ?></p>
    <p><code><?= __('Account') ?></code>: <?= $data['UserAccount']['name'] ?></p>
  </div>
 </div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'search')).'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'edit')).'/'.$data['UserRoleAccess']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserRoleAccess', 'action' => 'delete')).'/'.$data['UserRoleAccess']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      
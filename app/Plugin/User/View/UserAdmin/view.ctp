<h3>
  <?= __('Admin') ?> #<?= $data['UserAdmin']['id'] ?>
</h3>
<?php
$adminStatus = Configure::read('User.AdminStatus');
?>
<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('Name') ?></code>: <?= $data['UserAdmin']['name'] ?></p>
    <p><code><?= __('Email') ?></code>: <?= $data['UserAdmin']['email'] ?></p>
    <p><code><?= __('Status') ?></code>: <?= $adminStatus[$data['UserAdmin']['status']] ?></p>
    <p><code><?= __('Created Time') ?></code>: <?= $data['UserAdmin']['created_time'] ?></p>
    <p><code><?= __('Updated Time') ?></code>: <?= $data['UserAdmin']['updated_time'] ?></p>
  </div>
</div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'search')) . '/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'edit')) . '/' . $data['UserAdmin']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserAdmin', 'action' => 'edit')) . '/' . $data['UserAdmin']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>

<h3>
  <?= __('Role' ) ?> #<?= $data['UserRole']['id'] ?>
</h3>

<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('Name') ?></code>: <?= $data['UserRole']['name'] ?></p>
    <p><code><?= __('Description') ?></code>: <?= $data['UserRole']['description'] ?></p>
  </div>
 </div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'search')).'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'edit')).'/'.$data['UserRole']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserRole', 'action' => 'delete')).'/'.$data['UserRole']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      
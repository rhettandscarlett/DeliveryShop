<h3>
  <?= __('DataAccess' ) ?> #<?= $data['UserDataAccess']['id'] ?>
</h3>

<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('User') ?></code>: <?= $data['User']['name'] ?></p>
    <p><code><?= __('Model') ?></code>: <?= $data['UserDataAccess']['model'] ?></p>
    <p><code><?= __('Type') ?></code>: <?= $data['UserDataAccess']['type'] ?></p>
  </div>
 </div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'search')).'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'edit')).'/'.$data['UserDataAccess']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserDataAccess', 'action' => 'delete')).'/'.$data['UserDataAccess']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      
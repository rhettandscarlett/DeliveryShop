<h3>
  <?= __('Account' ) ?> #<?= $data['UserAccount']['id'] ?>
</h3>

<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('User') ?></code>: <?= $data['User']['name'] ?></p>
    <p><code><?= __('Password') ?></code>: <?= $data['UserAccount']['password'] ?></p>
    <p><code><?= __('Password Hint') ?></code>: <?= $data['UserAccount']['password_hint'] ?></p>
    <p><code><?= __('Reset Token Password') ?></code>: <?= $data['UserAccount']['reset_token_password'] ?></p>
    <p><code><?= __('Reset Token Time') ?></code>: <?= $data['UserAccount']['reset_token_time'] ?></p>
    <p><code><?= __('Last Login') ?></code>: <?= $data['UserAccount']['last_login'] ?></p>
    <p><code><?= __('Number Attempt') ?></code>: <?= $data['UserAccount']['number_attempt'] ?></p>
    <p><code><?= __('Status') ?></code>: <?= $data['UserAccount']['status'] ?></p>
    <p><code><?= __('Oauth Uid') ?></code>: <?= $data['UserAccount']['oauth_uid'] ?></p>
    <p><code><?= __('Oauth Provider') ?></code>: <?= $data['UserAccount']['oauth_provider'] ?></p>
    <p><code><?= __('Oauth Data') ?></code>: <?= $data['UserAccount']['oauth_data'] ?></p>
  </div>
 </div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'search')).'/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'edit')).'/'.$data['UserAccount']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Delete'), Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'delete')).'/'.$data['UserAccount']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger')) ?>
      
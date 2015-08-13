<h3>
  <?= __('Email Template') ?> #<?= $data['EmailTemplate']['id'] ?>
</h3>

<hr>
<div class="bs-docs-section">
  <div class="bs-callout bs-callout-danger">
    <p><code><?= __('Key') ?></code>: <?= $data['EmailTemplate']['template_key'] ?></p>
    <p><code><?= __('Type') ?></code>: <?= $data['EmailTemplate']['template_type'] ?></p>
    <p><code><?= __('Subject') ?></code>: <?= $data['EmailTemplate']['subject'] ?></p>
    <p><code><?= __('Body') ?></code>: <?= h($data['EmailTemplate']['body']) ?></p>
  </div>
</div>

<hr />

<?= $this->Html->link(__('Back to list'), Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'search')) . '/', array('class' => 'btn btn-primary')) ?> &nbsp;
<?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'edit')) . '/' . $data['EmailTemplate']['id'], array('class' => 'btn btn-primary')) ?> &nbsp;
<?php if (!$is_inused){
  echo $this->Html->link(__('Delete'), Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'delete')) . '/' . $data['EmailTemplate']['id'], array('confirm' => __('Are you sure you want to delete this?'), 'class' => 'btn btn-danger'));
} ?>

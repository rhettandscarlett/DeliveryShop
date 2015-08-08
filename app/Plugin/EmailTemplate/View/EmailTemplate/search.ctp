<h3>
  <?= __('Email Template list') ?>
</h3>
<hr>

<p><?= $this->Html->link(__('Add'), Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'edit')), array('class' => 'btn btn-primary')) ?></p>

<div class='table-responsive'>
  <table cellpadding='0' cellspacing='0' class='table table-striped table-bordered'>
    <thead>
      <tr>
        <th>ID</th>
        <th><?= __('Key') ?></th>
        <th><?= __('Type') ?></th>
        <th><?= __('Subject') ?></th>
        <th><?= __('Body') ?></th>

        <th class='actions'><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($dataList as $data): ?>
        <tr>
          <td><?= $data['EmailTemplate']['id'] ?></td>
          <td><?= $data['EmailTemplate']['template_key'] ?></td>
          <td><?= $data['EmailTemplate']['template_type'] ?></td>
          <td><?= $this->Text->truncate($data['EmailTemplate']['subject'], 50) ?></td>
          <td><?= h($this->Text->truncate($data['EmailTemplate']['body'], 50)) ?></td>

          <td class='actions'>
            <?= $this->Html->link(__('Edit'), Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'edit')) . '/' . $data['EmailTemplate']['id'], array('class' => 'btn btn-default btn-xs btn-edit')) ?>
            <?php
              if (!$data['EmailTemplate']['extra_field_is_inused']){
                echo $this->Html->link(__('Delete'), Router::url(array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'delete')) . '/' . $data['EmailTemplate']['id'], array('class' => 'btn btn-default btn-xs btn-edit'));
              }
            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($this->Paginator->param('pageCount') > 1): ?>
      <tfoot>
        <tr>
          <td colspan='6'>
            <?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
          </td>
        </tr>
      </tfoot>
    <? endif; ?>

  </table>
</div>

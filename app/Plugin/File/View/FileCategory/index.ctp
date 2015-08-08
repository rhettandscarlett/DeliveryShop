<?php
/**
 * @var $this View
 * @var $dataList
 */
?>

<div class="page-title">
  <div class="row">
    <div class="col col-md-9">
      <h1><i class="fa fa-bars"></i>
        <?php echo __('File Folder Management') ?>
      </h1>
    </div>
    <div class="col-md-3 text-right">
      <?= $this->Html->link('<i class="fa fa-plus"></i> ' . __('Add'), Router::url(array('action' => 'edit')), array(
        'class'  => 'btn btn-inverse btn-medium',
        'escape' => false
      )) ?>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="row">
    <div class="col-md-12">
      <div class="block">
        <div class="block-body">
          <div class='table-responsive'>
            <table cellpadding='0' cellspacing='0' class="table">
              <thead>
              <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Parent Folder') ?></th>
                <th><?= __('Name') ?></th>
                <th class='actions'><?php echo __('Actions'); ?></th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($dataList as $data): ?>
                <tr>
                  <td><?= $data['FileCategory']['id'] ?></td>
                  <td><?= $data['FileCategory']['parent_id'] ?></td>
                  <td><?= $data['FileCategory']['name'] ?></td>
                  <td class='actions'>
                    <?= $this->Html->link('<i class="fa fa-edit"></i>', Router::url(array(
                      'action' => 'edit',
                      $data['FileCategory']['id']
                    )), array(
                      'class'  => 'btn btn-edit btn-sm',
                      'escape' => false
                    )) ?>
                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', Router::url(array(
                        'plugin'     => 'File',
                        'controller' => 'FileCategory',
                        'action'     => 'delete'
                      )) . '/' . $data['FileCategory']['id'], array('escape' => false, 'class' => 'btn btn-delete btn-sm'), __('All files in this folder will be remove also. Are you sure you want to delete #%s?', $data['FileCategory']['name'])) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


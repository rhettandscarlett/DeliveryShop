<?php
$this->HTML->script('libs/jquery.dataTables.min', array('inline' => false));
$this->HTML->script('libs/dataTables.bootstrap', array('inline' => false));
$this->HTML->script('datatables.js', array('inline' => false));
$this->HTML->css('dataTables.bootstrap', array('inline' => false));
?>

<div class="page-title" id="page-title">
  <div class="row">
    <div class="col-md-9">
      <h1><i class="fa fa-bars"></i>
        <?php echo __('Schedule List') ?>
      </h1>
    </div>
    <div class="col-md-3 text-right">
      <?php echo $this->Html->link('<i class="fa fa-plus"></i> ' . __('Add'), Router::url(array('action' => 'edit')).buildQueryString(), array('class' => 'btn btn-inverse', 'escape' => false)); ?>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="row">
    <div class="col-md-12">
      <div class="block">
        <div class="block-body">
          <div class='table-responsive'>
            <table cellpadding='0' cellspacing='0' class='table'>
              <thead>
              <tr>
                <th width="5%"><?php echo __('Id') ?></th>
                <th width="30%"><?php echo __('Name') ?></th>
                <th width="20%"><?php echo __('Activate') ?></th>
                <th width="15%"><?php echo __('Actions'); ?></th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach ($dataList as $data):
                ?>
                <tr>

                  <td><?php echo $data['DeliSchedule']['id']; ?></td>
                  <td><?php echo $data['DeliSchedule']['name']; ?></td>
                  <td>
                    <?php if($data['DeliSchedule']['activated']): ?>
                      <div class="btn btn-success btn-sm"><?= __('Activated')?></div>
                    <?php else: ?>
                      <div class="btn btn-danger btn-sm"><?= __('Deactivated')?></div>
                    <?php endif;?>

                  </td>
                  <td>
                    <?= $this->Html->link('<i class="fa fa-edit"></i>', Router::url(array('action' => 'edit', $data['DeliSchedule']['id'])).buildQueryString(), array('class' => 'btn btn-default btn-sm btn-edit', 'escape' => false)) ?>
                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', Router::url(array('action' => 'delete', $data['DeliSchedule']['id'])).buildQueryString() , array('class' => 'btn btn-default btn-delete btn-sm', 'escape' => false), __('Are you surely want to delete #%s ?', $data['DeliSchedule']['id'])) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php if ($this->Paginator->param('pageCount') > 1): ?>
      <div>
        <?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
      </div>
    <?php endif; ?>
  </div>
</div>
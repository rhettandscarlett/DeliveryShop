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
        <?php echo __('Location List') ?>
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
                <th width="30%"><?php echo __('Schedule') ?></th>
                <th width="30%"><?php echo __('Name') ?></th>
                <th width="20%"><?php echo __('Timezone') ?></th>
                <th width="10%"><?php echo __('Order') ?></th>
                <th width="20%"><?php echo __('Actions'); ?></th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach ($dataList as $data):
                ?>
                <tr>
                  <td><?php echo $data['DeliSchedule']['name']; ?></td>
                  <td><?php echo $data['DeliLocation']['name']; ?></td>
                  <td><?php echo isset($listTimeZone[$data['DeliLocation']['timezone']]) ? $listTimeZone[$data['DeliLocation']['timezone']] : $data['DeliLocation']['timezone'] ; ?></td>
                  <td><?php echo $data['DeliLocation']['order']; ?></td>
                  <td>
                    <?= $this->Html->link('<i class="fa fa-edit"></i>', Router::url(array('action' => 'edit', $data['DeliLocation']['id'])).buildQueryString(), array('class' => 'btn btn-default btn-sm btn-edit', 'escape' => false)) ?>
                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', Router::url(array('action' => 'delete', $data['DeliLocation']['id'])).buildQueryString() , array('class' => 'btn btn-default btn-delete btn-sm', 'escape' => false), __('Are you surely want to delete #%s ?', $data['DeliLocation']['id'])) ?>
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
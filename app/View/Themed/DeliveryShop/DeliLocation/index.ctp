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
  </div>
</div>

<div class="page-body">
  <div class="row">
    <div class="col-md-12">
      <?php foreach ($scheduleList as $scheduleId => $scheduleName): ?>
      <div class="block block-black">
        <div class="block-title">
          <h3><i
              class="fa fa-bars"></i> <?php echo $scheduleName ?>
          </h3>

          <div class="block-tool dropdown navbar-right">
            <?php echo $this->Html->link('<i class="fa fa-plus"></i> ' . __('Add'), Router::url(array('action' => 'edit', $scheduleId)).buildQueryString(), array('class' => 'btn btn-inverse', 'style' => 'padding: 3px 15px;', 'escape' => false)); ?>
            <button style="padding: 3px 15px;" class="btn btn-inverse btn-collapse" data-action="collapse"><i
                class="fa fa-chevron-up"></i></button>
          </div>
        </div>
        <?php if(isset($allDataList[$scheduleId]) && !empty($allDataList[$scheduleId])): ?>
        <div class="block-body">
          <div class='table-responsive'>
            <table cellpadding='0' cellspacing='0' class='table items-table' data-nosearchable=",4" data-nosortable=",4" data-aasorting="[[3,'asc']]">
              <thead>
              <tr>
                <th width="30%"><?php echo __('Name') ?></th>
                <th width="20%"><?php echo __('Timezone') ?></th>
                <th width="10%"><?php echo __('Default for schedule') ?></th>
                <th width="10%"><?php echo __('Order') ?></th>
                <th width="20%"><?php echo __('Actions'); ?></th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach ($allDataList[$scheduleId] as $data):
                ?>
                <tr>
                  <td><?php echo $data['DeliLocation']['name']; ?></td>
                  <td><?php echo isset($listTimeZone[$data['DeliLocation']['timezone']]) ? $listTimeZone[$data['DeliLocation']['timezone']] : $data['DeliLocation']['timezone'] ; ?></td>
                  <td><?php echo ($data['DeliLocation']['is_default']) ? __('Yes') : __('No'); ?></td>
                  <td><?php echo $data['DeliLocation']['order']; ?></td>
                  <td>
                    <?= $this->Html->link('<i class="fa fa-edit"></i>', Router::url(array('action' => 'edit', $scheduleId ,$data['DeliLocation']['id'])).buildQueryString(), array('class' => 'btn btn-default btn-sm btn-edit', 'escape' => false)) ?>
                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', Router::url(array('action' => 'delete', $data['DeliLocation']['id'])).buildQueryString() , array('class' => 'btn btn-default btn-delete btn-sm', 'escape' => false), __('Are you surely want to delete #%s ?', $data['DeliLocation']['id'])) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach ?>
    </div>
    <?php if ($this->Paginator->param('pageCount') > 1): ?>
      <div>
        <?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

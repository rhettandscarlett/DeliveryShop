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
        <?php echo __('Billing List') ?>
      </h1>
    </div>
    <div class="col-md-3 text-right">
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
        <?php if(isset($allBillingList[$scheduleId]) && !empty($allBillingList[$scheduleId])): ?>
          <div class="block-body">
            <div class='table-responsive'>
              <table cellpadding='0' cellspacing='0' class='table items-table' data-nosearchable="0,5" data-nosortable="0,5">
                <thead>
                <tr>
                  <th width="30%"><?php echo __('Schedule') ?></th>
                  <th width="10%"><?php echo __('Bill Code') ?></th>
                  <th width="10%"><?php echo __('Status') ?></th>
                  <th width="20%"><?php echo __('Signed Person Name') ?></th>
                  <th width="10%"><?php echo __('Picked up date(Date/Month/Year)') ?></th>
                  <th width="20%"><?php echo __('Actions'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($allBillingList[$scheduleId] as $data):?>
                  <tr>
                    <td><?php echo $data['DeliSchedule']['name']; ?></td>
                    <td><?php echo $data['DeliBilling']['bill_code']; ?></td>
                    <td><?php echo (isset($transitStatusList[$data['DeliBilling']['status']])) ? $transitStatusList[$data['DeliBilling']['status']] : '' ?></td>
                    <td><?php echo $data['DeliBilling']['bill_name']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($data['DeliBilling']['picked_up_date'])); ?></td>
                    <td>
                      <?= $this->Html->link('<i class="fa fa-edit"></i>', Router::url(array('action' => 'edit', $scheduleId,  $data['DeliBilling']['id'])).buildQueryString(), array('class' => 'btn btn-default btn-sm btn-edit', 'escape' => false)) ?>
                      <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', Router::url(array('action' => 'delete', $data['DeliBilling']['id'])).buildQueryString() , array('class' => 'btn btn-default btn-delete btn-sm', 'escape' => false), __('Are you surely want to delete #%s ?', $data['DeliBilling']['id'])) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
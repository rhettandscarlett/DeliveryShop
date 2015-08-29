<?php $this->HTML->script('tracking', array('inline' => false)); ?>

<div class="resultHolder">
  <?php if (empty($billFound)): ?>
    <h3><?= __('Sorry, we found no result matches. Please contact our customer service if any question. Thank you !') ?></h3>
  <?php else: ?>
    <div>
      <h3><?= __('Result Summary') ?>:</h3>
    </div>
    <?php $open = (count($billFound) > 1) ? false: true ?>
    <?php foreach ($billFound as $bill): ?>
      <?php $lastLocation = end($bill['DeliLocation']);
      $lastPro            = end($lastLocation['DeliDefaultLocationProcedure']); ?>
      <br>
      <div class="well" style="position: relative;min-height: 150px;">
        <h4><?= __('Waybill') ?>: <?= $bill['DeliBilling']['bill_code'] ?></h4>
        <?php if ($bill['DeliBilling']['status'] == DELI_TRANSIT_STATUS_DELIVERED): ?>
          <h4 class="text-danger"><?= __('Signed for by') ?>: <?= $bill['DeliBilling']['bill_name'] ?></h4>
          <h4 class="text-success"><?php echo ($lastPro) ? $lastPro['realtime'] : '' ?></h4>
        <?php endif; ?>
        <div class="" style="position: absolute;right: 30px;top: 25px;">
          <div class="origin"><b><?= __('Origin Service Area') ?></b>
            >> <?= $bill['DeliLocation'][0]['DeliLocation']['name'] ?></div>
          <div class="destination"><b><?= __('Destination Service Area') ?></b>
            >> <?= $lastLocation['DeliLocation']['name'] ?></div>
          <div><b><?= __('Estimate')?></b>: <?= $bill['DeliBilling']['estimate_day'] ?> <?= __('Days')?></div>
          <br>
          <div class="" style="position: relative;width: 300px;">
            <?php if ($bill['DeliBilling']['status'] == DELI_TRANSIT_STATUS_DELIVERED): ?>
              <div class="delivered"
                   style="position: absolute;"><?= $this->Html->image('button_ok.png', array('style' => 'width:30px;height:30px;')) ?>
                &nbsp;<strong style="font-size:1.5em;color: green;margin-top:4px;"><?= __('Delivered') ?></strong></div>
              <?php else: ?>
              <?php $progress = (array_search($bill['DeliBilling']['status'], array_keys($statusList)) + 1) / count($statusList) * 100; ?>
              <div class="processsub">
                <div class="arr" style="width: <?= $progress ?>%;"></div>
              </div>
              <br>
              <div><strong style="font-size:1.5em;color: #A445D4;margin-top:4px;"><?= $statusList[$bill['DeliBilling']['status']] ?></strong></div><br>
            <?php endif; ?>

          </div>
        </div>
      </div>

      <div class="wrappingHolder">
        <div class="toggleDetail">
          <a class="toggleShow <?= $open ? 'hidden': '' ?>" style="cursor: pointer"><?= __('Show Details')?> >></a>
          <a class="toggleHide <?= $open ? '': 'hidden' ?>" style="cursor: pointer"><?= __('Hide Details')?> <<</a>
        </div>
        <div class="page-body detailHolder" style="display: <?= $open ? '': 'none' ?>;">
          <div class="row">
            <div class="col-md-12">
              <div class="block">
                <div class="block-body">
                  <div class='table-responsive'>
                    <?php foreach (array_reverse($bill['DeliLocation']) as $location): ?>
                      <?php if (!empty($location['DeliDefaultLocationProcedure'])): ?>
                        <table cellpadding='0' cellspacing='0' class='table table-bordered'>
                          <thead>
                          <tr>
                            <th width="30%"><?php echo __('Detail') ?></th>
                            <th width="30%"><?php echo __('Date (Day/Month/Date/Year)') ?></th>
                            <th width="30%"><?php echo __('Location') ?></th>
                            <th width="20%"><?php echo __('Local Time') ?></th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php foreach (array_reverse($location['DeliDefaultLocationProcedure']) as $data): ?>
                            <tr>
                              <td><?php echo $data['name']; ?></td>
                              <td><?php echo $data['realtime']; ?></td>
                              <td><?php echo $location['DeliLocation']['name']; ?></td>
                              <td><?php echo $data['time']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                          </tbody>
                        </table>
                      <?php endif; ?>
                    <?php endforeach ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
    <?php endforeach; ?>
  <?php endif; ?>

</div>
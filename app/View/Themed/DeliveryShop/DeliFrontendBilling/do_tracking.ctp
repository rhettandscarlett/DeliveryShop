<style>
  .processsub {
    position: absolute;
    width: 80%;
    margin-top: -11px;
    height: 22px;
  }

  .processsub:before, .processsub:after, .processsub .arr:after, .processsub .arr:before {
    content: '';
    display: block;
  }

  .processsub:before {
    margin-top: 10px;
    height: 2px;
    background: #ccc;
    left: 0;
    right: 10px;
    position: absolute;
  }

  .processsub:after {
    position: absolute;
    right: 0;
    width: 0;
    height: 0;
    border-top: 6px solid transparent;
    border-left: 16px solid #ccc;
    border-bottom: 6px solid transparent;
    margin-top: 4px;
  }

  .processsub .arr {
    width: 2px;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    background: #f5f5f5;
    z-index: 1;
  }

  .processsub .arr:after {
    border-right: 2px solid #A445D4;
    border-top: 2px solid #A445D4;
    position: absolute;
    right: 0;
    transform: rotate(45deg);
    background: #f5f5f5;
    top: 3px;
    width: 15px;
    height: 15px;
  }

  .processsub .arr:before {
    border: 2px solid #A445D4;
    border-right: 0;
    left: 0;
    top: 0;
    height: 100%;
    right: 7px;
    position: absolute;
  }
</style>
<?php $this->HTML->script('tracking', array('inline' => false)); ?>
<div class="jia_ge">
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
                            <th width="30%"><?php echo __('Date (Day/Month/Year)') ?></th>
                            <th width="30%"><?php echo __('Location') ?></th>
                            <th width="20%"><?php echo __('Local Time') ?></th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php foreach (array_reverse($location['DeliDefaultLocationProcedure']) as $data): ?>
                            <?php if ($data['visible'] == true || ($bill['DeliBilling']['status'] == DELI_TRANSIT_STATUS_DELIVERED)): ?>
                              <tr>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo $data['realtime']; ?></td>
                                <td><?php echo $location['DeliLocation']['name']; ?></td>
                                <td><?php echo $data['time']; ?></td>
                              </tr>
                            <?php endif; ?>
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
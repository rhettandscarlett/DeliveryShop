<style>
  .sf-unit-test h3 {
    margin-right: 10px;
  }
  .sf-unit-test h4 {
    margin-right: 6px;
    margin-top: 20px;
  }
</style>
<div class="container">
  <div class="row sf-unit-test">
    <h3 class="pull-left">
      <span class="label label-success">
        Passed <span class="badge"><?= $total['all']['pass'] ?></span>
      </span>
    </h3>

    <h3 class="pull-left">
      <span class="label label-danger">
        Failed <span class="badge"><?= $total['all']['fail'] ?></span>
      </span>
    </h3>

    <h3 class="pull-left">
      <span class="label label-primary">
        Code coverage <span class="badge"><?= unitTestRoundPercent($total['all']['coverage'],$total['all']['coverage-number']) ?>%</span>
      </span>
    </h3>

    <div class="clearfix"></div>
  </div>

  <?php foreach($lists as $plugin => $files): ?>
    <div>
      <div class="row sf-unit-test">
        <h3 class="pull-left"><span class="fa fa-plus-square-o" onclick="unitTestToogleData(this)"></span> <?= $plugin ?></h3>

        <h4 class="pull-left">
        <span class="label label-success">
          Passed <span class="badge"><?= $total['plugin'][$plugin]['pass'] ?></span>
        </span>
        </h4>

        <h4 class="pull-left">
        <span class="label label-danger">
          Failed <span class="badge"><?= $total['plugin'][$plugin]['fail'] ?></span>
        </span>
        </h4>

        <h4 class="pull-left">
        <span class="label label-primary">
          Code coverage <span class="badge"><?= unitTestRoundPercent($total['plugin'][$plugin]['coverage'],$total['plugin'][$plugin]['coverage-number']) ?>%</span>
        </span>
        </h4>

        <div class="clearfix"></div>
      </div>

      <table class="table" style="display: none">
        <?php foreach($files as $section => $listFiles):
        if(in_array($section, array('plugin'))) {
          continue;
        }
        ?>
          <?php foreach($listFiles as $file): ?>
          <tr>
            <td style="width:50%">
              <?= $this->UnitTest->buttonRun($files['plugin'], $section, $file) ?>
              <?= $this->UnitTest->buttonRunDebug($files['plugin'], $section, $file) ?>
              (<?= $section ?>) <?= $file ?>
            </td>
            <td style="width:50%">
              <?php if(!unitTestGetRealPath($files['plugin'], $section, $file)):?>
                <?= $this->UnitTest->buttonAdd($files['plugin'], $section, $file) ?>
              <?php else: ?>
                <?php if(isset($results[$plugin][$section][$file]['pass']) && $results[$plugin][$section][$file]['pass'] > 0):?>
                  <span class="label label-success"><?= $results[$plugin][$section][$file]['pass'] ?> Passed</span>
                <? endif;?>
                <?php if(isset($results[$plugin][$section][$file]['fail']) && $results[$plugin][$section][$file]['fail'] > 0):?>
                  <span class="label label-danger"><?= $results[$plugin][$section][$file]['fail'] ?> Failed</span>
                <? endif;?>
                <?php if(isset($results[$plugin][$section][$file]['coverage'])):?>
                  <span class="label label-primary">Code coverage: <?= $results[$plugin][$section][$file]['coverage'] ?>%</span>
                <? endif;?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </table>
    </div>
  <?php endforeach; ?>

</div>

<script>
  function unitTestRun(object, plugin, section, file) {
    $.ajax({
      url: "/unit-testing/run/?plugin=" + encodeURIComponent(plugin) + '&section=' + encodeURIComponent(section) + '&file=' + encodeURIComponent(file),
      beforeSend: function( xhr ) {
        $(object).html('Running...');
        }
      })
      .done(function( data ) {
        $(object).html('Run');
        $($(object).parent().parent().find('td')[1]).html(unitTestAnalyseResult(data));
      });
  }

  function unitTestRunDebug(plugin, section, file) {
    var targetUrl = "/unit-testing/runDebug/?plugin=" + encodeURIComponent(plugin) + '&section=' + encodeURIComponent(section) + '&file=' + encodeURIComponent(file);
    window.open(targetUrl,'_blank');
  }

  function unitTestAdd(object, plugin, section, file) {
    $.ajax({
      url: "/unit-testing/add/?plugin=" + encodeURIComponent(plugin) + '&section=' + encodeURIComponent(section) + '&file=' + encodeURIComponent(file),
      beforeSend: function( xhr ) {
        $(object).html('Adding...');
      }
    })
      .done(function( data ) {
        $($($(object).parent().parent().find('td')[0]).find('button')[0]).removeAttr('disabled');
        $($($(object).parent().parent().find('td')[0]).find('button')[1]).removeAttr('disabled');
        $($(object).parent().parent().find('td')[1]).html(unitTestAnalyseResult(data));
      });
  }

  function unitTestAnalyseResult(data) {
    var str = '';
    if(data['pass'] > 0) {
      str += '<span class="label label-success">' + data['pass'] + ' Passed</span> ';
    }
    if(data['fail'] > 0) {
      str += '<span class="label label-danger">' + data['fail'] + ' Failed</span> ';
    }
    var coverage = 0;
    if(data['coverage'] != undefined) {
      coverage = data['coverage'];
    }
    str += '<span class="label label-primary">Code coverage: ' + coverage + '%</span> ';
    return str;
  }

  function unitTestToogleData(object) {
    var tableObj = $($(object).parent().parent().parent().find('table')[0]);
    if(tableObj.is(':hidden')) {
      tableObj.show();
      $(object).removeClass('fa-plus-square-o');
      $(object).addClass('fa-minus-square-o');
    } else {
      tableObj.hide();
      $(object).removeClass('fa-minus-square-o');
      $(object).addClass('fa-plus-square-o');
    }

  }

</script>

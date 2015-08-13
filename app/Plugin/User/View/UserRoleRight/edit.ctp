<h3>
  Update Role Permission: <i><?= $role['UserRole']['name'] ?></i>
</h3>

<hr />

<div class="posts form">
  <?php
  echo $this->Form->create('UserRoleRight', array(
      'novalidate' => true,
      'inputDefaults' => array(
          'div' => 'form-group',
          'wrapInput' => false,
          'class' => 'form-control'
      ),
      'class' => 'well'
  ));
  ?>
  <?php
  $script = '';
  foreach ($rights['plugin'] as $plugin => $dataPlugin) {
    if ($dataPlugin['status'] == USER_FUNC_DISABLE) {
      continue;
    }
    ?>
    <h5>
      <?php
      $options = array('class' => '', 'name' => "plugin_all[$plugin]",
          'label' => '', 'type' => 'checkbox', 'div' => 'checkbox-inline',
          'onclick' => 'if($(this).prop("checked")){$("#plugin_all_' . $plugin . '").hide()}else{$("#plugin_all_' . $plugin . '").show()}');
      if (isset($rolesP[$plugin]['id'])) {
        $options['checked'] = 'checked';
        $script .= "\$('#plugin_all_$plugin').hide();\n";
      }
      echo $this->Form->input("plugin_all[$plugin]", $options);
      ?>
      <?= $dataPlugin['name'] ?>
    </h5>
    <div style="margin-left: 40px" id="plugin_all_<?= $plugin ?>">
      <?php
      foreach ($dataPlugin['controller'] as $controller => $dataController) {
        if ($dataController['status'] == USER_FUNC_DISABLE) {
          continue;
        }
        ?>
        <h6>
          <?php
          $options = array('class' => '', 'name' => "plugin_controller_all[$controller]",
              'label' => '', 'type' => 'checkbox', 'div' => 'checkbox-inline',
              'onclick' => 'if($(this).prop("checked")){$("#plugin_controller_all_' . $controller . '").hide()}else{$("#plugin_controller_all_' . $controller . '").show()}');
          if (isset($rolesP[$plugin][$controller]['id'])) {
            $options['checked'] = 'checked';
            $script .= "\$('#plugin_controller_all_$controller').hide();\n";
          }
          echo $this->Form->input("plugin_controller_all[$controller]", $options);
          ?>
          <?= $dataController['name'] ?>
        </h6>
        <div style="margin: -10px 0px 0px 10px" id="plugin_controller_all_<?= $controller ?>">
          <?php
          foreach ($dataController['action'] as $action => $dataAction) {
            if ($dataAction['status'] == USER_FUNC_DISABLE) {
              continue;
            }
            echo "<div>";
            $options = array('class' => '', 'name' => "pluginowner[$plugin][$controller][$action]",
                'label' => 'Owner', 'type' => 'checkbox', 'div' => 'checkbox-inline',
                'onclick' => 'if($(this).prop("checked")){$("[name=\'plugin[' . $plugin . '][' . $controller . '][' . $action . ']\']").prop("checked", true)}');
            if (isset($rolesP[$plugin][$controller][$action]['owner']) && $rolesP[$plugin][$controller][$action]['owner']) {
              $options['checked'] = 'checked';
            }
            echo $this->Form->input("pluginowner[$plugin][$controller][$action]", $options);

            $options = array('class' => '', 'name' => "plugin[$plugin][$controller][$action]",
                'label' => $dataAction['name'], 'type' => 'checkbox', 'div' => 'checkbox-inline',
                'onclick' => 'if(!$(this).prop("checked")){$("[name=\'pluginowner[' . $plugin . '][' . $controller . '][' . $action . ']\']").prop("checked", false)}');
            if (isset($rolesP[$plugin][$controller][$action]['id'])) {
              $options['checked'] = 'checked';
            }
            echo $this->Form->input("plugin[$plugin][$controller][$action]", $options);
            echo "</div>";
          }
          ?>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }

  foreach ($rights['controller'] as $controller => $dataController) {
    if ($dataController['status'] == USER_FUNC_DISABLE) {
      continue;
    }
    ?>
    <h5>
      <?php
      $options = array('class' => '', 'name' => "controller_all[$controller]",
          'label' => '', 'type' => 'checkbox', 'div' => 'checkbox-inline',
          'onclick' => 'if($(this).prop("checked")){$("#controller_all_' . $controller . '").hide()}else{$("#controller_all_' . $controller . '").show()}');
      if (isset($rolesC[$controller]['id'])) {
        $script .= "\$('#controller_all_$controller').hide();\n";
        $options['checked'] = 'checked';
      }
      echo $this->Form->input("controller_all[$controller]", $options);
      ?>
      <?= $dataController['name'] ?>
    </h5>
    <div style="margin: -10px 0px 0px 10px" id="controller_all_<?= $controller ?>">
      <?php
      foreach ($dataController['action'] as $action => $dataAction) {
        if ($dataAction['status'] == USER_FUNC_DISABLE) {
          continue;
        }
        echo "<div>";
        $options = array('class' => '', 'name' => "controllerowner[$controller][$action]",
            'label' => 'Owner', 'type' => 'checkbox', 'div' => 'checkbox-inline',
            'onclick' => 'if($(this).prop("checked")){$("[name=\'controller[' . $controller . '][' . $action . ']\']").prop("checked", true)}');
        if (isset($rolesC[$controller][$action]['owner']) && $rolesC[$controller][$action]['owner']) {
          $options['checked'] = 'checked';
        }
        echo $this->Form->input("controllerowner[$controller][$action]", $options);

        $options = array('class' => '', 'name' => "controller[$controller][$action]",
            'label' => $dataAction['name'], 'type' => 'checkbox', 'div' => 'checkbox-inline',
            'onclick' => 'if(!$(this).prop("checked")){$("[name=\'controllerowner[' . $controller . '][' . $action . ']\']").prop("checked", false)}');
        if (isset($rolesC[$controller][$action]['id'])) {
          $options['checked'] = 'checked';
        }
        echo $this->Form->input("controller[$controller][$action]", $options);
        echo "</div>";
      }
      ?>
    </div>
    <?php
  }
  ?>
  <hr>
  <?php
  echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>
</div>

<script>
<?= $script ?>
</script>
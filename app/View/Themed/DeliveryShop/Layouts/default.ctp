<?php
/**
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2013.
 * @package       View.Layouts
 * @since         SF v 1.0
 * @license
 */
?>
<?php echo $this->Html->docType('html5'); ?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php echo $this->Html->charset(); ?>
    <title><?= $title_for_layout ?></title>

    <meta name="description" content="">
    <meta name="keywords" content="">

    <?php
    echo $this->Html->meta('icon');
    echo $this->fetch('meta');
    echo $this->Html->css(array(
      'bootstrap.min',
      'font-awesome.min',
      'jquery.ui',
      'bootstrap-datetimepicker',
      'File.file',
      'common',
    ));
    echo $this->fetch('css');
    ?>
    <?php
    echo $this->Html->script(array(
      'libs/jquery-1.10.2.min',
      'libs/bootstrap.min',
      'libs/moment',
      'libs/bootstrap-datetimepicker',
    ));
    echo $this->Layout->js();
    echo $this->fetch('script');
    ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">
      <div class="row">
        <?php
        echo $this->element('frontend/header');
        echo $this->element('frontend/menu');
        echo $this->fetch('content');
        echo $this->element('frontend/footer');
        ?>
      </div>
    </div>
    <?php if (Configure::read('debug') > 1): ?>
      <div class="container">
        <div class="well well-sm">
          <p>SQL Debug:</p>
          <small>
            <?php echo $this->element('sql_dump'); ?>
          </small>
        </div>
      </div>
    <?php endif; ?>
    <div class="menu-mask"></div>
  </body>
</html>

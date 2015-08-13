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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->Html->charset(); ?>
    <title><?php echo $title_for_layout; ?></title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->fetch('meta');
    echo $this->Html->css(array(
      'font-gesta',
      'bootstrap.min',
      'jquery.bxslider',
      'font-awesome.min',
      'bootstrap-datetimepicker',
      'backend',
      'jquery.ui',
      'File.file'
    ));
    echo $this->fetch('css');

    echo $this->Html->script(array(
      'libs/jquery-1.10.2.min',
      'libs/jquery-ui.min',
      'libs/bootstrap.min',
      'libs/jquery.bxslider',
      'libs/jquery.tmpl.min',
      'libs/jquery.gritter.min',
      'libs/moment',
      'libs/bootstrap-datetimepicker',
      'common',
      'core',
      'File.file',
      'File.fileuploader',
    ));
    echo $this->Layout->js();
    echo $this->fetch('script');
    ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="skin-black">
    <?php
    if ($this->Positions->count('backend/header') > 0):
      echo $this->Positions->blocks('backend/header');
    endif;
    ?>

    <div id="main-container" class="container">
      <?php echo $this->Positions->blocks('backend/left'); ?>
      <div id="main-content">
        <?php echo $this->Layout->sessionFlash(); ?>
        <?php echo $this->fetch('content'); ?>
      </div>
    </div>
    <?php echo $this->Positions->blocks('backend/footer'); ?>
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
  </body>
</html>

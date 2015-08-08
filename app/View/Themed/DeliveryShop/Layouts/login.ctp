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
    <title>
      <?php echo $title_for_layout; ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->fetch('meta');
    echo $this->Html->css('font-gesta');
    echo $this->Html->css('bootstrap.min');
    echo $this->Html->css('backend');

    echo $this->fetch('css');

    echo $this->Html->script('libs/jquery-1.10.2.min');
    echo $this->Html->script('libs/bootstrap.min');
    echo $this->Html->script('core');
    echo $this->fetch('script');
    ?>
  </head>

  <body class="login-page" style="">
    <div class="login-wrapper">
      <?php
      echo $this->fetch('content');
      ?>
    </div>
  </body>
</html>

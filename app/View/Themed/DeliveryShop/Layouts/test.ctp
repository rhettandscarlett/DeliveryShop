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
      'font-awesome.min',
      'test',
      'color'
    ));
    echo $this->fetch('css');

    echo $this->Html->script(array(
      'libs/jquery-1.10.2.min',
      'libs/bootstrap.min'
    ));
    echo $this->Layout->js();
    echo $this->fetch('script');
    ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="boxed">
    <?php echo $this->fetch('content'); ?>
  </body>
</html>

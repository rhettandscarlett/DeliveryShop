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

    echo $this->Html->css('bootstrap.min');
    echo $this->Html->css('font-awesome.min');
    echo $this->Html->css('itrainer');
    echo $this->Html->css('itrainer-responsive');
    echo $this->Html->css('jquery.gritter');
    echo $this->Html->css('radio-checkbox');

    echo $this->fetch('css');

    echo $this->Html->script('libs/jquery-1.10.2.min');
    echo $this->Html->script('libs/jquery-ui.min');
    echo $this->Html->script('libs/bootstrap.min');
    echo $this->Html->script('libs/jquery.gritter.min');
    echo $this->Html->script('radio-checkbox');
    echo $this->Html->script('common');
    echo $this->Html->script('core');

    echo $this->Layout->js();
    echo $this->fetch('script');
    ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-black dialog">
    <?php echo $this->fetch('content'); ?>
  </body>
</html>
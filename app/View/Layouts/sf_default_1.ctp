<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo $title_for_layout; ?>
    </title>
    <?php
    echo $this->Html->meta('icon');

    //echo $this->Html->css('cake.generic');

    echo $this->fetch('meta');
    //echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
  </head>
  <body>
    <div id="container">

      <?php
      if (isset($blocks['header'])) {
        $this->start('header');
        foreach ($blocks['header']['element'] as $blockElement) {
          echo $this->element($blockElement);
        }
        $this->end();
        echo $this->fetch('header');
      }
      ?>

      <div id="content">
        <?php echo $this->fetch('content'); ?>
      </div>

      <div id="footer">
      </div>

    </div>
  </body>
</html>

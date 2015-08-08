<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if (is_array($message)): ?>
    <?php foreach ($message as $msg): ?>
      <div>
        <?= $msg[0] ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <?php echo $message ?>
  <?php endif; ?>
</div><!-- /.alert alert-error -->
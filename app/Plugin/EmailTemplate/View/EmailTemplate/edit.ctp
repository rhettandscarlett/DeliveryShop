<h3>
  <? if (isset($this->data['EmailTemplate']['id']) && $this->data['EmailTemplate']['id'] > 0): ?>
    <?= __('Edit') ?>: <?= $this->data['EmailTemplate']['template_key'] ?>
  <? else: ?>
    <?= __('Add') ?>
  <? endif; ?>
</h3>

<hr />

<div class="posts form">
  <?php
  echo $this->Form->create('EmailTemplate', array(
    'novalidate' => true,
    'inputDefaults' => array(
      'div' => 'form-group',
      'wrapInput' => false,
      'class' => 'form-control'
    ),
    'class' => 'well',
  ));
  ?>
  <?= $this->Form->input('template_key', array('label' => 'Key')) ?>
  <?= $this->Form->input('template_type', array('label' => 'Type')) ?>
  <?= $this->Form->input('subject', array('label' => 'Subject')) ?>
  <?php
    $body_note = NULL;
    if (!empty($required_token_elements)){
      $body_note = '<i>(*)The body field have to contain some tokens: ' . implode(', ', $required_token_elements) . '. </i>';
    }
  ?>
  <?= $this->Form->input('body', array('label' => 'Body', 'rows' => 5, 'after' => $body_note)) ?>

  <?php
  echo $this->Form->input('id', array('type' => 'hidden'));
  echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-large btn-primary'));
  echo $this->Form->end();
  ?>
</div>

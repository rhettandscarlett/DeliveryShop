<div id="block-list-<?php echo $name; ?>" class="block-list">
	<ul class="todo-list">
	<?php
		foreach ($data as $d) {
			if ($options['link']) {
				echo '<li>';
				echo $this->Html->link($d['title'], array(
					'plugin' => $options['plugin'],
					'controller' => $options['controller'],
					'action' => $options['action']
				));
				echo '</li>';
			} else {
				echo '<li>' . $d['title'] . '</li>';
			}
		}
	?>
	</ul>
</div>
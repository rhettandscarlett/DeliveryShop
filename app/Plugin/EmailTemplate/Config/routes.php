<?php

Router::connect('/email_template/:action/*', array('plugin' => 'EmailTemplate', 'controller' => 'EmailTemplate', 'action' => 'search'));



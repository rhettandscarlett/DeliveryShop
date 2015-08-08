<?php

Router::connect('/multi-language/:action/*', array('plugin' => 'MultiLanguage', 'controller' => 'MultiLanguage'));

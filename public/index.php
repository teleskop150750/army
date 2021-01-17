<?php

use core\App;

require_once dirname(__DIR__) . '/config/init.php';
require_once CONF . '/routes.php';
require_once LIBS . '/functions.php';
new App();

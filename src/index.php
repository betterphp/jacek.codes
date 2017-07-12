<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website;

require __DIR__ . '/../vendor/autoload.php';

define('REQUESTED_CONTROLLER_NAME', ($_GET['controller_name'] ?? 'index'));
define('REQUESTED_ACTION_NAME', ($_GET['action_name'] ?? 'index'));

router::start(REQUESTED_CONTROLLER_NAME, REQUESTED_ACTION_NAME);

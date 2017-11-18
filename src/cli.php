<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/common.php';

use \betterphp\jacek_codes_website\command\command;

if (PHP_SAPI !== 'cli') {
    throw new \Exception('This should only be executed via the command line');
}

$args = getopt('', [
    'command_name:',
]);

if (!isset($args['command_name'])) {
    throw new \Exception('No command specified');
}

$command = command::get_by_name($args['command_name']);
$command->execute();

<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website;

use \betterphp\error_reporting\sentry_reporter;

$error_handler = sentry_reporter::get();
$error_handler->set_report_url(
    'crash.jacekk.co.uk',
    '6348b6562b0b4e19904d52fd8f73841e',
    'f418f6c64b9e46eabe5cf4ae2f5069ea',
    4
);

$error_handler->set_environment(config::ENVIRONMENT);
$error_handler->set_redirect_url('/');

if (config::ENVIRONMENT !== 'development') {
    $error_handler->register_redirect_handler();
    $error_handler->register_reporting_handler();
}

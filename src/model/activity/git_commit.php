<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model\activity;

use \betterphp\jacek_codes_website\config;
use \betterphp\jacek_codes_website\model\activity;

class git_commit extends activity {

    protected static $table_name = 'git_commit';
    protected static $id_field = 'id';
    protected static $fields = [
        'id' => 'int',
    ];

}

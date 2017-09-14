<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model;

class activity extends database_model {

    protected static $fields = [
        'id' => 'int',
        'uuid' => 'string',
        'date' => 'datetime',
        'title' => 'string',
        'description' => 'string',
    ];

}

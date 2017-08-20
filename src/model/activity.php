<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model;

class activity extends model {

    public $uuid;
    public $date;
    public $title;
    public $description;

    /**
     * @param string $uuid A unique ID for this activity
     * @param \DateTime $date The date it happened
     * @param string $title A short title
     * @param string $description A longer description
     */
    public function __construct(string $uuid, \DateTime $date, string $title, string $description) {
        $this->uuid = $uuid;
        $this->date = $date;
        $this->title = $title;
        $this->description = $description;
    }

}

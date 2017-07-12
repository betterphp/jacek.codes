<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

abstract class view {

    /**
     * Renders the output from this view
     *
     * @return void
     */
    abstract public function render(): void;

}

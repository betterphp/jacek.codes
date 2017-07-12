<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\controller;

use \betterphp\jacek_codes_website\view\index as index_view;

class index extends controller {

    /**
     */
    public function __construct() {
        parent::__construct(index_view::class);
    }

    /**
     * @inheritDoc
     */
    public function index(): void {
        $this->view->render();
    }

}

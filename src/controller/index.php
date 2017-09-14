<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\controller;

use \betterphp\jacek_codes_website\view\index as index_view;
use \betterphp\jacek_codes_website\model\activity\git_commit;

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
        $this->view->set_var('events', []);

        $this->view->render();
    }

}

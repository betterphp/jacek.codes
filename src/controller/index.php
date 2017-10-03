<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\controller;

use \betterphp\jacek_codes_website\view\index as index_view;
use \betterphp\jacek_codes_website\model\activity\git_commit;
use \betterphp\jacek_codes_website\database\query_condition_builder;

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
        $git_commits = git_commit::get_list(query_condition_builder::all());

        $this->view->set_var('events', []);

        $this->view->render();
    }

}

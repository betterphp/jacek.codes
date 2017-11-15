<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\controller;

use \betterphp\jacek_codes_website\view\index as index_view;
use \betterphp\jacek_codes_website\model\activity\github_commit;
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
        $activities_filter = function ($qcb) {
            $qcb->order_by('date', false)->limit(10);
        };

        $git_commits = github_commit::get_list($activities_filter);

        print_r($git_commits);

        $this->view->set_var('events', []);

        $this->view->render();
    }

}

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
            $qcb->order_by('date', false)->limit(20);
        };

        $git_commits = github_commit::get_list($activities_filter);

        $this->view->set_var('github_commits', array_map(function (github_commit $commit): array {
            return [
                'project_name' => $commit->project_name,
                'message' => $commit->message,
                'sha' => $commit->sha,
                'date' => $commit->date,
                'files_modified' => $commit->files_modified,
                'lines_added' => $commit->lines_added,
                'lines_deleted' => $commit->lines_deleted,
            ];
        }, $git_commits));

        $this->view->render();
    }

}

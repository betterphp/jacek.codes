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
     * Gets an activity card definition frm a list of commits to the same project
     *
     * @param github_commit[] $commits The list of commits
     *
     * @return array The card definition to be passed to the view
     */
    private function get_activity_from_github_commits(array $commits): array {
        $activity = [
            'title' => "Commited to {$commits[0]->project_name}",
            'title_link' => "https://github.com/betterphp/{$commits[0]->project_name}",
            'type' => 'commits',
            'details' => [],
        ];

        foreach ($commits as $commit) {
            $datetime = \Datetime::createFromFormat('Y-m-d H:i:s', $commit->date);

            $activity['details'][] = [
                'link' => "{$activity['title_link']}/commit/{$commit->sha}",
                'message' => $commit->message,
                'date' => $datetime->format('D jS M Y \a\t H:i'),
            ];
        }

        return $activity;
    }

    /**
     * @inheritDoc
     */
    public function index(): void {
        $activities_filter = function ($qcb) {
            $qcb->order_by('date', false)->limit(50);
        };

        $git_commits = github_commit::get_list($activities_filter);

        $cards = [];
        $card_index = 0;
        $total_commits = count($git_commits);

        for ($i = 0; $i < $total_commits; ++$i) {
            $this_commit = $git_commits[$i];
            $next_commit = ($git_commits[($i + 1)] ?? null);

            $cards[$card_index][] = $this_commit;

            if ($next_commit !== null && $this_commit->project_name !== $next_commit->project_name) {
                ++$card_index;
            }
        }

        // Remove the last card as it will not be complete.
        unset($cards[$card_index]);

        $activities = array_map([$this, 'get_activity_from_github_commits'], $cards);

        $this->view->set_var('activities', $activities);

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

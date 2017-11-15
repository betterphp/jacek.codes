<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\command;

use \betterphp\jacek_codes_website\config;
use \betterphp\jacek_codes_website\model\activity\github_commit;

class import_github_commits extends command {

    /**
     * Helper function to parse the date of a commit to a DateTime object
     *
     * @param array $commit The commit data as returned by the API
     *
     * @return \DateTime The time
     */
    private function get_commit_datetime(array $commit): \DateTime {
        return \DateTime::createFromFormat(
            \DateTime::ATOM,
            $commit['commit']['author']['date']
        );
    }

    /**
     * @inheritDoc
     */
    public function execute(): void {
        $client = new \Github\Client();
        $client->authenticate(config::GITHUB_API_NAME, config::GITHUB_API_TOKEN, \Github\Client::AUTH_HTTP_PASSWORD);
        $pager = new \Github\ResultPager($client);

        $repos = $pager->fetchAll(
            $client->api('user')->setPerPage(100),
            'repositories',
            [ config::GITHUB_API_NAME ]
        );

        $last_updates = github_commit::get_last_commit_dates();

        foreach ($repos as $repo) {
            echo "Starting work on repo: {$repo['name']}\n";

            $latest_repo_update = ($last_updates[$repo['name']] ?? null);

            do {
                try {
                    $commits = $pager->fetch(
                        $client->api('repos')->commits()->setPerPage(100),
                        'all',
                        [ config::GITHUB_API_NAME, $repo['name'], [] ]
                    );
                } catch (\Github\Exception\RuntimeException $exception) {
                    if ($exception->getMessage() === 'Repository access blocked') {
                        // Skip over repos that have been made private
                        continue 2;
                    }

                    throw $exception;
                }

                // Just do nothing with empty repos
                if (count($commits) === 0) {
                    continue;
                }

                // Only include commits done by the right user
                $commits = array_filter($commits, function (array $commit): bool {
                    return ($commit['author']['login'] === config::GITHUB_API_NAME);
                });

                // Find the most recent one to compare with the current database
                $latest_commit_date = array_reduce($commits, function (?\DateTime $previous, array $commit) {
                    $commit_date = $this->get_commit_datetime($commit);

                    return ($previous === null || $commit_date > $previous)
                        ? $commit_date
                        : $previous;
                });

                foreach ($commits as $commit) {
                    $commit_date = $this->get_commit_datetime($commit);

                    // If there are none in the database or this one is newer
                    if ($latest_repo_update === null || $commit_date > $latest_repo_update) {
                        github_commit::create([
                            'project_name' => $repo['name'],
                            'sha' => $commit['sha'],
                            'message' => $commit['commit']['message'],
                            'files_modified' => 0,
                            'lines_added' => 0,
                            'lines_deleted' => 0,
                            'date' => $commit_date,
                        ]);

                        echo implode(' ', [
                            $repo['name'],
                            $commit['commit']['author']['date'],
                            $commit['sha'],
                            $commit['commit']['message'],
                        ]);

                        echo "\n";
                    }
                }

                // Carry on while there are more commits and we're
                // not at the most recent one from the database yet
            } while (
                $pager->hasNext() &&
                $latest_repo_update !== null &&
                $latest_commit_date > $latest_repo_update
            );
        }
    }

}

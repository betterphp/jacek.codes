<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model\activity;

use \betterphp\jacek_codes_website\config;
use \betterphp\jacek_codes_website\model\activity;

class git_commit extends activity {

    /**
     * Placeholder function to get a list of commit events directly from the GitHub API
     *
     * @return array A list of events
     */
    public static function get_from_api(): array {
        $client = new \Github\Client();
        $client->authenticate(config::GITHUB_API_NAME, config::GITHUB_API_TOKEN, \Github\Client::AUTH_HTTP_PASSWORD);

        $response = $client->getHttpClient()->get('users/' . config::GITHUB_API_NAME . '/events');
        $events = \Github\HttpClient\Message\ResponseMediator::getContent($response);

        $commits = [];

        foreach ($events as $event) {
            if ($event['type'] === 'PushEvent') {
                foreach ($event['payload']['commits'] as $commit) {
                    $commits[] = new self($commit['sha'], new \DateTime(), $commit['message'], $commit['url']);
                }
            }
        }

        return $commits;
    }

}

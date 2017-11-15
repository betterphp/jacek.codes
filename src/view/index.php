<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

class index extends page_view {

    /**
     * @inheritDoc
     */
    public function render_page_content(): void {
        foreach ($this->get_var('github_commits') as $event) {
            $project_url = "https://github.com/betterphp/{$event['project_name']}";
            $commit_url = "{$project_url}/commit/{$event['sha']}";

            ?>
            <article class="activity-summary">
                <header class="activity-header">
                    <div class="activity-header-main">
                        <h2>
                            <a href="<?= $project_url ?>">
                                Committed to <?= $event['project_name'] ?>
                            </a>
                        </h2>
                        <span class="activity-date"><?= $event['date'] ?></span>
                    </div>
                    <h3 class="activity-header-details">
                        <a href="<?= $commit_url ?>">
                            <?= substr($event['sha'], 0, 6), ': ', $event['message'] ?>
                        </a>
                    </h3>
                </header>
            </article>
            <?php
        }
    }

}

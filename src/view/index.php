<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

class index extends page_view {

    /**
     * Helper function to render the content for a commit set
     *
     * @param array $commits The commit list
     *
     * @return void
     */
    private function render_commits_content(array $commits): void {
        ?>
        <ol class="commit-list">
            <?php

            foreach ($commits as $commit) {
                ?>
                <li>
                    <div class="commit-message">
                        <a href="<?= htmlentities($commit['link']) ?>">
                            <?= htmlentities($commit['message']) ?>
                        </a>
                    </div>
                    <div class="commit-date"><?= $commit['date'] ?></div>
                </li>
                <?php
            }

            ?>
        </ol>
        <?php
    }

    /**
     * @inheritDoc
     */
    public function render_page_content(): void {
        foreach ($this->get_var('activities') as $activity) {
            ?>
            <article class="activity-summary">
                <header class="activity-header">
                    <h2>
                        <a href="<?= htmlentities($activity['title_link']) ?>">
                            <?= htmlentities($activity['title']) ?>
                        </a>
                    </h2>
                </header>
                <div class="activity-content">
                    <?php

                    switch ($activity['type']) {
                        case 'commits':
                            $this->render_commits_content($activity['details']);
                        break;
                    }

                    ?>
                </div>
            </article>
            <?php
        }
    }

}

<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

class index extends page_view {

    /**
     * @inheritDoc
     */
    public function render_page_content(): void {
        foreach ($this->get_var('events') as $event) {
            ?>
            <article class="activity-summary">
                <header class="activity-header">
                    <h2>
                        <a href=""><?= $event['title']; ?></a>
                    </h2>
                </header>
                <p>
                    <?= $event['body']; ?>
                </p>
            </article>
            <?php
        }
    }

}

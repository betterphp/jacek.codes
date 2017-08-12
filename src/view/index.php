<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

class index extends page_view {

    /**
     * @inheritDoc
     */
    public function render_page_content(): void {
        for ($i = 0; $i < 10; ++$i) {
            ?>
            <article>
                <header>
                    <h2>
                        <a href="">Title</a>
                    </h2>
                </header>
                <p>
                    Some intro text
                </p>
            </article>
            <?php
        }
    }

}

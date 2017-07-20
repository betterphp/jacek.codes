<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

abstract class page_view extends html_view {

    /**
     * Renders the content of the page in the main container
     *
     * @return void
     */
    abstract protected function render_page_content(): void;

    /**
     * @inheritDoc
     */
    protected function render_body(): void {
        $this->render_page_content();
    }

}

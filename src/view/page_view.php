<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

use \betterphp\jacek_codes_website\view\component\stylesheet;

abstract class page_view extends html_view {

    /**
     */
    public function __construct(string $site_root) {
        parent::__construct($site_root);

        $this->add_stylesheet(new stylesheet("{$this->site_root}/ext/css/build/common.min.css", 'screen'));
    }

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

<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

use \betterphp\jacek_codes_website\view\component;
use \betterphp\jacek_codes_website\view\component\script;
use \betterphp\jacek_codes_website\view\component\stylesheet;

abstract class html_view extends view {

    protected $site_root = null;

    private $page_title = '';
    private $header_scripts = [];
    private $footer_scripts = [];
    private $stylesheets = [];

    /**
     * @param string $site_root The URL to the index file
     */
    public function __construct(string $site_root) {
        $this->site_root = $site_root;
    }

    /**
     * Sets the title to be used for the document
     *
     * @param string $page_title The new title
     *
     * @return void
     */
    public function set_page_title(string $page_title): void {
        $this->page_title = $page_title;
    }

    /**
     * Adds a script to the document head
     *
     * @param script $script The script component
     *
     * @return void
     */
    public function add_header_script(script $script): void {
        $this->header_scripts[] = $script;
    }

    /**
     * Adds a script to the bottom of the body tag
     *
     * @param script $script The script component
     *
     * @return void
     */
    public function add_footer_script(script $script): void {
        $this->footer_scripts[] = $script;
    }

    /**
     * Adds a stylesheet to the document
     *
     * @param stylesheet $stylesheet The stylesheet to add
     *
     * @return void
     */
    public function add_stylesheet(stylesheet $stylesheet): void {
        $this->stylesheets[] = $stylesheet;
    }

    /**
     * Renders the body section of the HTML document
     *
     * @return void
     */
    abstract protected function render_body(): void;

    /**
     * Helper method to render an array of view components
     *
     * @param component[] $list The list of components
     *
     * @return void
     */
    protected function render_component_list(array $list): void {
        foreach ($list as $component) {
            if (!method_exists($component, 'render')) {
                throw new \Exception('Components does not have a render() method');
            }

            $component->render();
        }
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <title><?= htmlentities($this->page_title); ?></title>
                <?php

                $this->render_component_list($this->stylesheets);
                $this->render_component_list($this->header_scripts);

                ?>
            </head>
            <body>
                <?php

                $this->render_body();

                $this->render_component_list($this->footer_scripts);

                ?>
            </body>
        </html>
        <?php
    }

}

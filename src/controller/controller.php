<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\controller;

use \betterphp\jacek_codes_website\view\view;
use \betterphp\jacek_codes_website\view\html_view;
use \betterphp\jacek_codes_website\view\component\inline_script;

abstract class controller {

    protected $view;

    /**
     * @param string $class_name The name of the view class
     */
    public function __construct(string $class_name = null) {
        if ($class_name !== null) {
            if (!is_subclass_of($class_name, view::class)) {
                throw new \Exception('Class does not implement a view');
            }

            $this->view = new $class_name($this->get_site_root());

            if ($this->view instanceof html_view) {
                $error_reporter = \betterphp\error_reporting\sentry_reporter::get();
                $this->view->add_header_script(new inline_script($error_reporter->get_client_script()));
            }
        }
    }

    /**
     * Gets the absolute URL for the site root
     *
     * This does not account for things like http authentication or weird ports but will do for our purposes
     *
     * @return string The URL
     */
    protected function get_site_root(): string {
        $path = dirname($_SERVER['PHP_SELF'], 1);

        // This method should return the path with no trailing slash but dirname will return one for the root folder
        if ($path === '/') {
            $path = '';
        }

        return "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$path}";
    }

    /**
     * The default method to be called if none is specified
     *
     * This should not be overridden to accept any arguments
     *
     * @return void
     */
    abstract public function index(): void;

}

<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\controller;

use \betterphp\jacek_codes_website\view\view;

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

            $this->view = new $class_name();
        }
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

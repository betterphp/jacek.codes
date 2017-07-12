<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website;

use \betterphp\jacek_codes_website\controller\controller;

class router {

    private static function get_controller(string $controller_name): controller {
        if (!preg_match('#^[a-z_]+$#', $controller_name)) {
            throw new \Exception('Invalid controller name');
        }

        $namespace = substr(controller::class, 0, strrpos(controller::class, '\\'));

        $controller_class = "{$namespace}\\{$controller_name}";

        if (!class_exists($controller_class)) {
            throw new \Exception('Controller not found');
        }

        return new $controller_class();
    }

    /**
     * Gets the method to call for a given action
     *
     * @param controller $controller The controller that should do the action
     * @param string $action_name The name of the action
     *
     * @return \ReflectionMethod The method
     */
    private static function get_action_method(controller $controller, string $action_name): \ReflectionMethod {
        if (!preg_match('#^[a-z_]+$#', $action_name)) {
            throw new \Exception('Invalid action name');
        }

        if (!method_exists($controller, $action_name)) {
            throw new \Exception('Action not found');
        }

        return new \ReflectionMethod($controller, $action_name);
    }

    /**
     * Passes incomming web requests to their controller to be handled
     *
     * @param string $controller_name The name of the controller
     * @param string $action_name The action that it should perform
     *
     * @return void
     */
    public static function start(string $controller_name, string $action_name): void {
        $controller = self::get_controller($controller_name);
        $method = self::get_action_method($controller, $action_name);

        $method->invoke($controller);
    }

}

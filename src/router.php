<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website;

use \betterphp\jacek_codes_website\controller\controller;

class router {

    /**
     * Gets a new controller instance from it's name
     *
     * @param string $controller_name The controller name
     *
     * @return controller the controller instance
     */
    private static function get_controller(string $controller_name): controller {
        // Sanity check on input
        if (!preg_match('#^[a-z_]+$#', $controller_name)) {
            throw new \Exception('Invalid controller name');
        }

        // Work out what the namespace should be
        $namespace = substr(controller::class, 0, strrpos(controller::class, '\\'));

        // Class name should then be this
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
     * Parses the doc comment for a method and gets the values for it's arguents
     *
     * @param \ReflectionMethod $method The method
     *
     * @return array A list of method arguments
     */
    private static function get_argument_list(\ReflectionMethod $method): array {
        $doc_comment = $method->getDocComment();

        preg_match_all(
            '#@param\s([a-z]+)\s\$([a-z0-9_]+).+@(get|post)#',
            $doc_comment,
            $matched_params,
            PREG_SET_ORDER
        );

        $defined_params = [];

        foreach ($matched_params as $matched_param) {
            $defined_params[$matched_param[2]] = [
                'type' => $matched_param[1],
                'source' => $matched_param[3],
            ];
        }

        $method_params = $method->getParameters();

        if (count($method_params) !== count($defined_params)) {
            throw new \Exception('Invalid doc comment: Parameter count does not match function');
        }

        $function_params = [];

        foreach ($method_params as $method_param) {
            $param_name = $method_param->getName();

            if (!isset($defined_params[$param_name])) {
                throw new \Exception('Invalid doc comment: Parameter not defined');
            }

            $defined_param = $defined_params[$param_name];

            switch ($defined_param['source']) {
                case 'get':
                    $value = ($_GET[$param_name] ?? null);
                break;
                case 'post':
                    $value = ($_POST[$param_name] ?? null);
                break;
                default:
                    throw new \Exception('Invalid doc comment: Source is not valid');
                break;
            }

            if ($value !== null) {
                switch ($defined_param['type']) {
                    case 'boolean':
                    case 'integer':
                    case 'float':
                    case 'string':
                        settype($value, $defined_param['type']);
                    break;
                    default:
                        throw new \Exception('Invalid doc comment: Type is not valid');
                    break;
                }
            } else if (!$method_param->isOptional()) {
                throw new \Exception('Required parameter not provided');
            }

            $function_params[] = $value;
        }

        return $function_params;
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
        $arguments = self::get_argument_list($method);

        $method->invoke($controller, ...$arguments);
    }

}

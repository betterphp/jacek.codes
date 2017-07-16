<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\jacek_codes_website\router;
use \betterphp\jacek_codes_website\controller\controller;
use \betterphp\utils\reflection;
use \betterphp\native_mock\native_mock;

/**
 * @covers \betterphp\jacek_codes_website\router
 */
class RouterTest extends TestCase {

    use native_mock;

    public function setUp(): void {
        $this->nativeMockSetUp();
    }

    public function teardown(): void {
        $this->nativeMockTearDown();
    }

    public function testGetController(): void {
        $controller = reflection::call_method(router::class, 'get_controller', ['index']);

        $this->assertInstanceOf(controller::class, $controller);
    }

    public function testGetControllerWithInvalidName(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid controller name');

        reflection::call_method(router::class, 'get_controller', ['ALL CAPS WITH SPACES']);
    }

    public function testGetControllerWithIncorrectName(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Controller not found');

        reflection::call_method(router::class, 'get_controller', ['hopefully_this_wont_exist']);
    }

    public function testGetActionMethod(): void {
        $controller = reflection::call_method(router::class, 'get_controller', ['index']);
        $method = reflection::call_method(router::class, 'get_action_method', [$controller, 'index']);

        $this->assertInstanceOf(\ReflectionMethod::class, $method);
    }

    public function testGetActionMethodWithInvalidName(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid action name');

        $controller = $this->getMockBuilder(controller::class)->getMockForAbstractClass();

        reflection::call_method(router::class, 'get_action_method', [$controller, 'ALL CAPS WITH SPACES']);
    }

    public function testGetActionMethodWithIncorrectName(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Action not found');

        $controller = $this->getMockBuilder(controller::class)->getMockForAbstractClass();

        reflection::call_method(router::class, 'get_action_method', [$controller, 'not_a_method']);
    }

    public function testGetArgumentList(): void {
        $class = new class {

            /**
             * @param string $param_one Example string param @get
             * @param boolean $param_two Example boolean param @post
             */
            public function mock_action_method(string $param_one, bool $param_two = null): void {

            }

        };

        $_GET['param_one'] = 'example_one';
        $_POST['param_two'] = '0';

        $method = new \ReflectionMethod($class, 'mock_action_method');
        $function_params = reflection::call_method(router::class, 'get_argument_list', [$method]);

        $this->assertCount(2, $function_params);
        $this->assertSame($_GET['param_one'], $function_params[0]);
        $this->assertFalse($function_params[1]);
    }

    public function testStart(): void {
        $controller = new class extends controller {

            public $value = null;

            public function index(): void {
                // Controller is abstract so this just has to be here
            }

            /**
             * @param string $example_param An example @get
             */
            public function example_method(string $example_param): void {
                $this->value = $example_param;
            }

        };

        // Need to always get the mock controller above
        $this->redefineMethod(router::class, 'get_controller', function () use (&$controller) {
            return $controller;
        });

        $_GET['example_param'] = 'some awesome string data';

        router::start(get_class($controller), 'example_method');

        // Method should have been called and set the value property to the input parameter
        $this->assertSame($_GET['example_param'], $controller->value);
    }

}

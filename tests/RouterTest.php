<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\jacek_codes_website\router;
use \betterphp\jacek_codes_website\controller\controller;
use \betterphp\utils\reflection;

/**
 * @covers \betterphp\jacek_codes_website\router
 */
class RouterTest extends TestCase {

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

}

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

}

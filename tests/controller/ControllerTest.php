<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\utils\reflection;
use \betterphp\jacek_codes_website\view\view;
use \betterphp\jacek_codes_website\controller\controller;

/**
 * @covers \betterphp\jacek_codes_website\controller\controller
 */
class ControllerTest extends TestCase {

    public function testCreateView(): void {
        $test_view = $this->getMockBuilder(view::class)
                          ->getMockForAbstractClass();

        $test_controller = $this->getMockBuilder(controller::class)
                                ->setConstructorArgs([get_class($test_view)])
                                ->getMockForAbstractClass();

        $created_view = reflection::get_property($test_controller, 'view');

        $this->assertInstanceOf(view::class, $created_view);
    }

    public function testCreateViewWithBadClassName(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Class does not implement a view');

        $this->getMockBuilder(controller::class)
             ->setConstructorArgs([\DateTime::class])
             ->getMockForAbstractClass();
    }

}

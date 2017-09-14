<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\utils\reflection;
use \betterphp\jacek_codes_website\view\html_view;
use \betterphp\jacek_codes_website\view\component\component;
use \betterphp\jacek_codes_website\view\component\script;
use \betterphp\jacek_codes_website\view\component\stylesheet;

/**
 * @covers \betterphp\jacek_codes_website\view\html_view
 */
class HtmlViewTest extends TestCase {

    private function getHtmlView(): html_view {
        return $this->getMockBuilder(html_view::class)
                    ->setConstructorArgs([''])
                    ->getMockForAbstractClass();
    }

    public function testSetPageTitle(): void {
        $view = $this->getHtmlView();
        $expected_title = 'such page, very title';

        $view->set_page_title($expected_title);

        $actual_title = reflection::get_property($view, 'page_title');

        $this->assertSame($expected_title, $actual_title);
    }

    /**
     * @dataProvider dataAddScripts
     */
    public function testAddResources(string $add_method_name, string $var_name, string $class_name): void {
        $view = $this->getHtmlView();

        $expected_resource = $this->getMockBuilder($class_name)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $view->$add_method_name($expected_resource);

        $actual_resources = reflection::get_property($view, $var_name);

        $this->assertCount(1, $actual_resources);
        $this->assertSame($expected_resource, $actual_resources[0]);
    }

    public function dataAddScripts(): array {
        return [
            ['add_header_script', 'header_scripts', script::class],
            ['add_footer_script', 'footer_scripts', script::class],
            ['add_stylesheet', 'stylesheets', stylesheet::class],
        ];
    }

    public function testRenderComponentList(): void {
        $view = $this->getHtmlView();
        $expected_output = 'such content, very rendered, wow';

        $component = $this->getMockBuilder(component::class)
                          ->getMockForAbstractClass();

        $component->method('render')
                  ->will($this->returnCallback(function () use ($expected_output) {
                      echo $expected_output;
                  }));

        ob_start();
        reflection::call_method($view, 'render_component_list', [ [ $component ] ]);
        $output = ob_get_clean();

        $this->assertSame($expected_output, $output);
    }

    public function testRenderWithBadComponent(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Components does not have a render() method');

        $view = $this->getHtmlView();

        reflection::call_method($view, 'render_component_list', [ [ new \DateTime() ] ]);
    }

    public function testRender(): void {
        $view = $this->getHtmlView();

        $page_title = 'such page';
        $header_script = new script('header_script.js');
        $footer_script = new script('footer_script.js');
        $stylesheet = new stylesheet('stylesheet.css', 'screen');

        $view->set_page_title($page_title);
        $view->add_header_script($header_script);
        $view->add_footer_script($footer_script);
        $view->add_stylesheet($stylesheet);

        ob_start();
        $view->render();
        $output = ob_get_clean();

        // Should be a HTML document
        $this->assertContains('<!DOCTYPE html>', $output);

        // And contain all of the resources, the tests for those
        // check for correct rendering so the URL will do here
        $this->assertContains($page_title, $output);
        $this->assertContains(reflection::get_property($header_script, 'src'), $output);
        $this->assertContains(reflection::get_property($footer_script, 'src'), $output);
        $this->assertContains(reflection::get_property($stylesheet, 'href'), $output);
    }

}

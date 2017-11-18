<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\utils\reflection;
use \betterphp\jacek_codes_website\view\component\linked_script;

/**
 * @covers \betterphp\jacek_codes_website\view\component\linked_script
 */
class LinkedScriptTest extends TestCase {

    public function testSetProperties(): void {
        $expected_src = 'such_script.js';
        $expected_defer = true;
        $expected_async = false;

        $script = new linked_script($expected_src, $expected_defer, $expected_async);

        $actual_src = reflection::get_property($script, 'src');
        $actual_defer = reflection::get_property($script, 'defer');
        $actual_async = reflection::get_property($script, 'async');

        $this->assertSame($expected_src, $actual_src);
        $this->assertSame($expected_defer, $actual_defer);
        $this->assertSame($expected_async, $actual_async);
    }

    public function testRender(): void {
        $expected_src = 'such_script.js';

        $script = new linked_script($expected_src, true, true);

        ob_start();
        $script->render();
        $output = ob_get_clean();

        // Should have a script tag
        $this->assertContains('<script type="text/javascript"', $output);
        $this->assertContains('</script>', $output);

        // And the attributes we set
        $this->assertContains("src=\"{$expected_src}\"", $output);
        $this->assertContains('async="async"', $output);
        $this->assertContains('defer="defer"', $output);
    }

}

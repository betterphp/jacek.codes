<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\utils\reflection;
use \betterphp\jacek_codes_website\view\component\stylesheet;

/**
 * @covers \betterphp\jacek_codes_website\view\component\stylesheet
 */
class StylesheetTest extends TestCase {

    public function testSetProperties(): void {
        $expected_href = 'such_style.css';
        $expected_media = 'all';

        $script = new stylesheet($expected_href, $expected_media);

        $actual_href = reflection::get_property($script, 'href');
        $actual_media = reflection::get_property($script, 'media');

        $this->assertSame($expected_href, $actual_href);
        $this->assertSame($expected_media, $actual_media);
    }

    public function testRender(): void {
        $expected_href = 'such_style.css';
        $expected_media = 'screen';

        $script = new stylesheet($expected_href, $expected_media);

        ob_start();
        $script->render();
        $output = ob_get_clean();

        // Should have a link tag
        $this->assertContains('<link rel="stylesheet" type="text/css"', $output);
        $this->assertContains('/>', $output);

        // And the attributes we set
        $this->assertContains("href=\"{$expected_href}\"", $output);
        $this->assertContains("media=\"{$expected_media}\"", $output);
    }

}

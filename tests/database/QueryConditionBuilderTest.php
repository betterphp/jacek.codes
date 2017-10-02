<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \betterphp\utils\reflection;

use \betterphp\jacek_codes_website\database\query_condition_builder;

class QueryConditionBuilderTest extends TestCase {

    public function testCreate(): void {
        $expected_sql = '';
        $expected_params = [];
        $expected_offset = 1337;

        $builder = new query_condition_builder($expected_offset);

        $actual_sql = reflection::get_property($builder, 'sql');
        $actual_params = reflection::get_property($builder, 'params');
        $actual_offset = reflection::get_property($builder, 'param_offset');

        // Properties should have been set correctly
        $this->assertSame($expected_sql, $actual_sql);
        $this->assertSame($expected_params, $actual_params);
        $this->assertSame($expected_offset, $actual_offset);
    }

}

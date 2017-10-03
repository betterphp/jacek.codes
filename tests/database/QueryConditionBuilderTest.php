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

    public function testGetSql(): void {
        $expected_sql = 'SELECT such, colum, very, relational FROM doge';

        $builder = new query_condition_builder();

        reflection::set_property($builder, 'sql', $expected_sql);

        $this->assertSame($expected_sql, $builder->get_sql());
    }

    public function testGetParams(): void {
        $expected_params = [
            'such' => 'params',
            'very' => 'assoc',
        ];

        $builder = new query_condition_builder();

        reflection::set_property($builder, 'params', $expected_params);

        $this->assertSame($expected_params, $builder->get_params());
    }

    public function testGetParamName(): void {
        $field = 'very_field';
        $offset = 10;
        $expected_field_name = "{$field}_{$offset}";

        $builder = new query_condition_builder($offset);

        $actual_field_name = reflection::call_method($builder, 'get_param_name', [$field]);

        $this->assertSame($expected_field_name, $actual_field_name);
    }

    /**
     * @dataProvider dataGetcomparisonString
     */
    public function testGetComparisonString(
        string $comparison,
        $value,
        string $expected_string
    ): void {
        $builder = new query_condition_builder();

        $actual_string = reflection::call_method(
            $builder,
            'get_comparison_string',
            [
                'field',
                $comparison,
                $value,
            ]
        );

        $this->assertSame($expected_string, $actual_string);
    }

    public function dataGetcomparisonString(): array {
        return [
            ['=', [0, 1, 2], ' IN (:field_0,:field_1,:field_2) '],
            ['!=', [0, 1], ' NOT IN (:field_0,:field_1) '],
            ['=', 'anything', ' = :field_0 '],
            ['!=', 'anything', ' != :field_0 '],
            ['>', 'anything', ' > :field_0 '],
            ['<', 'anything', ' < :field_0 '],
        ];
    }

    /**
     * @dataProvider dataGetComparisonStringWithInvalidInput
     */
    public function testGetComparisonStringWithInvalidInput(
        string $comparison,
        $value,
        string $expected_message
    ): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($expected_message);

        $builder = new query_condition_builder();

        reflection::call_method(
            $builder,
            'get_comparison_string',
            [
                'field',
                $comparison,
                $value,
            ]
        );
    }

    public function dataGetComparisonStringWithInvalidInput(): array {
        return [
            ['=', [], 'Comparison with an empty list'],
            ['what even', [1, 2, 3], 'Unsupported comparison'],
            ['>', null, 'Mathamatical comparisons are not possible with null values'],
            ['<', null, 'Mathamatical comparisons are not possible with null values'],
            ['what even', 'anything', 'Unsupported comparison'],
        ];
    }

}

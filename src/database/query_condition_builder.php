<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\database;

class query_condition_builder {

    private $sql;
    private $params;
    private $param_offset;

    /**
     * @param integer $param_offset An offset to apply when naming query params
     */
    public function __construct(int $param_offset = 0) {
        $this->sql = '';
        $this->params = [];
        $this->param_offset = $param_offset;
    }

    /**
     * Gets the built SQL WHERE statement
     *
     * @return string The SQL
     */
    public function get_sql(): string {
        return $this->sql;
    }

    /**
     * Gets a map of the comparison values
     *
     * @return array The map
     */
    public function get_params(): array {
        return $this->params;
    }

    /**
     * Gets the string to use as the parameter name for PDO
     *
     * @param string $field The name of the field
     *
     * @return string The param name
     */
    private function get_param_name(string $field): string {
        return "{$field}_" . (count($this->params) + $this->param_offset);
    }

    /**
     * Helper function to generate the comparison string for a field
     *
     * @param string $field The field name being bound
     * @param string $comparison The type of comparison to be done
     * @param mixed $value The value to compare
     *
     * @return string The SQL string
     */
    private function get_comparison_string(string $field, string $comparison, $value): string {
        if (is_array($value)) {
            if (count($value) === 0) {
                throw new \Exception('Comparison with an empty list');
            }

            foreach ($value as $part) {
                $param_name = $this->get_param_name($field);

                $this->params[$param_name] = $part;
                $params_names[] = $param_name;
            }

            switch ($comparison) {
                case '=':
                    return ' IN (:' . implode(',:', $params_names) . ') ';
                case '!=':
                    return ' NOT IN (:' . implode(',:', $params_names) . ') ';
                default:
                    throw new \Exception('Unsupported comparison');
            }
        } else {
            $param_name = $this->get_param_name($field);

            $this->params[$param_name] = $value;

            $maths_comparisons = ['>', '<'];

            if ($value === null && in_array($comparison, $maths_comparisons)) {
                throw new \Exception('Mathamatical comparisons are not possible with null values');
            }

            switch ($comparison) {
                case '=':
                    return " = :{$param_name} ";
                case '!=':
                    return " != :{$param_name} ";
                case '>':
                    return " > :{$param_name} ";
                case '<':
                    return " < :{$param_name} ";
                default:
                    throw new \Exception('Unsupported comparison');
            }
        }
    }

    /**
     * Starts the query building
     *
     * @param string $field The field to compare
     * @param mixed $value The value to compare to
     * @param string $comparison The comparison operator to use
     *
     * @return self The current builder
     */
    public function where(string $field, $value, string $comparison = '='): self {
        $this->sql .= " WHERE {$field} " . $this->get_comparison_string($value, $comparison) . ' ';

        return $this;
    }

    /**
     * Adds an and condition to the query
     *
     * @param string $field The name of the field to compare
     * @param mixed $value The value to compare to
     * @param string $comparison The SQL comparison operator to use
     *
     * @return self The current builder
     */
    public function and(string $field, $value, string $comparison = '='): self {
        $this->sql .= " AND {$field} " . $this->get_comparison_string($value, $comparison) . ' ';

        return $this;
    }

    /**
     * Adds an or condition to the query
     *
     * @param string $field The field to be compared
     * @param mixed $value The value to compare to
     * @param string $comparison The SQL comparison operator to use`
     *
     * @return self The current builder
     */
    public function or(string $field, $value, string $comparison = '='): self {
        $this->sql .= " OR {$field} " . $this->get_comparison_string($value, $comparison) . ' ';

        return $this;
    }

    /**
     * Adds a nested condition (eqivelant to using brackets in SQL)
     *
     * @param \Closure $nested_condition_callback A function to call with a new query builder
     *
     * @return self The current query builder
     */
    public function and_where(\Closure $nested_condition_callback): self {
        $qcb = new self(count($this->params));

        $nested_condition_callback($qcb);

        $this->sql .= ' AND (' . $qcb->get_sql() . ') ';
        $this->params = array_merge($this->params, $qcb->get_params());

        return $this;
    }

    /**
     * Adds a nested or condition
     *
     * @param \Closure $nested_condition_callback A function to call with a new query builder
     *
     * @return self The current query builder
     */
    public function or_where(\Closure $nested_condition_callback): self {
        $qcb = new self(count($this->params));

        $nested_condition_callback($qcb);

        $this->sql .= ' OR (' . $qcb->get_sql() . ') ';
        $this->params = array_merge($this->params, $qcb->get_params());

        return $this;
    }

}

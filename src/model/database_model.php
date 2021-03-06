<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model;

use betterphp\jacek_codes_website\config;
use \betterphp\jacek_codes_website\database\query_condition_builder;

class database_model extends model {

    private $db;

    protected static $id_field = 'id';
    protected static $fields = [
        'id' => 'int',
    ];

    /**
     */
    public function __construct() {
        $this->db = self::get_database();
    }

    /**
     * Gets a connection to the database backend
     *
     * @return \PDO The database connection
     */
    public static function get_database(): \PDO {
        static $database = null;

        if ($database === null) {
            $database = new \PDO(
                config::DATABASE_DSN,
                config::DATABASE_USERNAME,
                config::DATABASE_PASSWORD
            );

            $database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $database->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        }

        return $database;
    }

    /**
     * Helper function to build an SQL formatted field list
     *
     * @return string The field list string
     */
    private static function get_sql_field_list(): string {
        return implode(', ', array_keys(static::$fields));
    }

    /**
     * Helper function to bind a single SQL value
     *
     * @param \PDOStatement $stmt The statement to bind the value to
     * @param string $param_name The name of the param key in the SQL
     * @param string $field_name The database field that is being bound to
     * @param mixed $value The value to bind
     *
     * @return void
     */
    private static function bind_sql_param(
        \PDOStatement $stmt,
        string $param_name,
        string $field_name,
        $value
    ): void {
        $data_type = (static::$fields[$field_name] ?? null);

        if ($data_type === null) {
            throw new \Exception("Unknown field {$field_name}");
        }

        // Special case null values
        if ($value === null) {
            $stmt->bindValue($param_name, null, \PDO::PARAM_NULL);
            return;
        }

        switch ($data_type) {
            case 'bool':
                $stmt->bindParam($param_name, $value, \PDO::PARAM_BOOL);
            break;
            case 'int':
                $stmt->bindParam($param_name, $value, \PDO::PARAM_INT);
            break;
            case 'float':
            case 'string':
                $stmt->bindParam($param_name, $value, \PDO::PARAM_STR);
            break;
            case 'date':
                $stmt->bindValue($param_name, $value->format('Y-m-d'), \PDO::PARAM_STR);
            break;
            case 'datetime':
                $stmt->bindValue($param_name, $value->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            break;
            case 'time':
                $stmt->bindValue($param_name, $value->format('%H:%I:%S'), \PDO::PARAM_STR);
            break;
        }
    }

    /**
     * Helper function to bind the values from a query condition to a PDO statement
     *
     * @param \PDOStatement $stmt The statement to bind the values to
     * @param query_condition_builder $condition_builder The condition builder for the query
     *
     * @return void
     */
    private static function bind_sql_where(\PDOStatement $stmt, query_condition_builder $condition_builder): void {
        foreach ($condition_builder->get_params() as $name => &$value) {
            list($field_name, $param_number) = explode('_', $name, 2);

            self::bind_sql_param($stmt, $name, $field_name, $value);
        }
    }

    /**
     * Gets a list of model instances
     *
     * The calback function sould accept one parameter, a query_condition_builder object
     *
     * @param \Closure $conditions_callback A callback function used to build the query conditions
     *
     * @return array The results
     */
    public static function get_list(\Closure $conditions_callback): array {
        $database = self::get_database();

        $condition_builder = new query_condition_builder();
        $conditions_callback($condition_builder);

        $field_list = self::get_sql_field_list();
        $table = static::$table_name;
        $where = $condition_builder->get_sql();

        $stmt = $database->prepare("SELECT {$field_list} FROM {$table} {$where}");

        self::bind_sql_where($stmt, $condition_builder);

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_CLASS, get_called_class());

        $stmt->closeCursor();

        return $results;
    }

    /**
     * Gets a single model instance
     *
     * @param \Closure $conditions_callback The conditions to query on
     *
     * @return database_model The resulting model
     */
    public static function get(\Closure $conditions_callback): self {
        $list = self::get_list($conditions_callback);

        if (count($list) !== 1) {
            throw new \Exception('Not exactly one record returned');
        }

        return array_pop($list);
    }

    /**
     * Creates a new record in the database
     *
     * @param array $fields The fields to store in the record
     *
     * @return void
     */
    public static function create(array $fields): void {
        $database = self::get_database();

        $field_names = array_keys(static::$fields);
        $table_name = static::$table_name;
        $field_list = static::get_sql_field_list();
        $param_list = implode(', ', array_map(function (string $field_name): string {
            return ":{$field_name}";
        }, $field_names));

        $stmt = $database->prepare("INSERT INTO {$table_name} ({$field_list}) VALUES ({$param_list})");

        foreach ($field_names as $field_name) {
            $value = ($fields[$field_name] ?? null);

            self::bind_sql_param($stmt, ":{$field_name}", $field_name, $value);
        }

        $stmt->execute();
    }

    /**
    * Placeholder method
    *
    * @return void
    */
    public function delete() {

    }

}

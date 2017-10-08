<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model\activity;

use \betterphp\jacek_codes_website\config;
use \betterphp\jacek_codes_website\model\activity;

class git_commit extends activity {

    protected static $table_name = 'git_commit';
    protected static $id_field = 'id';
    protected static $fields = [
        'id' => 'int',
    ];

    /**
     * Gets a map of the last imported commit date for all git repos
     *
     * @return array The map
     */
    public static function get_last_commit_dates(string $host_domain): array {
        $sql = <<<SQL
            SELECT
                project_name,
                MAX(date) AS date
            FROM git_commit
            WHERE host_domain = :host_domain
            GROUP BY project_name
SQL;

        $database = self::get_database();
        $stmt = $database->prepare($sql);

        $stmt->bindParam(':host_domain', $host_domain, \PDO::PARAM_STR);

        $stmt->execute();

        $repos = [];

        while ($row = $stmt->fetch()) {
            $repos[$row->project_name] = \DateTime::createFromFormat('Y-m-d H:i:s', $row->date);
        }

        return $repos;
    }

}

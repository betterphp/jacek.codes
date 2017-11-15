<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\model\activity;

use \betterphp\jacek_codes_website\config;
use \betterphp\jacek_codes_website\model\activity;

class github_commit extends activity {

    protected static $table_name = 'github_commits';
    protected static $id_field = 'id';
    protected static $fields = [
        'id' => 'int',
        'project_name' => 'string',
        'sha' => 'string',
        'message' => 'string',
        'files_modified' => 'int',
        'lines_added' => 'int',
        'lines_deleted' => 'int',
        'date' => 'datetime',
    ];

    /**
     * Gets a map of the last imported commit date for all git repos
     *
     * @return array The map
     */
    public static function get_last_commit_dates(): array {
        $sql = <<<SQL
            SELECT
                project_name,
                MAX(date) AS date
            FROM github_commits
            GROUP BY project_name
SQL;

        $database = self::get_database();
        $stmt = $database->prepare($sql);
        $stmt->execute();

        $repos = [];

        while ($row = $stmt->fetch()) {
            $repos[$row->project_name] = \DateTime::createFromFormat('Y-m-d H:i:s', $row->date);
        }

        return $repos;
    }

}

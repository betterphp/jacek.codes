<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateGitCommitTable extends AbstractMigration {

    public function change() {
        $this->table('git_commit')
             ->addColumn('activity_id', 'integer')
             ->addColumn('project_name', 'string')
             ->addColumn('host_domain', 'enum', ['values' => ['github.com', 'git.jacekk.co.uk']])
             ->addColumn('sha', 'string', ['limit' => 40])
             ->addColumn('message', 'string')
             ->addColumn('files_modified', 'integer')
             ->addColumn('lines_added', 'integer')
             ->addColumn('lines_deleted', 'integer')
             ->addForeignKey('activity_id', 'activity', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
             ->create();
    }

}

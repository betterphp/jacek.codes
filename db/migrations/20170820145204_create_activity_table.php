<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateActivityTable extends AbstractMigration {

    public function change() {
        $this->table('activity')
             ->addColumn('time', 'datetime')
             ->addColumn('type', 'enum', ['values' => ['git_commit']])
             ->create();
    }

}

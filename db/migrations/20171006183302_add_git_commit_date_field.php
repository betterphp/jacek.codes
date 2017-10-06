<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class AddGitCommitDateField extends AbstractMigration {

    public function change() {
        $this->table('git_commit')
             ->addColumn('date', 'datetime')
             ->update();
    }

}

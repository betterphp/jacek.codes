<?php


use Phinx\Migration\AbstractMigration;

class SplitActivityTables extends AbstractMigration {

    public function up() {
        // Remove activity ID index
        $this->table('git_commit')->dropForeignKey('activity_id')->update();
        // Rename the table to be specific
        $this->table('git_commit')->rename('github_commits')->update();

        // Delete any commits from other hosts
        $this->query("DELETE FROM github_commits WHERE host_domain != 'github.com'");

        // Remove unused columns
        $this->table('github_commits')
             ->removeColumn('activity_id')
             ->removeColumn('host_domain')
             ->update();
    }

}

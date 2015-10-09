<?php
use Migrations\AbstractMigration;

class CreateTrackings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('trackings');
        $table->addColumn('started_at', 'timestamp', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('stopped_at', 'timestamp', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('trackable_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('trackable_type', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}

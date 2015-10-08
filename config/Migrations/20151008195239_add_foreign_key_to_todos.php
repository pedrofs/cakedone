<?php
use Migrations\AbstractMigration;

class AddForeignKeyToTodos extends AbstractMigration
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
        $table = $this->table('todos');
        $table->addForeignKey('user_id', 'users');
        $table->update();
    }
}

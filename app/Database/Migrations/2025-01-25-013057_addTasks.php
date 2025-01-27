<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'description' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'due_date'    => [
                'type'           => 'DATE',
            ],
            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'in_progress', 'completed'],
                'default'        => 'pending',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Rules extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'role_name'     => ['type' => 'VARCHAR', 'constraint' => 240, 'unique' => true, 'null' => true],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'created_at timestamp default current_timestamp',
            'updated_at'    => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rules');
    }

    public function down()
    {
        $this->forge->dropTable('rules');
    }
}

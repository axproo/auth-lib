<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'table_name' => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true],
            'permission_name' => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at timestamp default current_timestamp',
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permissions');
    }

    public function down()
    {
        $this->forge->dropTable('permissions');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RulesPermissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'role_id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'permission_id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true]
        ]);
        $this->forge->addForeignKey('role_id', 'rules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rule_permissions');
    }

    public function down()
    {
        $this->forge->dropTable('rule_permissions');
    }
}

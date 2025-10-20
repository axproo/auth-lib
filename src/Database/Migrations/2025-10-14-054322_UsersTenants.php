<?php

namespace Axproo\Auth\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTenants extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'tenant_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'role' => ['type' => 'ENUM', 'constraint' => ['superadmin','admin','msp','client','technicien'], 'default' => 'client'],
            'status' => ['type' => 'ENUM', 'constraint' => ['active','suspend'], 'default' => 'active'],
            'created_at timestamp default current_timestamp',
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id','users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tenant_id','tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users_tenants');
    }

    public function down()
    {
        $this->forge->dropTable('users_tenants');
    }
}

<?php

namespace Axproo\Auth\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersSessions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'jwt_token' => ['type' => 'TEXT', 'null' => true],
            'user_ip' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'TEXT', 'null' => true],
            'last_activity' => ['type' => 'DATETIME', 'null' => true],
            'created_at timestamp default current_timestamp',
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('users_sessions');
    }
}

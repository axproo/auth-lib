<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'first_name'            => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true],
            'last_name'             => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true],
            'email'                 => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true, 'unique' => true],
            'password'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'role_id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_type'             => ['type' => 'ENUM', 'constraint' => ['employee','client','merchant','admin','partner','vip'], 'default' => 'employee', 'null' => true],
            'owner_id'              => ['type' => 'INT', 'null' => true],
            'owner_type'            => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status'                => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'blocked', 'pending'], 'default' => 'pending'],
            'email_verified'        => ['type' => 'BOOLEAN', 'default' => false],
            'email_verified_at'     => ['type' => 'DATETIME', 'null' => true],
            'totp_secret'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'reset_token'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'reset_token_expires'   => ['type' => 'DATETIME', 'null' => true],
            'registration_type'     => ['type' => 'ENUM', 'constraint' => ['admin','self'], 'default' => 'self'],
            'ip_address'            => ['type' => 'VARCHAR', 'constraint' => 45],
            'created_at timestamp default current_timestamp',
            'updated_at'            => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'rules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}

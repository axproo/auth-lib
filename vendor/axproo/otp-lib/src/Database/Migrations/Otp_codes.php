<?php 
namespace Axproo\Otp\Database\Migrations;

use CodeIgniter\Database\Migration;

class Otp_codes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'purpose' => ['type' => 'VARCHAR', 'constraint' => 50],
            'target' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_used' => ['type' => 'BOOLEAN', 'default' => 0],
            'expires_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at timestamp default current_timestamp',
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('otp_codes');
    }

    public function down()
    {
        $this->forge->dropTable('otp_codes');
    }
}
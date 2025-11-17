<?php 
namespace Axproo\Otp\Database\Migrations;

use CodeIgniter\Database\Migration;

class Otp_codes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'receiver' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'channel' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'default' => 'email'],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50],
            'expires_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at timestamp default current_timestamp',
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('receiver');
        $this->forge->addKey('channel');
        $this->forge->createTable('otp_codes');
    }

    public function down()
    {
        $this->forge->dropTable('otp_codes');
    }
}
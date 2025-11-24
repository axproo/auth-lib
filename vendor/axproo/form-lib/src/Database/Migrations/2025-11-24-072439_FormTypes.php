<?php

use CodeIgniter\Database\Migration;

class FormTypes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'created_at timestamp default current_timestamp',
            'updated_at'    => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('form_types');
    }

    public function down()
    {
        $this->forge->dropTable('form_types');
    }
}
<?php

use CodeIgniter\Database\Migration;

class FormStatic extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'label'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'type'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'legend'        => ['type' => 'TEXT', 'null' => true],
            'placeholder'   => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true],
            'isLabel'       => ['type' => 'BOOLEAN', 'default' => false],
            'attributes'    => ['type' => 'TEXT', 'null' => true],
            'option_values' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provide_table' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provide_key'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provide_field' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provide_cond'  => ['type' => 'TEXT', 'null' => true],
            'is_read_only'  => ['type' => 'BOOLEAN', 'default' => false],
            'required'      => ['type' => 'BOOLEAN', 'default' => false],
            'sort_order'    => ['type' => 'INT', 'constraint' => 10, 'null' => true],
            'is_active'     => ['type' => 'BOOLEAN', 'default' => true],
            'form_group'    => ['type' => 'VARCHAR', 'constraint' => 240, 'null' => true],
            'for_system'    => ['type' => 'BOOLEAN', 'default' => false],
            'created_at timestamp default current_timestamp',
            'updated_at'    => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('type', 'form_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('form_static');
    }

    public function down()
    {
        $this->forge->dropTable('form_static');
    }
}
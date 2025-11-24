<?php

namespace Axproo\Form\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FormTypeSeeder extends Seeder
{
    public function run()
    {
        $data = array(
            array('name' => 'hidden','description' => 'Champs hidden (masqué)','updated_at' => '2025-04-18 05:47:55','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'password','description' => 'Champs password (mot de passe ***)','updated_at' => '2025-04-18 05:47:28','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'text','description' => 'Champs text (input)','updated_at' => '2025-04-18 05:47:10','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'select','description' => 'Champs select (sélection d\'un élément)','updated_at' => '2025-04-18 05:46:49','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'multiple','description' => 'Champs multiple (Sélection multiple)','updated_at' => '2025-04-18 05:46:26','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'textarea','description' => 'Champs Textarea (contenu)','updated_at' => '2025-04-18 05:46:04','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'file','description' => 'Champs file (fichier)','updated_at' => '2025-04-18 05:45:43','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'editor','description' => 'Champs editor (WYSIWYG)','updated_at' => '2025-04-18 05:45:00','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'checkbox','description' => 'Champs checkbox ','updated_at' => '2025-04-18 05:43:30','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'radio','description' => 'Champs radio','updated_at' => '2025-04-18 05:43:06','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'date','description' => 'Champs date, format: (DD/MM/YY)','updated_at' => '2025-04-18 05:42:51','created_at' => '2025-04-17 06:10:33'),
            array('name' => 'image','description' => 'Champs image'),
            array('name' => 'json','description' => 'Champs JSON: {"title":"text"}'),
            array('name' => 'code','description' => 'Champs OTP (code)')
          );
        $builder = $this->db->table('form_types');

        foreach ($data as $row) {
            $exists = $builder->where('name', $row['name'])->get()->getRow();
            if (!$exists) {
                $builder->insert($row);
            }
        }
    }
}

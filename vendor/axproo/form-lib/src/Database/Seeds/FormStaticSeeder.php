<?php

namespace Axproo\Form\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FormStaticSeeder extends Seeder
{
    public function run()
    {
        $sort = 1;
        
        $data = [
            [
                'name' => 'email',
                'label' => 'email',
                'type' => 3,
                'placeholder' => 'Email address',
                'isLabel' => false,
                'sort_order' => $sort++,
                'form_group' => 'mb-3',
                'for_system' => true,
                'attributes' => '{"class":"form-control"}'
            ],
            [
                'name' => 'password',
                'label' => 'Mot de passe',
                'type' => 2,
                'placeholder' => 'Mot de passe',
                'isLabel' => false,
                'sort_order' => $sort++,
                'form_group' => 'mb-3',
                'for_system' => true,
                'attributes' => '{"class":"form-control"}'
            ],
            [
                'name' => 'code',
                'label' => 'Code OTP',
                'type' => 14,
                'placeholder' => '0',
                'isLabel' => false,
                'sort_order' => $sort++,
                'form_group' => 'row my-3 g-3 text-center',
                'for_system' => true,
                'attributes' => '{"class":"form-control"}'
            ],
        ];
        $builder = $this->db->table('form_static');

        foreach ($data as $row) {
            $exists = $builder
                ->where([
                    'name' => $row['name'],
                ])->get()->getRow();
            if (!$exists) {
                $builder->insert($row);
            }
        }
    }
}

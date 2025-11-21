<?php

namespace Axproo\Auth\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RuleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'role_name'     => 'superadmin',
                'description'   => 'Administrateur avec tous les super privilèges'
            ],
            [
                'role_name'     => 'admin',
                'description'   => 'Administrateur avec tous les privilèges'
            ],
            [
                'role_name'     => 'user',
                'description'   => 'Utilisateurs avec droits limités'
            ],
        ];
        $builder = $this->db->table('rules');

        foreach ($data as $row) {
            $exists = $builder
                ->where('role_name', $row['role_name'])
                ->get()->getRow();
            if (!$exists) {
                $builder->insert($row);
            }
        }
    }
}

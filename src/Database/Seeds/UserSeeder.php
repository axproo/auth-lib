<?php

namespace Axproo\Auth\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'Admin',
                'last_name'  => 'TEST',
                'email'      => 'admin@example.com',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'role_id'    => 1,
                'updated_at' => Time::now()
            ],
            [
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'test@company.com',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'role_id'    => 2,
                'updated_at' => Time::now()
            ]
        ];
        $builder = $this->db->table('users');

        foreach ($data as $row) {
            $exists = $builder
                ->where('email', $row['email'])
                ->get()->getRow();
            if (!$exists) {
                $builder->insert($row);
            }
        }
    }
}

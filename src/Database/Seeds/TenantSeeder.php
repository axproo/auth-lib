<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Company Technology',
                'domain' => 'company.com',
                'email' => 'contact@company.com',
                'phone' => '+33 X XX XX XX XX',
                'status' => 'active'
            ],
        ];
        $builder = $this->db->table('tenants');

        foreach ($data as $row) {
            $exists = $builder
                ->where('uuid', $row['uuid'])
                ->get()->getRow();
            if (!$exists) {
                $builder->insert($row);
            }
        }
    }
}

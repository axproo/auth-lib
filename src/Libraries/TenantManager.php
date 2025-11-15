<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Models\TenantModel;

class TenantManager
{
    protected TenantModel $model;

    public function __construct() {
        $this->model = new TenantModel();
    }

    public function getTenantById(?int $userId) : ?string {
        if (!$userId) return null;

        $userTenant = $this->model
            ->select('tenants.uuid')
            ->join('tenants', 'tenants.id = users_tenants.tenant_id', 'left')
            ->where('user_id', $userId)
            ->first();
        return $userTenant->uuid ?? null;
    }
}
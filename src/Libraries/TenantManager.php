<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Models\UserTenantModel;

class TenantManager
{
    protected UserTenantModel $model;

    public function __construct() {
        $this->model = new UserTenantModel();
    }

    public function getTenantById(?int $userId) : ?string {
        if (!$userId) return null;

        $userTenant = $this->model
            ->select(select: 't.uuid')
            ->join('tenants AS t', 't.id = users_tenants.tenant_id', 'left')
            ->where('user_id', $userId)
            ->first();
        return $userTenant->uuid ?? null;
    }
}
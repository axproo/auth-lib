<?php 
namespace Axproo\Auth\Services;

use Axproo\Auth\Models\TenantModel;
use Axproo\Auth\Models\UserTenantModel;

class TenantService
{
    protected TenantModel $model;
    protected UserTenantModel $tenant;

    public function __construct() {
        $this->model = new TenantModel();
        $this->tenant = new UserTenantModel();
    }

    /**
     * Récupération de l'UUID du tenant pour l'utilisateur donné
     *
     * @param integer|null $userId
     * @return void
     */
    public function getUserTenant(?int $userId) : ?string {
        if (!$userId) return null;

        $userTenant = $this->tenant
            ->select('tenants.uuid')
            ->join('tenants', 'tenants.id = users_tenants.tenant_id', 'left')
            ->where('user_id', $userId)
            ->first();
        return $userTenant->uuid ?? null;
    }

    /**
     * Vérifier si un tenant existe et est actif
     *
     * @param string|null $uuid
     * @return void
     */
    public function check(?string $uuid) : ? object {
        if (!$uuid) return null;

        return $this->model
            ->where([
                'uuid' => $uuid,
                'status' => 'active'
            ])->first();
    }

    /**
     * Vérifier que l'utilisateur appartient à ce tenant et que le tenant est actif
     *
     * @param integer|null $userId
     * @param string|null $uuid
     * @return void
     */
    public function verify(?int $userId, ?string $uuid) : bool {
        if (!$userId || !$uuid) return false;

        $tenant = $this->tenant
            ->where([
                'user_id' => $userId,
                'tenant_id' => $uuid,
                'status' => 'active'
            ])->first();
        return $tenant !== null;
    }
}
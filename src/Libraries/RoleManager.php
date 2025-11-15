<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Models\RoleModel;

class RoleManager
{
    protected $model;

    public function __construct() {
        $this->model = new RoleModel();
    }

    public function getRoleById(?int $id = null) {
        $query = $this->model->where('id', $id)->first();
        return $query->role_name ?? null;
    }
}
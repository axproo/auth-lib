<?php 
namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class RuleModel extends Model
{
    protected $table    = 'rules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['role_name','description'];
    protected $returnType = 'Axproo\Auth\Entities\RoleEntity';
    protected $useTimestamps = true;

    public function findByUser(?int $id) : ?string {
        $query = $this->find($id);
        return $query->role_name ?? null;
    }
}
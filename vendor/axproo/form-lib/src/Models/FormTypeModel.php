<?php 
namespace Axproo\Form\Models;

use CodeIgniter\Model;

class FormTypeModel extends Model
{
    protected $table    = 'form_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','description'];
    protected $returnType = 'Axproo\Form\Entities\FormTypeEntity';
    protected $useTimestamps = true;

    public function getTypeByName(?int $id = null) : string {
        $query = $this->find($id);
        return $query->name ?? null;
    }
}
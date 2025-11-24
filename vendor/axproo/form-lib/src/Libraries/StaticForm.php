<?php 
namespace Axproo\Form\Libraries;

class StaticForm extends BaseForm
{
    public function render() : array {
        if (!$this->db->tableExists('form_static')) {
            throw new \Exception(lang('Message.tables.failed.exist', ['table' => 'form_static']));
        }

        $query = $this->db->table('form_static')->select($this->getColumns('form_static'));

        foreach (array_keys(self::$items) as $key) {
            $query->orWhere('name', $key);
        }
        $result = $query->orderBy('sort_order', 'ASC')->get()->getResultArray();
        $fields = $this->builder($result);

        return [
            'url'       => self::$url,
            'path_url'  => uri_string(),
            'dataForm'  => self::$items,
            'fieldData' => $fields
        ];
    }
}
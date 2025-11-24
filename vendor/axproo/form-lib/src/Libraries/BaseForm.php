<?php 
namespace Axproo\Form\Libraries;

use Axproo\Form\Models\FormTypeModel;

class BaseForm
{
    protected $db;
    protected FormTypeModel $types;

    protected static ?string $url = '/';
    protected static array $items = [];
    protected static array $overrides = [];

    private $columns = [
        'name',
        'type',
        'placeholder',
        'required',
        'form_group',
        'attributes',
        'label',
        'isLabel',
        'option_values',
        'provide_table',
        'provide_field',
        'provide_key',
        'provide_cond',
        'is_read_only',
        'legend'
    ];

    public function __construct() {
        $this->db = db_connect();
        $this->types = new FormTypeModel();
    }

    public function getColumns(?string $table = null) : array {
        return array_map(fn($key) => $table. "." .$key, $this->columns ?? []);
    }

    public static function setUrl(?string $url = null) : void {
        if ($url !== null) self::$url = $url;
    }

    public static function setItems(array $items) : void {
        self::$items = $items;
    }

    public static function setOverrides(array $overrides = []) : void {
        self::$overrides = $overrides;
    }

    public function builder(array $schema = []) : array {
        $requestFields = [];

        foreach ($schema as $key => $field) {
            $field['type']          = $this->types->getTypeByName($field['type']);
            $field['attributes']    = json_decode($field['attributes'], true);
            $field['isLabel']       = filter_var($field['isLabel'], FILTER_VALIDATE_BOOLEAN);
            $field['required']      = filter_var($field['required'], FILTER_VALIDATE_BOOLEAN);
            $field['is_read_only']  = filter_var($field['is_read_only'], FILTER_VALIDATE_BOOLEAN);
            $field['option_values'] = $this->selectOptions($field);

            if (isset(self::$overrides[$field['name']]) && is_array(self::$overrides[$field['name']])) {
                $field = array_merge($field, self::$overrides[$field['name']]);
            }
            $requestFields[$key] = $field;
        }
        return $requestFields;
    }

    protected function selectOptions($field) {
        $type = $field['option_values'] ?? null;
        if (empty($type) || $type === null) return;

        switch ($type) {
            case 'value':
                # code...
                break;
            
            default: throw new \Exception(lang('Message.forms.failed.option_values'));
        }
    }
}
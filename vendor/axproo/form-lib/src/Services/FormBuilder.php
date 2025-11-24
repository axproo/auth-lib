<?php 
namespace Axproo\Form\Services;

use Axproo\Form\Libraries\BaseForm;

class FormBuilder
{
    protected $request;

    public function __construct(?string $url = null) {
        BaseForm::setUrl($url);
        $this->request = service('request');
    }

    public function build(array $schema = [], array $overrides = []) : array {
        $items = [];

        foreach ($schema as $key) {
            $items[$key] = $this->request->getVar($key);
        }

        if (!$items) {
            throw new \Exception(lang('Message.forms.failed.field', ['form' => uri_string()]));
        }
        BaseForm::setItems($items);
        BaseForm::setOverrides($overrides);

        return FormManager::render();
    }
}
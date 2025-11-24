<?php 
namespace Axproo\Form\Services;

use Axproo\Form\Libraries\StaticForm;

class FormManager
{
    protected static array $overrides = [];

    protected static ?StaticForm $static = null;

    protected static function init() : void {
        if (self::$static === null) {
            self::$static = new StaticForm();
        }
    }

    public static function setOverrides(array $overrides) : void {
        self::$overrides = $overrides;
    }

    public static function render(string $type = 'static') {
        self::init();

        return match ($type) {
            'static' => self::$static->render(),
            default => throw new \InvalidArgumentException(lang('Message.forms.failed.type', ['type' => $type]))
        };
    }
}
<?php 

namespace Axproo\HelperLib\Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    /**
     * Helpers
     * Prototype:
     *   $helpers = [
     *      'form,
     *   ];
     * @var array
     */
    public $helpers = ['response'];
}
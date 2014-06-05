<?php
namespace Vda\I18n;

class TranslationKey
{
    public $key;
    public $sectionId;
    public $meta;

    public function __construct($key = null, $sectionId = 0, array $meta = array())
    {
        $this->key = $key;
        $this->sectionId = $sectionId;
        $this->meta = $meta;
    }
}

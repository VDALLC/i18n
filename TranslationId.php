<?php
namespace Vda\I18n;

class TranslationId
{
    public $key;
    public $lang;
    public $sectionId;

    public function __construct($key = null, $lang = null, $sectionId = 0)
    {
        $this->key = $key;
        $this->lang = $lang;
        $this->sectionId = $sectionId;
    }
}

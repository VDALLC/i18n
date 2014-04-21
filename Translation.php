<?php
namespace Vda\I18n;

class Translation extends TranslationId
{
    public $translation;
    public $updateTime;

    public function __construct($translation = null, $key = null, $lang = null, $sectionId = 0)
    {
        parent::__construct($key, $lang, $sectionId);

        $this->translation = $translation;
    }
}

<?php
namespace Vda\I18n\Translation;

use Vda\Query\Filter;

class TranslationFilter extends Filter
{
    private $key;
    private $trans;

    public function __construct(DKey $key, DTranslation $trans)
    {
        $this->key = $key;
        $this->trans = $trans;
    }

    public function keyId()
    {
        return $this->key->id;
    }

    public function key()
    {
        return $this->key->key;
    }

    public function sectionId()
    {
        return $this->key->sectionId;
    }

    public function lang()
    {
        return $this->trans->lang;
    }

    public function translation()
    {
        return $this->trans->translation;
    }

    public function updateTime()
    {
        return $this->trans->updateTime;
    }

    /**
     * @return DKey
     */
    public function keyTable()
    {
        return $this->key;
    }

    /**
     * @return DTranslation
     */
    public function translationTable()
    {
        return $this->trans;
    }
}

<?php
namespace Vda\I18n;

class TranslationKey
{
    private $key;
    private $sectionId;
    private $meta;

    public function __construct($key, $sectionId = 0, array $meta = array())
    {
        $this->key = $key;
        $this->sectionId = $sectionId;
        $this->meta = $meta;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getSectionId()
    {
        return $this->sectionId;
    }

    public function setSectionId($sectionId)
    {
        $this->sectionId = $sectionId;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }
}

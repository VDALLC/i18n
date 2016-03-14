<?php
namespace Vda\I18n\Storage;

use Vda\Datasource\KeyValue\IStorage;
use Vda\I18n\TranslationId;
use Vda\I18n\Translation;

class KeyValueStorage implements ITranslationStorage
{
    private $storage;

    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
    }

    public function get(TranslationId $id)
    {
        $str = $this->storage->get($this->buildKey($id));

        return $str === false ? null : $str;
    }

    public function set(Translation $t)
    {
        $this->storage->set($this->buildKey($t), $t->translation);
    }

    public function delete(TranslationId $id)
    {
        $this->storage->delete($this->buildKey($id));
    }

    public function batchSet(array $translations)
    {
        foreach ($translations as $t) {
            $this->set($t);
        }
    }

    public function batchDelete(array $translationIds)
    {
        foreach ($translationIds as $id) {
            $this->delete($id);
        }
    }

    private function buildKey(TranslationId $id)
    {
        return "{$id->lang}/{$id->sectionId}/{$id->key}";
    }
}

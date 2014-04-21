<?php
namespace Vda\I18n\Storage;

use Vda\Datasource\KeyValue\IStorage;
use Vda\I18n\TranslationId;
use Vda\I18n\Translation;

class BucketStorage implements ITranslationStorage
{
    private $storage;

    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
    }

    public function get(TranslationId $id)
    {
        $bucket = $this->storage->get($this->getBucketId($id));

        if (!empty($bucket) && isset($bucket[$id->key])) {
            return $bucket[$id->key];
        }

        return null;
    }

    public function set(Translation $translation)
    {
        $bucketId = $this->getBucketId($translation);
        $bucket = $this->storage->get($bucketId);

        if (empty($bucket)) {
            $bucket = array();
        }

        $bucket[$translation->key] = $translation->translation;

        $this->storage->set($bucketId, $bucket);
    }

    public function delete(TranslationId $id)
    {
        $bucketId = $this->getBucketId($id);
        $bucket = $this->storage->get($bucketId);

        if (!empty($bucket) && isset($bucket[$key])) {
            unset($bucket[$id->key]);
            $this->storage->set($bucketId, $bucket);
        }
    }

    public function batchSet(array $translations)
    {
        $buckets = array();
        foreach ($translations as $t) {
            $buckets[$this->getBucketId($t)][$t->key] = $t->translation;
        }

        foreach ($buckets as $bucketId => $update) {
            $bucket = $this->storage->get($bucketId);

            if (empty($bucket)) {
                $this->storage->set($bucketId, $update);
            } else {
                $this->storage->set($bucketId, array_merge($bucket, $update));
            }
        }
    }

    public function batchDelete(array $translationIds)
    {
        $buckets = array();
        foreach ($translations as $t) {
            $buckets[$this->getBucketId($t)][$t->key] = true;
        }

        foreach ($buckets as $bucketId => $update) {
            $bucket = $this->storage->get($bucketId);

            if (!empty($bucket)) {
                $bucket = array_diff_key($bucket, $update);
            }

            if (!empty($bucket)) {
                $this->storage->set($bucketId, $bucket);
            } else {
                $this->storage->delete($bucketId);
            }
        }
    }

    private function getBucketId(TranslationId $id)
    {
        $p1 = strpos($id->key, '.');
        if ($p1 > 0) {
            $p2 = strpos($id->key, '.', $p1 + 1);
        }

        if (!empty($p2)) {
            $k = substr($id->key, 0, $p2);
            $k[$p1] = '/';
        } else {
            $k = 'short-key';
        }

        return "{$id->lang}/{$id->sectionId}/{$k}";
    }
}

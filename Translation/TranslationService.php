<?php
namespace Vda\I18n\Translation;

use Vda\Datasource\IRepository;
use Vda\Query\Select;
use Vda\Query\Update;
use Vda\Query\Insert;
use Vda\I18n\TranslationId;
use Vda\Query\Delete;
use Vda\I18n\I18nException;

class TranslationService implements ITranslationService
{
    private $repository;
    private $key;
    private $trans;

    public function __construct(IRepository $repository, $tablePrefix = '')
    {
        $this->repository = $repository;
        $this->key = new DKey('k', $tablePrefix);
        $this->trans = new DTranslation('t', $tablePrefix);
    }

    public function getTranslation(TranslationId $id)
    {
        $f = $this->createTranslationFilter();
        $f->where(
            $f->key()->eq($id->key),
            $f->sectionId()->eq($id->sectionId),
            $f->lang()->eq($id->lang)
        );
        $f->orderBy(
            $f->key()->asc(),
            $f->sectionId()->asc()
        );
        $f->limit(1);

        return $this->repository->select(
            $this->createTranslationQuery($f)->singleRow()
        );
    }

    public function setTranslation(TranslationId $id, $translation)
    {
        $keyId = $this->upsertKey($id->key, $id->sectionId);

        $this->upsertTranslation($keyId, $id->lang, $translation);
    }

    public function findTranslations(TranslationFilter $filter)
    {
        return $this->repository->select($this->createTranslationQuery($filter));
    }

    public function deleteTranslation(TranslationId $id)
    {
        $key = $this->loadKey($id->key, $id->sectionId);

        if (empty($key)) {
            throw new I18nException("Translation key ({$id->key}, {$id->sectionId}) not found");
        }

        return $this->repository->delete(
            Delete::delete()
                ->from($this->trans)
                ->where(
                    $this->trans->keyId->eq($key['id']),
                    $this->trans->lang->eq($id->lang)
                )
        ) > 0;
    }

    public function createTranslationFilter()
    {
        return new TranslationFilter($this->key, $this->trans);
    }

    public function findKeys(TranslationFilter $f)
    {
        return $this->repository->select(
            Select::select($f->keyTable()->key, $f->keyTable()->sectionId)
                ->from($f->keyTable())
                ->join($f->translationTable(), $f->keyTable()->_fkTranslation)
                ->filter($f)
                ->groupBy($f->key())
                ->map('\Vda\I18n\TranslationKey')
        );
    }

    public function getKeyMeta($key, $sectionId)
    {
        $key = $this->loadKey($key, $sectionId);

        if (!empty($key['meta'])) {
            return $key['meta'];
        }

        return null;
    }

    public function setKeyMeta($key, $sectionId, array $meta)
    {
        $this->upsertKey($key, $sectionId, $meta);
    }

    public function deleteKey($key, $sectionId)
    {
        $key = $this->loadKey($key, $sectionId);

        if (empty($key)) {
            throw new I18nException("Tranlsation key ({$id->key}, {$id->sectionId}) not found");
        }

        $this->repository->delete(
            Delete::delete()
                ->from($this->trans)
                ->where($this->trans->keyId->eq($key['id']))
        );

        return $this->repository->delete(
            Delete::delete()
                ->from($this->key)
                ->where($this->key->id->eq($key['id']))
        );
    }

    private function createTranslationQuery(TranslationFilter $filter)
    {
        $k = $filter->keyTable();
        $t = $filter->translationTable();

        return Select::select($k->key, $k->sectionId, $t->lang, $t->translation, $t->updateTime)
            ->from($k)
            ->join($t, $k->_fkTranslation)
            ->filter($filter)
            ->map('\Vda\I18n\Translation');
    }

    private function upsertKey($key, $sectionId, array $meta = null)
    {
        $k = $this->loadKey($key, $sectionId);

        if (empty($k)) {
            $this->repository->insert(
                Insert::insert()
                    ->into($this->key)
                    ->set($this->key->key, $key)
                    ->set($this->key->sectionId, $sectionId)
                    ->set($this->key->meta, is_null($meta) ? '' : json_encode($meta))
            );

            $keyId = $this->repository->getLastInsertId();
        } else  {
            $keyId = $k['id'];
            if (!is_null($meta)) {
                $this->repository->update(
                    Update::update($this->key)
                        ->set($this->key->meta, json_encode($meta))
                        ->where($this->key->id->eq($keyId))
                );
            }
        }

        return $keyId;
    }

    private function upsertTranslation($keyId, $lang, $translation)
    {
        $time = time();

        $affected = $this->repository->update(
            Update::update($this->trans)
                ->set($this->trans->translation, $translation)
                ->set($this->trans->updateTime, $time)
                ->where(
                    $this->trans->keyId->eq($keyId),
                    $this->trans->lang->eq($lang)
                )
        );

        if (empty($affected)) {
            $this->repository->insert(
                Insert::insert()
                    ->into($this->trans)
                    ->set($this->trans->keyId, $keyId)
                    ->set($this->trans->lang, $lang)
                    ->set($this->trans->translation, $translation)
                    ->set($this->trans->updateTime, $time)
            );
        }
    }

    private function loadKey($key, $sectionId)
    {
        $key = $this->repository->select(
            Select::select($this->key->id, $this->key->meta)
                ->from($this->key)
                ->where(
                    $this->key->key->eq($key),
                    $this->key->sectionId->eq($sectionId)
                )
                ->singleRow()
        );

        if (!empty($key['meta'])) {
            $key['meta'] = json_decode($key['meta'], true);
        }

        return $key;
    }
}

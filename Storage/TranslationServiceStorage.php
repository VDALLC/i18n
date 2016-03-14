<?php
namespace Vda\I18n\Storage;

use Vda\I18n\TranslationId;
use Vda\I18n\Translation;
use Vda\I18n\Translation\ITranslationService;

class TranslationServiceStorage implements ITranslationStorage
{
    /**
     * @var ITranslationService
     */
    private $ts;

    public function __construct(ITranslationService $ts)
    {
        $this->ts = $ts;
    }

    public function get(TranslationId $id)
    {
        $t = $this->ts->getTranslation($id);

        return empty($t) ? null : $t->translation;
    }

    public function set(Translation $t)
    {
        $this->ts->setTranslation($t, $t->translation);
    }

    public function delete(TranslationId $id)
    {
        $this->ts->deleteTranslation($id);
    }

    public function batchSet(array $translations)
    {
        foreach ($translations as $t) {
            $this->ts->setTranslation($t, $t->translation);
        }
    }

    public function batchDelete(array $translationIds)
    {
        foreach ($translationIds as $id) {
            $this->ts->deleteTranslation($id);
        }
    }
}

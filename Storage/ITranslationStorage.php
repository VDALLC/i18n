<?php
namespace Vda\I18n\Storage;

use Vda\I18n\Translation;
use Vda\I18n\TranslationId;

interface ITranslationStorage
{
    public function get(TranslationId $translationId);

    public function set(Translation $translation);

    public function delete(Translationid $translationId);

    /**
     * Set translations in a batch mode
     *
     * @param Translation[] $translations
     */
    public function batchSet(array $translations);

    /**
     * Set translations in a batch mode
     *
     * @param TranslationId[] $translations
     */
    public function batchDelete(array $translationIds);
}

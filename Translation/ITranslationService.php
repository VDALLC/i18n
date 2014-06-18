<?php
namespace Vda\I18n\Translation;

use Vda\I18n\Translation;
use Vda\I18n\TranslationId;

interface ITranslationService
{
    /**
     * @param TranslationId $id
     * @return Translation
     */
    public function getTranslation(TranslationId $id);

    /**
     * @param TranslationId $id
     * @param string $translation
     */
    public function setTranslation(TranslationId $id, $translation);

    /**
     * @param TranslationFilter $filter
     * @return Translation[]
     */
    public function findTranslations(TranslationFilter $filter);

    /**
     * @return TranslationFilter
     */
    public function createTranslationFilter();

    public function deleteTranslation(TranslationId $id);

    /**
     * @return \Vda\I18n\TranslationKey[]
     * */
    public function findKeys(TranslationFilter $filter);

    public function getKeyMeta($key, $sectionId);

    public function setKeyMeta($key, $sectionId, array $meta);

    public function deleteKey($key, $sectionId);
}

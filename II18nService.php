<?php
namespace Vda\I18n;

interface II18nService
{
    public function translate(TranslationId $id, array $params = array());

    public function isExist(TranslationId $id);
}

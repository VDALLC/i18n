<?php
namespace Vda\I18n\Audit;

use Vda\I18n\TranslationId;

interface IAuditor
{
    const KEY_USE         = 0;
    const KEY_MISS        = 1;
    const KEY_INVALID     = 2;
    const PLURALIZER_MISS = 3;

    public function log(TranslationId $id, $event);
}

<?php
namespace Vda\I18n\Audit;

use Vda\I18n\TranslationId;

class NullAuditor implements IAuditor
{
    public function log(TranslationId $id, $event)
    {
    }
}

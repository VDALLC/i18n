<?php
namespace Vda\I18n\Pluralization;

class English implements IPluralizer
{
    private static $forms = array(
        IPluralizer::FORM_ONE,
        IPluralizer::FORM_OTHER,
    );

    public function getForm($number)
    {
        if ($number == 1) {
            return IPluralizer::FORM_ONE;
        } else {
            return IPluralizer::FORM_OTHER;
        }
    }

    public function getFormIdx($number)
    {
        return array_search($this->getForm($number), self::$forms);
    }
}

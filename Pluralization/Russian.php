<?php
namespace Vda\I18n\Pluralization;

class Russian implements IPluralizer
{
    private static $forms = array(
        IPluralizer::FORM_ONE,
        IPluralizer::FORM_FEW,
        IPluralizer::FORM_MANY,
        IPluralizer::FORM_OTHER,
    );

    public function getForm($number)
    {
        $mod10 = $number % 10;

        if ($mod10 != fmod($number, 10)) {
            return IPluralizer::FORM_OTHER;
        }

        $mod100 = $number % 100;

        if ($mod10 == 1 && $mod100 != 11) {
            return IPluralizer::FORM_ONE;
        } else if (($mod10 >= 2 && $mod10 <= 4) && ($mod100 < 12 || $mod100 > 14)) {
            return IPluralizer::FORM_FEW;
        } elseif ($mod10 == 0 || ($mod10 >= 5 && $mod10 <= 9) || ($mod100 >= 11 && $mod100 <= 14)) {
            return IPluralizer::FORM_MANY;
        } else {
            return IPluralizer::FORM_OTHER;
        }
    }

    public function getFormIdx($number)
    {
        return array_search($this->getForm($number), self::$forms);
    }
}

<?php
namespace Vda\I18n\Pluralization;

interface IPluralizer
{
    const FORM_ZERO  = 'zero';
    const FORM_ONE   = 'one';
    const FORM_TWO   = 'two';
    const FORM_FEW   = 'few';
    const FORM_MANY  = 'many';
    const FORM_OTHER = 'other';

    /**
     * Gets pluaral form of given number
     *
     * @param numeric $number
     * @return string on of ['zero','one','two','few','many','other'] depending on number and language
     * @see http://www.unicode.org/cldr/charts/latest/supplemental/language_plural_rules.html
     */
    public function getForm($number);
}

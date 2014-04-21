<?php
namespace Vda\I18n\Pluralization;

interface IPluralizerFactory
{
    public function getPluralizer($lang);
    public function getDefaultPluralizer();
}

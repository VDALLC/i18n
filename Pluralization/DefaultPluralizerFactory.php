<?php
namespace Vda\I18n\Pluralization;

use Vda\I18n\I18nException;

class DefaultPluralizerFactory implements IPluralizerFactory
{
    private $defaultLang;
    private $pluralizers;

    public function __construct($defaultLang = 'en')
    {
        $this->defaultLang = $defaultLang;

        $this->pluralizers = array(
            'ru' => '\Vda\I18n\Pluralization\Russian',
            'en' => '\Vda\I18n\Pluralization\English',
        );

        if (empty($this->pluralizers[$defaultLang])) {
            throw new I18nException(
                "Unable to lookup pluralizer for fallback language '{$defaultLang}'"
            );
        }
    }

    public function getPluralizer($lang)
    {
        if (empty($this->pluralizers[$lang])) {
            return null;
        }

        return new $this->pluralizers[$lang];
    }

    public function getDefaultPluralizer()
    {
        return new $this->pluralizers[$this->defaultLang];
    }
}

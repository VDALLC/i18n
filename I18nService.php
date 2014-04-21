<?php
namespace Vda\I18n;

use Vda\I18n\Audit\IAuditor;
use Vda\I18n\Pluralization\IPluralizerFactory;
use Vda\I18n\Storage\ITranslationStorage;
use Vda\I18n\Pluralization\IPluralizer;

class I18nService implements II18nService
{
    private $storage;
    private $pluralizerFactory;
    private $auditor;

    public function __construct(
        ITranslationStorage $storage,
        IPluralizerFactory $pluralizerFactory,
        IAuditor $auditor
    ) {
        $this->storage = $storage;
        $this->pluralizerFactory = $pluralizerFactory;
        $this->auditor = $auditor;

        $this->pluralizers = array();
    }

    public function translate(TranslationId $id, array $params = array())
    {
        if (!$this->checkKey($id->key)) {
            $this->auditor->log($id, IAuditor::KEY_INVALID);
            return '';
        }

        $str = $this->storage->get($id);

        if ($str === null) {
            $this->auditor->log($id, IAuditor::KEY_MISS);
            return $id->key;
        }

        $this->auditor->log($id, IAuditor::KEY_USE);

        return empty($params) ? $str : $this->interpolate($id, $str, $params);
    }

    public function isExist(TranslationId $id)
    {
        return $this->storage->get($id) === null;
    }

    private function checkKey($key)
    {
        return preg_match('!^[a-z0-9.#={}\[\]\-]+$!', $key);
    }

    private function interpolate($id, $str, array $params)
    {
        $search  = array();
        $replace = array();

        foreach ($params as $k => $v) {
            $search[] = '%{' . $k . '}';
            $replace[] = $v;
        }

        if (preg_match_all(
            '!%{(?<count>\w+)?(?:\s*,\s*(?<sex>\w+))?\s*->\s*(?<text>[^}]*)}!',
            $str,
            $m,
            PREG_SET_ORDER
        )) {
            $pluralizer = $this->getPluralizer($id);

            foreach ($m as $op) {
                $search[] = $op[0];
                $replace[] = $this->resolveOperator($id, $pluralizer, $op, $params);
            }
        }

        return str_replace($search, $replace, $str);
    }

    /**
     * @param TranslationId $id
     */
    private function getPluralizer($id)
    {
        if (empty($this->pluralizers[$id->lang])) {
            $this->pluralizers[$id->lang] = $this->pluralizerFactory->getPluralizer($id->lang);
        }

        if (empty($this->pluralizers[$id->lang])) {
            $this->auditor->log($id, IAuditor::PLURALIZER_MISS);
            $this->pluralizers[$id->lang] = $this->pluralizerFactory->getDefaultPluralizer();
        }

        return $this->pluralizers[$id->lang];
    }

    /**
     * @param Translation $id
     * @param IPluralizer $pluralizer
     * @param array $op
     * @param array $params
     * @return string
     */
    private function resolveOperator($id, $pluralizer, $op, $params)
    {
        $byForm = str_getcsv($op['text']);

        if (empty($op['count'])) {
            $pluralIdx = 0;
        } elseif (!array_key_exists($op['count'], $params)) {
            $this->auditor->log($id, IAuditor::KEYWORD_MISS);
            $pluralIdx = 0;
        } else {
            $pluralIdx = $pluralizer->getFormIdx($params[$op['count']]);

            if (!array_key_exists($pluralIdx, $byForm)) {
                //TODO Audit missing plural variant
                $pluralIdx = 0;
            }
        }

        $bySex = str_getcsv($byForm[$pluralIdx], '|');

        if (empty($op['sex'])) {
            $sex = 0;
        } elseif (!array_key_exists($op['sex'], $params)) {
            //TODO Audit missing sex parameter
            $sex = 0;
        } else {
            $sex = $params[$op['sex']];

            if (!array_key_exists($sex, $bySex)) {
                $sex = 0;
            }
        }

        return $bySex[$sex];
    }
}

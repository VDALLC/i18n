<?php
namespace Vda\I18n\Translation;

use Vda\Query\Table;
use Vda\Query\Field;
use Vda\Util\Type;
use Vda\Query\Key\PrimaryKey;
use Vda\Query\Key\ManyToOne;

class DTranslation extends Table
{
    public $keyId;
    public $lang;
    public $translation;
    public $updateTime;

    public $_primaryKey;
    public $_fkKey;

    public function __construct($alias, $tablePrefix = '')
    {
        $this->keyId = new Field(Type::INTEGER);
        $this->lang = new Field(Type::STRING);
        $this->translation = new Field(Type::STRING);
        $this->updateTime = new Field(Type::INTEGER);

        $this->_primaryKey = new PrimaryKey('keyId', 'lang');

        $this->_fkKey = new ManyToOne(
            '\Vda\I18n\Translation\Dkey',
            array('keyId' => 'id')
        );

        parent::__construct($tablePrefix . 'i18n_translations', $alias);
    }
}

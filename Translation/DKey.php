<?php
namespace Vda\I18n\Translation;

use Vda\Query\Table;
use Vda\Query\Field;
use Vda\Util\Type;
use Vda\Query\Key\PrimaryKey;
use Vda\Query\Key\ManyToOne;
use Vda\Query\Key\OneToMany;

class DKey extends Table
{
    public $id;
    public $key;
    public $sectionId;
    public $meta;

    public $_primaryKey;
    public $_fkTranslation;

    public function __construct($alias, $tablePrefix = '')
    {
        $this->id = new Field(Type::INTEGER);
        $this->key = new Field(Type::STRING);
        $this->sectionId = new Field(Type::INTEGER);
        $this->meta = new Field(Type::STRING);

        $this->_primaryKey = new PrimaryKey('id');

        $this->_fkTranslation = new OneToMany(
            '\Vda\I18n\Translation\DTranslation',
            array('id' => 'keyId')
        );

        parent::__construct($tablePrefix . 'i18n_keys', $alias);
    }
}

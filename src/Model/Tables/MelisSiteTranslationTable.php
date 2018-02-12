<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Join;

class MelisSiteTranslationTable extends MelisGenericTable
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mst_id';
    }

    public function getSiteTranslationTextByKey($key, $locale)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('mstt'=>'melis_site_translation_text'), 'mstt.mstt_mst_id = melis_site_translation.mst_id');
        $select->join(array('lang'=>'melis_cms_lang'), 'mstt.mstt_lang_id = lang.lang_cms_id');
        $select->where->equalTo("melis_site_translation.mst_key", $key);

        if(!is_null($locale) && !empty($locale)){
            $select->where->equalTo("lang.lang_cms_locale", $locale);
        }

        $data = $this->tableGateway->selectWith($select);
        return $data;
    }

    public function getSiteTranslation($key, $locale)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('mstt'=>'melis_site_translation_text'), 'mstt.mstt_mst_id = melis_site_translation.mst_id');
        $select->join(array('lang'=>'melis_cms_lang'), 'mstt.mstt_lang_id = lang.lang_cms_id');

        if(!is_null($key) && !empty($locale)){
            $select->where->equalTo("melis_site_translation.mst_key", $key);
        }

        if(!is_null($locale) && !empty($locale)){
            $select->where->equalTo("lang.lang_cms_locale", $locale);
        }

        $data = $this->tableGateway->selectWith($select);
        return $data;
    }
}
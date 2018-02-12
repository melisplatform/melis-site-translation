<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Model\Tables\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;

use MelisSiteTranslation\Model\MelisSiteTranslation;
use MelisSiteTranslation\Model\Tables\MelisSiteTranslationTable;

class MelisSiteTranslationTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
	    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSiteTranslation());
    	$tableGateway = new TableGateway('melis_site_translation', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisSiteTranslationTable($tableGateway);
	}

}
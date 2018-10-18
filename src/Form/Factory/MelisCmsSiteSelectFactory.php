<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Form\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCore\Form\Factory\MelisSelectFactory;

/**
 * This class creates a select box for melis languages
 *
 */
class MelisCmsSiteSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();
        $table = $serviceManager->get('MelisEngineTableSite');
        $cmsSiteData = $table->fetchAll()->toArray();

        $valueoptions = array();

        foreach($cmsSiteData as $lang => $val) {
            $valueoptions[$val['site_id']] = $val['site_name'];
        }

        return $valueoptions;
    }

}
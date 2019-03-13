<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Listener;


use MelisCore\Listener\MelisCoreGeneralListener;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class MelisSiteTranslationFlashMessengerListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            'MelisSiteTranslation',
            [
                'melissitetranslation_save_translation_end',
                'melissitetranslation_del_translation_end'
            ],
            function ($e) {
                $params = $e->getParams();
                $e->getTarget()->forward()->dispatch(
                    'MelisCore\Controller\MelisFlashMessenger',
                    array_merge(
                        ['action' => 'log'],
                        $params
                    )
                )->getVariables();
            },
            -1000
        );
        $this->listeners[] = $callBackHandler;
    }
}
<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

return array(
    'router' => array(
        'routes' => array(
            'melis-backoffice' => array(
                'child_routes' => array(
                    'application-MelisSiteTranslation' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'MelisSiteTranslation',
                            'defaults' => array(
                                '__NAMESPACE__' => 'MelisSiteTranslation\Controller',
                                'controller'    => 'Index',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:controller[/:action][/:id]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'         => '[0-9]+',
                                    ),
                                    'defaults' => array(
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'en_EN',
    ),
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'MelisSiteTranslationTable' => 'MelisSiteTranslation\Model\Tables\MelisSiteTranslationTable',
            'MelisSiteTranslationTextTable' => 'MelisSiteTranslation\Model\Tables\MelisSiteTranslationTextTable',
        ),
        'factories' => array(
            'MelisSiteTranslationService' => 'MelisSiteTranslation\Service\Factory\MelisSiteTranslationServiceFactory',
            'MelisSiteTranslation\Model\Tables\MelisSiteTranslationTable' => 'MelisSiteTranslation\Model\Tables\Factory\MelisSiteTranslationTableFactory',
            'MelisSiteTranslation\Model\Tables\MelisSiteTranslationTextTable' => 'MelisSiteTranslation\Model\Tables\Factory\MelisSiteTranslationTextTableFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'MelisSiteTranslation\Controller\MelisSiteTranslation' => 'MelisSiteTranslation\Controller\MelisSiteTranslationController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/default.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);

<?php

return array(
    'plugins' => array(
        'meliscore' => array(
            'interface' => array(
                'meliscore_leftmenu' => array(
                    'interface' => array(
                        'meliscore_toolstree' =>  array(
                            'interface' => array(
                                'meliscms_tools_section' => array(
                                    'interface' => array(
                                        'meliscms_site_translation' => array(
                                            'conf' => array(
                                                'type' => '/melis_site_translation/interface/melis_site_translation_tool',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        )
                    ),
                ),
            ),
        ),
        'melis_site_translation' => array(
            'conf' => array(
                'id' => 'id_melis_translation',
                'name' => 'tr_melis_site_translation_name',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisSiteTranslation/js/site-translation.js',
                ),
                'css' => array(

                ),
                /**
                 * the "build" configuration compiles all assets into one file to make
                 * lesser requests
                 */
                'build' => [
                    // lists of assets that will be loaded in the layout
                    'css' => [
                    ],
                    'js' => [
                        '/MelisSiteTranslation/build/js/bundle.js',
                    ]
                ]
            ),
            'datas' => array(
            ),
            'interface' => array(
                'melis_site_translation_tool' =>  array(
                    'conf' => array(
                        'id' => 'id_melis_site_translation_tool',
                        'name' => 'tr_melis_site_translation_name',
                        'melisKey' => 'melis_site_translation_tool',
                        'rightsDisplay' => 'referencesonly',
                        'icon' => 'fa-language',
                    ),
                    'forward' => array(
                        'module' => 'MelisSiteTranslation',
                        'controller' => 'MelisSiteTranslation',
                        'action' => 'render-melis-site-translation',
                        'jscallback' => '',
                        'jsdatas' => array()
                    ),
                    'interface' => array(
                        'melis_site_translation_tool_content' => array(
                            'conf' => array(
                                'id' => 'id_melis_site_translation_tool_content',
                                'melisKey' => 'melis_site_translation_tool_content',
                                'name' => 'tr_melis_site_translation_name',
                                'icon' => 'fa-wrench',
                            ),
                            'forward' => array(
                                'module' => 'MelisSiteTranslation',
                                'controller' => 'MelisSiteTranslation',
                                'action' => 'render-melis-site-translation-content',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                        ),
                    ),
                ),
                /*'melis_site_translation_tool_modal_add_site_translation' => array(
                    'conf' => array(
                        'id' => 'id_melis_site_translation_tool_modal_add_site_translation',
                        'melisKey' => 'melis_site_translation_tool_modal_add_site_translation',
                        'name' => 'tr_melis_site_translation_add_translation',
                        'icon' => 'fa fa-plus',
                    ),
                    'forward' => array(
                        'module' => 'MelisSiteTranslation',
                        'controller' => 'MelisSiteTranslation',
                        'action' => 'render-melis-site-translation-modal-add-site-translation',
                        'jscallback' => '',
                        'jsdatas' => array()
                    ),
                ),*/
                'melis_site_translation_tool_modal_edit_site_translation' => array(
                    'conf' => array(
                        'id' => 'id_melis_site_translation_tool_modal_edit_site_translation',
                        'melisKey' => 'melis_site_translation_tool_modal_edit_site_translation',
                        'name' => 'tr_melis_site_translation_edit_translation',
                        'icon' => 'fa fa-pencil',
                    ),
                    'forward' => array(
                        'module' => 'MelisSiteTranslation',
                        'controller' => 'MelisSiteTranslation',
                        'action' => 'render-melis-site-translation-modal-edit-site-translation',
                        'jscallback' => '',
                        'jsdatas' => array()
                    ),
                ),
            ),
        ),
    ),
);
<?php

return array(
    'plugins' => array(
        'melis_site_translation' => array(
            'tools' => array(
                'melis_site_translation_tool' => array(
                    'conf' => array(
                        'title' => 'Melis Site Translation',
                        'id' => 'id_melis_site_translation',
                    ),
                    'table' => array(
                        // table ID
                        'target' => '#tableMelisSiteTranslation',
                        'ajaxUrl' => '/melis/MelisSiteTranslation/MelisSiteTranslation/getTranslation',
                        'dataFunction' => 'initSiteTranslationSiteList',
                        'ajaxCallback' => 'initSiteTranslationTable()',
                        'filters' => array(
                            'left' => array(
                                'mt-tr-limit' => array(
                                    'module' => 'MelisSiteTranslation',
                                    'controller' => 'MelisSiteTranslation',
                                    'action' => 'render-melis-site-translation-content-filters-limit'
                                ),
                                'mt-tr-sites' => array(
                                    'module' => 'MelisSiteTranslation',
                                    'controller' => 'MelisSiteTranslation',
                                    'action' => 'render-melis-site-translation-filters-sites'
                                )
                            ),
                            'center' => array(
                                'mt-tr-search' => array(
                                    'module' => 'MelisSiteTranslation',
                                    'controller' => 'MelisSiteTranslation',
                                    'action' => 'render-melis-site-translation-content-filters-search'
                                ),
                            ),
                            'right' => array(
                                'mt-tr-refresh' => array(
                                    'module' => 'MelisSiteTranslation',
                                    'controller' => 'MelisSiteTranslation',
                                    'action' => 'render-melis-site-translation-content-filters-refresh'
                                ),
                            ),
                        ),
                        'columns' => array(
                            'mst_key' => array(
                                'text' => 'tr_melis_site_translation_key_col',
                                'sortable' => false,
                            ),
                            'module' => array(
                                'text' => 'tr_melis_site_translation_module_col',
                                'sortable' => false,
                            ),
                            'mstt_text' => array(
                                'text' => 'tr_melis_site_translation_text_col',
                                'sortable' => false,
                            )
                        ),

                        // define what columns can be used in searching
                        'searchables' => array('mst_key', 'module', 'mstt_text'),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisSiteTranslation',
                                'controller' => 'MelisSiteTranslation',
                                'action' => 'render-melis-site-translation-action-edit',
                            ),
                            'delete' => array(
                                'module' => 'MelisSiteTranslation',
                                'controller' => 'MelisSiteTranslation',
                                'action' => 'render-melis-site-translation-action-delete',
                            ),
                        )
                    ),
                    'forms' => array(
                        'melissitetranslation_form' => array(
                            'attributes' => array(
                                'name' => 'melissitetranslationform',
                                'id' => 'site-translation-form',
                                'method' => 'POST',
                                'action' => '',
                            ),
                            'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                            'elements' => array(
                                array(
                                    'spec' => array(
                                        'name' => 'mstt_site_id',
                                        'type' => 'MeliSiteTranslationsCmsSiteSelectFactory',
                                        'options' => array(
                                            'label' => 'tr_melis_site_translation_select_language_site',
                                            'empty_option' => 'tr_meliscms_form_common_Choose',
                                            'tooltip' => 'tr_melis_site_translation_select_site_tooltip',
                                            'disable_inarray_validator' => true,
                                        ),
                                        'attributes' => array(
                                            'id' => 'mstt_site_id',
                                            'value' => '',
                                            'required' => 'required',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'mstt_lang_id',
                                        'type' => 'MelisCmsLanguageSelect',
                                        'options' => array(
                                            'label' => 'tr_melis_site_translation_select_language',
                                            'empty_option' => 'tr_meliscms_form_common_Choose',
                                            'tooltip' => 'tr_melis_site_translation_select_language_tooltip',
                                            'disable_inarray_validator' => true,
                                        ),
                                        'attributes' => array(
                                            'id' => 'mstt_lang_id',
                                            'value' => '',
                                            'required' => 'required',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'mst_key',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_melis_site_translation_key',
                                            'tooltip' => 'tr_melis_site_translation_key_tooltip',
                                        ),
                                        'attributes' => array(
                                            'id' => 'mst_key',
                                            'value' => '',
                                            'required' => 'required',
                                            'readonly' => 'readonly',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'mstt_text',
                                        'type' => 'textarea',
                                        'options' => array(
                                            'label' => 'tr_melis_site_translation_text',
                                            'tooltip' => 'tr_melis_site_translation_text_tooltip',
                                        ),
                                        'attributes' => array(
                                            'id' => 'mstt_text',
                                            'value' => '',
                                            'required' => 'required',
                                            'class' => 'form-control',
                                        ),
                                    ),
                                ),
                            ),
                            'input_filter' => array(
                                'mstt_site_id' => array(
                                    'name'     => 'mstt_site_id',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_melis_site_translation_empty_site',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                                'mstt_lang_id' => array(
                                    'name'     => 'mstt_lang_id',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_melis_site_translation_empty_language',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                                'mst_key' => array(
                                    'name'     => 'mst_key',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_melis_site_translation_empty_key',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                                'mstt_text' => array(
                                    'name'     => 'mstt_text',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_melis_site_translation_empty_text',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Service;


use MelisEngine\Service\MelisEngineGeneralService;
use Zend\Session\Container;

class MelisSiteTranslationService extends MelisEngineGeneralService
{

    /**
     * Function to delete translation
     *
     * @param array $data - consisting the id of both key and text
     * @return mixed
     */
    public function deleteTranslation($data = array())
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_delete_translation_start', $arrayParameters);

        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');//get db adapter
        $con = $db->getDriver()->getConnection();//get db driver connection
        $con->beginTransaction();//begin transaction
        try {
            $this->deleteTranslationKeyById($data['mst_id']);
            $this->deleteTranslationTextById($data['mstt_id']);
            $arrayParameters['results'] = true;
            $con->commit();
        }catch(\Exception $ex){
            $con->rollback();
            $arrayParameters['results'] = false;
        }

        $arrayParameters = $this->sendEvent('melis_site_translation_delete_translation_end', $arrayParameters);
        return $arrayParameters['results'];
    }

    /***
     * Function to delete key
     *
     * @param null $id
     * @return mixed
     */
    public function deleteTranslationKeyById($id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_delete_translation_key_start', $arrayParameters);

        $mstTable = $this->getServiceLocator()->get('MelisSiteTranslationTable');
        $res = $mstTable->deleteById($arrayParameters['id']);

        $arrayParameters['results'] = $res;
        $arrayParameters = $this->sendEvent('melis_site_translation_delete_translation_key_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to delete text
     *
     * @param null $id
     * @return mixed
     */
    public function deleteTranslationTextById($id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_delete_translation_text_start', $arrayParameters);

        $msttTable = $this->getServiceLocator()->get('MelisSiteTranslationTextTable');
        $res = $msttTable->deleteById($arrayParameters['id']);

        $arrayParameters['results'] = $res;
        $arrayParameters = $this->sendEvent('melis_site_translation_delete_translation_text_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to save translation
     *
     * @param array $data
     * @return mixed
     */
    public function saveTranslation($data = array())
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_save_translation_start', $arrayParameters);

        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');//get db adapter
        $con = $db->getDriver()->getConnection();//get db driver connection
        $con->beginTransaction();//begin transaction
        try {
            //check whether we insert or update the record by checking the value of id
            if($data['mst_id'] != 0){
                $mstRes = $this->saveTranslationKey($data['mst_data'], $data['mst_id']);
                if ($mstRes) {
                    $msttRes = $this->saveTranslationText($data['mstt_data'], $data['mstt_id']);
                    if ($msttRes) {
                        $arrayParameters['results'] = true;
                    }
                } else {
                    $arrayParameters['results'] = false;
                }
            }else {
                $mstRes = $this->saveTranslationKey($data['mst_data']);
                if ($mstRes) {
                    $data['mstt_data']['mstt_mst_id'] = $mstRes;
                    $msttRes = $this->saveTranslationText($data['mstt_data']);
                    if ($msttRes) {
                        $arrayParameters['results'] = true;
                    }
                } else {
                    $arrayParameters['results'] = false;
                }
            }
            $con->commit();
        }catch(\Exception $ex){
            $con->rollback();
            $arrayParameters['results'] = null;
        }
        $arrayParameters = $this->sendEvent('melis_site_translation_save_translation_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to save translation key
     *
     * @param array $data
     * @param null $id
     * @return mixed
     */
    public function saveTranslationKey($data = array(), $id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters['results'] = null;
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_save_translation_key_start', $arrayParameters);

        $mstTable = $this->getServiceLocator()->get('MelisSiteTranslationTable');

        if (!is_null($data) && !empty($data) && sizeof($data) > 0) {
            //check whether we update or we insert the record
            if(!is_null($id) && !empty($id) && $id != 0){
                $mstRes = $mstTable->save($arrayParameters['data'], $id);
            }else {
                $mstRes = $mstTable->save($arrayParameters['data']);
            }

            if ($mstRes) {
                $arrayParameters['results'] = $mstRes;
            } else {
                $arrayParameters['results'] = false;
            }
        }
        $arrayParameters = $this->sendEvent('melis_site_translation_save_translation_key_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to save the translation text
     *
     * @param array $data
     * @param null $id
     * @return mixed
     */
    public function saveTranslationText($data = array(), $id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters['results'] = null;
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_save_translation_text_start', $arrayParameters);
        $msttTable = $this->getServiceLocator()->get('MelisSiteTranslationTextTable');

        if (!is_null($data) && !empty($data) && sizeof($data) > 0) {
            //check whether we update or we insert the record
            if(!is_null($id) && !empty($id) && $id != 0) {
                $msttRes = $msttTable->save($arrayParameters['data'], $id);
            }else{
                $msttRes = $msttTable->save($arrayParameters['data']);
            }

            if ($msttRes) {
                $arrayParameters['results'] = $msttRes;
            } else {
                $arrayParameters['results'] = false;
            }
        }
        $arrayParameters = $this->sendEvent('melis_site_translation_save_translation_text_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to get the translated text by key
     *
     * @param null $translationKey
     * @param null $locale - the language local (eg. en_EN, fr_FR, etc.)
     * @return mixed|null
     */
    public function getSiteTranslationTextByKey($translationKey = null, $locale = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //check if $translationKey is not empty
        if (!is_null($arrayParameters['translationKey'])) {
            try {
                $arrayParameters['results'] = $arrayParameters['translationKey'];
                // Sending service start event
                $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_by_key_start', $arrayParameters);

                //if $locale is empty, get the current used locale
                if(is_null($arrayParameters['locale'])){
                    $container = new Container('melisplugins');
                    $currentLocale = (isset($container['melis-plugins-lang-locale']) ? $container['melis-plugins-lang-locale'] : null);
                    if(!is_null($currentLocale)){
                        //use the current locale
                        $arrayParameters['locale'] = $currentLocale;
                    }else {
                        //set default locale to english
                        $arrayParameters['locale'] = 'en_EN';
                    }
                }
                //get the data
                $getAllTransMsg = $this->getSiteTranslationList($arrayParameters['translationKey'], $arrayParameters['locale']);
                if($getAllTransMsg) {
                    //get the translated text
                    foreach ($getAllTransMsg as $transKey => $transMsg) {
                        if ($arrayParameters['translationKey'] == $transMsg['mst_key']) {
                            $arrayParameters['results'] = $transMsg['mstt_text'];
                            break;
                        }
                    }
                }
                // Sending service end event
                $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_by_key_end', $arrayParameters);
            } catch (\Exception $ex) {
                //if we encounter an error, we just need to return the translationKey
                $arrayParameters['results'] = $arrayParameters['translationKey'];
            }
        }else{
            $arrayParameters['results'] = null;
        }
        return $arrayParameters['results'];
    }

    /**
     * Function to get all translated text in the file and in the db
     *
     * @param null $locale - if provided, it will get only the translated text by locale (eg. en_EN, fr_FR, etc.)
     * @param null $translationKey - if provided, it will get only the translated text by key
     * @return array
     */
    public function getSiteTranslationList($translationKey = null, $locale = null)
    {
        try {
            // Event parameters prepare
            $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
            // Sending service start event
            $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_start', $arrayParameters);
            /**
             * Get the translation from the database
             */
            $transFromDb = $this->getSiteTranslationFromDb($arrayParameters['translationKey'], $arrayParameters['locale']);
            /**
             *  Get all the translation from the file in every module
             */
            $transFromFile = $this->getSiteTranslationFromFile($arrayParameters['translationKey'], $arrayParameters['locale']);

            /**
             * Check if the translation from the file are already existed in the db
             * if it exist, don't include the translation from the file - the translation from db is the priority
             */
            if($transFromDb) {
                foreach ($transFromFile as $keyFile => $keyValue) {
                    foreach ($transFromDb as $keyFromDb => $valFromDb) {
                        //if the trans key from the file already exist in the db, don't include it
                        if ($valFromDb['mst_key'] == $keyValue['mst_key'] && $valFromDb['mst_locale'] == $keyValue['mst_locale']) {
                            unset($transFromFile[$keyFile]);
                        }
                    }
                }
            }

            //merge all the translations
            $translationData = array_merge($transFromFile, $transFromDb);
            $arrayParameters['results'] = $translationData;

            $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_end', $arrayParameters);
        }catch(\Exception $ex){
            $arrayParameters['results'] = array();
        }
        return $arrayParameters['results'];
    }

    /**
     * Function to get all translation of every module in the file
     *
     * @param null $locale - the language local (eg. en_EN, fr_FR, etc.)
     * @param $translationKey
     * @return array
     */
    public function getSiteTranslationFromFile($translationKey = null, $locale = null)
    {

        $transFromFile = array();
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_file_start', $arrayParameters);

        $modulesSvc = $this->getServiceLocator()->get('ModulesService');
        $modules = $modulesSvc->getAllModules();

        $moduleFolders = array();
        foreach ($modules as $module) {
            array_push($moduleFolders, $modulesSvc->getModulePath($module));
        }

        $transFiles = array();
        $tmpTrans = array();

        $langTable = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $langList = $langTable->fetchAll()->toArray();

        //get the locale
        if (is_null($arrayParameters['locale']) && empty($arrayParameters['locale'])) {
            foreach ($langList as $loc) {
                array_push($transFiles,  $loc['lang_cms_locale'] . '.interface.php', $loc['lang_cms_locale'] . '.forms.php');
            }
        } else {
            array_push($transFiles, $arrayParameters['locale'] . '.interface.php', $arrayParameters['locale'] . '.forms.php');
        }

        //get the translation from each module
        set_time_limit(0);
        foreach ($moduleFolders as $module) {
            if (file_exists($module . '/language')) {
                foreach ($transFiles as $file) {
                    $file_info = explode(".", $file);
                    if (file_exists($module . '/language/' . $file)) {
                        array_push($tmpTrans, array($file_info[0] => include($module . '/language/' . $file)));
                    }
                }
            }
        }

        if ($tmpTrans) {
            $lang_id = 0;
            foreach ($tmpTrans as $tmpIdx => $transKey) {
                //loop again to get the translation from locale
                foreach ($transKey as $localeKey => $value) {
                    //get the language id by field since the translation from the file has no id of the language
                    $langData = $langTable->getEntryByField('lang_cms_locale', $localeKey)->toArray();
                    if(isset($langData[0])){
                        $lang_id = $langData[0]['lang_cms_id'];
                    }
                    //loop to get the key and the text
                    foreach ($value as $k => $val) {
                        //check if key is not null to retrieve only the translation with equal to the key
                        if(!is_null($arrayParameters['translationKey']) && !empty($arrayParameters['translationKey'])) {
                            if ($k == $arrayParameters['translationKey']) {
                                array_push($transFromFile, array('mst_id' => 0, 'mstt_id' => 0, 'mstt_lang_id' => $lang_id, 'mst_locale' => $localeKey, 'mst_key' => $k, 'mstt_text' => $val));
                            }
                        }else{
                            array_push($transFromFile, array('mst_id' => 0, 'mstt_id' => 0, 'mstt_lang_id' => $lang_id, 'mst_locale' => $localeKey, 'mst_key' => $k, 'mstt_text' => $val));
                        }
                    }
                }
            }
        }

        $arrayParameters['results'] = $transFromFile;
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_file_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to get all translation from db
     *
     * @param null $locale - the language local (eg. en_EN, fr_FR, etc.)
     * @param null $translationKey
     * @return array
     */
    public function getSiteTranslationFromDb($translationKey = null, $locale = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_db_start', $arrayParameters);

        $transFromDb = array();
        $mstTable = $this->getServiceLocator()->get('MelisSiteTranslationTable');
        $translationFromDb = $mstTable->getSiteTranslation($arrayParameters['translationKey'], $arrayParameters['locale'])->toArray();
        foreach ($translationFromDb as $keyDb => $valueDb) {
            array_push($transFromDb, array('mst_id' => $valueDb['mst_id'], 'mstt_id' => $valueDb['mstt_id'], 'mstt_lang_id' => $valueDb['mstt_lang_id'], 'mst_locale' => $valueDb['lang_cms_locale'], 'mst_key' => $valueDb['mst_key'], 'mstt_text' => $valueDb['mstt_text']));
        }

        $arrayParameters['results'] = $transFromDb;
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_db_end', $arrayParameters);

        return $arrayParameters['results'] ;
    }
}
<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Service;


use Composer\Composer;
use Composer\Factory;
use Composer\Package\CompletePackage;
use Composer\IO\NullIO;
use MelisEngine\Service\MelisEngineGeneralService;

class MelisSiteTranslationService extends MelisEngineGeneralService
{

    /**
     * @var Composer
     */
    protected $composer;

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
     * @param String $translationKey
     * @param null $langId
     * @return mixed|null
     */
    public function getText($translationKey, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //check if $translationKey is not empty
        $arrayParameters['results'] = $arrayParameters['translationKey'];
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_by_key_start', $arrayParameters);
        if(!is_null($arrayParameters['langId']) && !empty($arrayParameters['langId'])) {
            //get the data
            $getAllTransMsg = $this->getSiteTranslation($arrayParameters['translationKey'], $arrayParameters['langId']);
            if ($getAllTransMsg) {
                //get the translated text
                foreach ($getAllTransMsg as $transKey => $transMsg) {
                    if ($arrayParameters['translationKey'] == $transMsg['mst_key']) {
                        $arrayParameters['results'] = $transMsg['mstt_text'];
                        break;
                    }
                }
            }
        }
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_by_key_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to get all translated text in the file and in the db
     *
     * @param null $langId
     * @param null $translationKey - if provided, it will get only the translated text by key
     * @return array
     */
    public function getSiteTranslation($translationKey = null, $langId = null)
    {
        try {
            // Event parameters prepare
            $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
            // Sending service start event
            $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_start', $arrayParameters);
            /**
             * Get the translation from the database
             */
            $transFromDb = $this->getSiteTranslationFromDb($arrayParameters['translationKey'], $arrayParameters['langId']);
            /**
             *  Get all the translation from the file in every module
             */
            $transFromFile = $this->getSiteTranslationFromFile($arrayParameters['translationKey'], $arrayParameters['langId']);
            /**
             * Check if the translation from the file are already existed in the db
             * if it exist, don't include the translation from the file - the translation from db is the priority
             */
            if($transFromDb) {
                foreach ($transFromFile as $keyFile => $keyValue) {
                    foreach ($transFromDb as $keyFromDb => $valFromDb) {
                        //if the trans key from the file already exist in the db, don't include it
                        if ($valFromDb['mst_key'] == $keyValue['mst_key'] && $valFromDb['mstt_lang_id'] == $keyValue['mstt_lang_id']) {
                            unset($transFromFile[$keyFile]);
                        }
                    }
                }
            }

            //merge all the translations
            $translationData = array_merge($transFromFile, $transFromDb);
            $translationData = array_values(array_unique($translationData, SORT_REGULAR));

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
     * @param null $langId
     * @param $translationKey
     * @return array
     */
    public function getSiteTranslationFromFile($translationKey = null, $langId = null)
    {

        $transFromFile = array();
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_file_start', $arrayParameters);

        $modules = $this->getSitesModules();

        $moduleFolders = array();
        foreach ($modules as $module) {
            //get path for each site
            $modulePath = $_SERVER['DOCUMENT_ROOT'] . '/../module/MelisSites/'.$module;
            if(is_dir($modulePath)){
                array_push($moduleFolders, $modulePath);
            }
        }
        $transFiles = array();
        $tmpTrans = array();

        $langTable = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        /**
         * if langId is null or empty, get all the languages
         */
        if (is_null($arrayParameters['langId']) && empty($arrayParameters['langId'])) {
            //get the language list
            $langList = $langTable->fetchAll()->toArray();
        } else {
            $langList = $langTable->getEntryById($arrayParameters['langId'])->toArray();
        }

        //get the language info
        foreach ($langList as $loc) {
            /**
             * we need to concat the lang id and the lang locale to use it later
             * so that we don't need to query again to get the lang id to make it a key of the array
             * we just need to explode it to separate the id from the exact file name
             */
            $langStr = $loc['lang_cms_id'].'-'.$loc['lang_cms_locale'];
            array_push($transFiles,  $langStr . '.php', $langStr . '.php');
        }

        //get the translation from each module
        set_time_limit(0);
        foreach ($moduleFolders as $module) {
            //check if language folder is exist
            if (file_exists($module . '/language')) {
                //loop through each filename
                foreach ($transFiles as $file) {
                    //explode the file to separate the langId from the file name
                    $file_info = explode("-", $file);
                    $fName = $file_info[1];
                    $langId = $file_info[0];
                    //check if translation file exist
                    if (file_exists($module . '/language/' . $fName)) {
                        //get the contents of the translation file
                        array_push($tmpTrans, array($langId => include($module . '/language/' . $file_info[1])));
                    }
                }
            }
        }

        if ($tmpTrans) {
            foreach ($tmpTrans as $tmpIdx => $transKey) {
                //loop again to get the translation from langId
                foreach ($transKey as $langId => $value) {
                    //loop to get the key and the text
                    foreach ($value as $k => $val) {
                        //check if key is not null to retrieve only the translation with equal to the key
                        if(!is_null($arrayParameters['translationKey']) && !empty($arrayParameters['translationKey'])) {
                            if ($k == $arrayParameters['translationKey']) {
                                array_push($transFromFile, array('mst_id' => 0, 'mstt_id' => 0, 'mstt_lang_id' => $langId, 'mst_key' => $k, 'mstt_text' => $val));
                            }
                        }else{//return everything
                            array_push($transFromFile, array('mst_id' => 0, 'mstt_id' => 0, 'mstt_lang_id' => $langId, 'mst_key' => $k, 'mstt_text' => $val));
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
     * @param null $langId
     * @param null $translationKey
     * @return array
     */
    public function getSiteTranslationFromDb($translationKey = null, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_db_start', $arrayParameters);

        $transFromDb = array();
        $mstTable = $this->getServiceLocator()->get('MelisSiteTranslationTable');
        $translationFromDb = $mstTable->getSiteTranslation($arrayParameters['translationKey'], $arrayParameters['langId'])->toArray();

        foreach ($translationFromDb as $keyDb => $valueDb) {
            array_push($transFromDb, array('mst_id' => $valueDb['mst_id'], 'mstt_id' => $valueDb['mstt_id'], 'mstt_lang_id' => $valueDb['mstt_lang_id'], 'mst_key' => $valueDb['mst_key'], 'mstt_text' => $valueDb['mstt_text']));
        }
        $arrayParameters['results'] = $transFromDb;
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_db_end', $arrayParameters);

        return $arrayParameters['results'] ;
    }

    /** ======================================================================================================================= **/
    /** ======================================================================================================================= **/
    /** ======================================================================================================================= **/
    /** ================================================= GET ALL SITES MODULES ================================================= **/
    /** ======================================================================================================================= **/
    /** ======================================================================================================================= **/
    /** ======================================================================================================================= **/


    private function getSitesModules()
    {
        $userModules = $_SERVER['DOCUMENT_ROOT'] . '/../module/MelisSites';

        $modules = array();
        if($this->checkDir($userModules)) {
            $modules = $this->getDir($userModules);
        }

        return $modules;
    }

    /**
     * This will check if directory exists and it's a valid directory
     * @param $dir
     * @return bool
     */
    protected function checkDir($dir)
    {
        if(file_exists($dir) && is_dir($dir))
        {
            return true;
        }

        return false;
    }

    /**
     * Returns all the sub-folders in the provided path
     * @param String $dir
     * @param array $excludeSubFolders
     * @return array
     */
    protected function getDir($dir, $excludeSubFolders = array())
    {
        $directories = array();
        if(file_exists($dir)) {
            $excludeDir = array_merge(array('.', '..', '.gitignore'), $excludeSubFolders);
            $directory  = array_diff(scandir($dir), $excludeDir);

            foreach($directory as $d) {
                if(is_dir($dir.'/'.$d)) {
                    $directories[] = $d;
                }
            }

        }

        return $directories;
    }
}
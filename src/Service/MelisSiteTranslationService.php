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
        } catch (\Exception $ex) {
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
            if ($data['mst_id'] != 0) {
                $mstRes = $this->saveTranslationKey($data['mst_data'], $data['mst_id']);
                if ($mstRes) {
                    $msttRes = $this->saveTranslationText($data['mstt_data'], $data['mstt_id']);
                    if ($msttRes) {
                        $arrayParameters['results'] = $mstRes;
                    }
                } else {
                    $arrayParameters['results'] = false;
                }
            } else {
                $mstRes = $this->saveTranslationKey($data['mst_data']);
                if ($mstRes) {
                    $data['mstt_data']['mstt_mst_id'] = $mstRes;
                    $msttRes = $this->saveTranslationText($data['mstt_data']);
                    if ($msttRes) {
                        $arrayParameters['results'] = $mstRes;
                    }
                } else {
                    $arrayParameters['results'] = false;
                }
            }
            $con->commit();
        } catch (\Exception $ex) {
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
            if (!is_null($id) && !empty($id) && $id != 0) {
                $mstRes = $mstTable->save($arrayParameters['data'], $id);
            } else {
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
            if (!is_null($id) && !empty($id) && $id != 0) {
                $msttRes = $msttTable->save($arrayParameters['data'], $id);
            } else {
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
    public function getText($translationKey, $langId = null, $siteId = 0)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //check if $translationKey is not empty
        $arrayParameters['results'] = $arrayParameters['translationKey'];
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_by_key_start', $arrayParameters);
        if (!is_null($arrayParameters['langId']) && !empty($arrayParameters['langId'])) {
            //get the data
            $getAllTransMsg = $this->getSiteTranslation($arrayParameters['translationKey'], $arrayParameters['langId'], $arrayParameters['siteId']);
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
     * @param null $siteId
     * @param null $isFromModal
     * @return array
     */
    public function getSiteTranslation($translationKey = null, $langId = null, $siteId = 0, $isFromModal = false)
    {
        try {
            // Event parameters prepare
            $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
            // Sending service start event
            $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_start', $arrayParameters);
            /**
             * get site id from page in the the route
             */
            if (empty($arrayParameters['siteId'])) {
                $router = $this->serviceLocator->get('router');
                $request = $this->serviceLocator->get('request');

                $routeMatch = $router->match($request);
                $params = $routeMatch->getParams();
                if (!empty($params)) {
                    if (isset($params['idpage'])) {
                        $pageId = $params['idpage'];
                        $pageTreeService = $this->getServiceLocator()->get('MelisEngineTree');
                        $site = $pageTreeService->getSiteByPageId($pageId);
                        if (!empty($site)) {
                            $arrayParameters['siteId'] = $site->site_id;
                        }
                    }
                }
            }
            /**
             * Get the translation from the database
             */
            $transFromDb = $this->getSiteTranslationFromDb($arrayParameters['translationKey'], $arrayParameters['langId'], $arrayParameters['siteId']);
            /**
             *  Get all the translation from the file in every module
             */
            $transFromFile = $this->getSiteTranslationFromFile($arrayParameters['translationKey'], $arrayParameters['langId'], $arrayParameters['siteId'], $arrayParameters['isFromModal']);
            /**
             * Check if the translation from the file are already existed in the db
             * if it exist, don't include the translation from the file - the translation from db is the priority
             */
            if ($transFromDb) {
                foreach ($transFromFile as $keyFile => $keyValue) {
                    foreach ($transFromDb as $keyFromDb => $valFromDb) {
                        //if the trans key from the file already exist in the db, don't include it
                        if ($valFromDb['mst_key'] == $keyValue['mst_key'] && $valFromDb['mstt_lang_id'] == $keyValue['mstt_lang_id']) {
                            //transfer the trans file module name to db trans file module name
                            $transFromDb[$keyFromDb]['module'] = $keyValue['module'];
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
        } catch (\Exception $ex) {
            $arrayParameters['results'] = array();
        }
        return $arrayParameters['results'];
    }

    /**
     * Function to get all translation from db
     *
     * @param null $langId
     * @param null $translationKey
     * @param null $siteId
     * @return array
     */
    public function getSiteTranslationFromDb($translationKey = null, $langId = null, $siteId = 0)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_db_start', $arrayParameters);

        $transFromDb = array();
        $mstTable = $this->getServiceLocator()->get('MelisSiteTranslationTable');
        if (empty($arrayParameters['translationKey']) && empty($arrayParameters['langId'])) {
            $translationFromDb = $mstTable->getTranslationAll()->toArray();
        } else {
            $translationFromDb = $mstTable->getSiteTranslation($arrayParameters['translationKey'], $arrayParameters['langId'], $arrayParameters['siteId'])->toArray();
        }

        foreach ($translationFromDb as $keyDb => $valueDb) {
            array_push($transFromDb, array('mst_id' => $valueDb['mst_id'], 'mstt_id' => $valueDb['mstt_id'], 'mstt_site_id' => $valueDb['mstt_site_id'], 'mstt_lang_id' => $valueDb['mstt_lang_id'], 'mst_key' => $valueDb['mst_key'], 'mstt_text' => $valueDb['mstt_text'], 'module' => null));
        }
        $arrayParameters['results'] = $transFromDb;
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_db_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Function to get all translation of every module in the file
     *
     * @param null $langId
     * @param $translationKey
     * @param $siteId
     * @param $isFromModal
     * @return array
     */
    public function getSiteTranslationFromFile($translationKey = null, $langId = null, $siteId = 0, $isFromModal = false)
    {

        $transFromFile = array();
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_site_translation_get_trans_list_from_file_start', $arrayParameters);

        $modules = $this->getSitesModules();

        $moduleFolders = array();
        if ($isFromModal) {
            if (!empty($arrayParameters['siteId'])) {
                $tplTable = $this->serviceLocator->get('MelisEngineTableTemplate');
                $tlpData = $tplTable->getEntryByField('tpl_site_id', $arrayParameters['siteId'])->current();
                if (!empty($tlpData)) {
                    $folderName = $tlpData->tpl_zf2_website_folder;

                    //check if site is came from the vendor
                    if(!empty($this->getComposerModulePath($folderName))){
                        $modulePath = $this->getComposerModulePath($folderName);
                    }else {
                        $modulePath = $_SERVER['DOCUMENT_ROOT'] . '/../module/MelisSites/' . $folderName;
                    }

                    if (is_dir($modulePath)) {
                        array_push($moduleFolders, array('path' => $modulePath, 'module' => $folderName));
                    }
                }
            }
        } else {
            foreach ($modules as $module) {
                //get path for each site
                $modulePath = $_SERVER['DOCUMENT_ROOT'] . '/../module/MelisSites/' . $module;
                if (is_dir($modulePath)) {
                    array_push($moduleFolders, array('path' => $modulePath, 'module' => $module));
                }
            }
            if(!empty($this->getSiteTranslationsFromVendor())){
                $moduleFolders = array_merge($moduleFolders, $this->getSiteTranslationsFromVendor());
            }
        }

        $transFiles = array();
        $tmpTrans = array();

        /** @var MelisEngineLangService $langSrv */
        $langSrv = $this->getServiceLocator()->get('MelisEngineLang');
        /**
         * if langId is null or empty, get all the languages
         */
        if (is_null($arrayParameters['langId']) && empty($arrayParameters['langId'])) {
            //get the language list
            $langList = $langSrv->getAvailableLanguages();
        } else {
            $langList = $langSrv->getLangDataById($arrayParameters['langId']);
        }

        //get the language info
        foreach ($langList as $loc) {
            /**
             * we need to concat the lang id and the lang locale to use it later
             * so that we don't need to query again to get the lang id to make it a key of the array
             * we just need to explode it to separate the id from the locale
             */
            $langStr = $loc['lang_cms_id'] . '-' . $loc['lang_cms_locale'];
            array_push($transFiles, $langStr);
        }

        //get the translation from each module
        set_time_limit(0);
        foreach ($moduleFolders as $module) {
            //check if language folder is exist
            if (file_exists($module['path'] . '/language')) {
                //loop through each filename
                foreach ($transFiles as $file) {
                    //explode the file to separate the langId from the file name
                    $file_info = explode("-", $file);
                    $langLocale = $file_info[1];
                    $langId = $file_info[0];
                    //get all translation from language folder that contains language locale.
                    $files = glob($module['path'] . '/language/*' . $langLocale . '*.php');
                    foreach ($files as $f) {
                        //check if translation file exist
                        if (file_exists($f)) {
                            //get the contents of the translation file
                            array_push($tmpTrans, array($langId => array('translations' => include($f), 'module' => $module['module'])));
                        }
                    }
                }
            }
        }

        //process/format the translations
        if ($tmpTrans) {
            foreach ($tmpTrans as $tmpIdx => $transKey) {
                //loop again to get the translation from langId
                foreach ($transKey as $langId => $value) {
                    //loop to get the key and the text
                    foreach ($value['translations'] as $k => $val) {
                        //check if key is not null to retrieve only the translation with equal to the key
                        if (!is_null($arrayParameters['translationKey']) && !empty($arrayParameters['translationKey'])) {
                            if ($k == $arrayParameters['translationKey']) {
                                array_push($transFromFile, array('mst_id' => 0, 'mstt_id' => 0, 'mstt_site_id' => $siteId, 'mstt_lang_id' => $langId, 'mst_key' => $k, 'mstt_text' => $val, 'module' => $value['module']));
                            }
                        } else {//return everything
                            array_push($transFromFile, array('mst_id' => 0, 'mstt_id' => 0, 'mstt_site_id' => $siteId, 'mstt_lang_id' => $langId, 'mst_key' => $k, 'mstt_text' => $val, 'module' => $value['module']));
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
     * Get the translation from the site inside
     * the vendor
     *
     * @return array
     */
    public function getSiteTranslationsFromVendor()
    {
        $vendordModules = $this->getVendorModules();

        $moduleFolders = array();
        foreach ($vendordModules as $key => $module){
            //check if module is site
            if($this->isSiteModule($module)){
                //get the full path of the site module
                $path = $this->getComposerModulePath($module);
                array_push($moduleFolders, array('path' => $path, 'module' => $module));
            }
        }
        return $moduleFolders;
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
        if ($this->checkDir($userModules)) {
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
        if (file_exists($dir) && is_dir($dir)) {
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
        if (file_exists($dir)) {
            $excludeDir = array_merge(array('.', '..', '.gitignore'), $excludeSubFolders);
            $directory = array_diff(scandir($dir), $excludeDir);

            foreach ($directory as $d) {
                if (is_dir($dir . '/' . $d)) {
                    $directories[] = $d;
                }
            }

        }

        return $directories;
    }

    /**
     *
     * ADDED THIS FUNCTIONS TO FIXED THE ERROR SINCE
     * WE NEED TO GET ALSO THE TRANSLATION FROM THE
     * SITE INSIDE VENDOR, SO WE NEED THE COMPOSER TO
     * HANDLED IT. RIGHT NOW THE MODULE SERVICE IS
     * ONLY AVAILABLE IN CORE, SO IT CANNOT BE USED
     * ON FRONT, SO WE NEED TO CREATE THIS FUNCTION
     * TO MADE A SOLUTION TO THIS PROBLEM, BUT THIS
     * PROBLEM IS ALREADY SOLVED ON VERSION 2 OF THE
     * SITE SINCE THE SITE-TRANSLATION-MODULE WILL
     * BE TRANSFERRED INSIDE SITE TOOL
     *
     */


    /**
     * @return \Composer\Composer
     */
    protected function getComposer()
    {
        if (is_null($this->composer)) {
            // required by composer factory but not used to parse local repositories
            if (!isset($_ENV['COMPOSER_HOME'])) {
                putenv("COMPOSER_HOME=/tmp");
            }
            $factory = new Factory();
            $this->setComposer($factory->createComposer(new NullIO()));
        }

        return $this->composer;
    }

    /**
     * @param Composer $composer
     *
     * @return $this
     */
    protected function setComposer(Composer $composer)
    {
        $this->composer = $composer;

        return $this;
    }

    /**
     * Returns all melisplatform-module packages loaded by composer
     * @return array
     */
    protected function getVendorModules()
    {
        $repos = $this->getComposer()->getRepositoryManager()->getLocalRepository();

        $packages = array_filter($repos->getPackages(), function ($package) {
            /** @var CompletePackage $package */
            return $package->getType() === 'melisplatform-module' &&
                array_key_exists('module-name', $package->getExtra());
        });

        $modules = array_map(function ($package) {
            /** @var CompletePackage $package */
            return $package->getExtra()['module-name'];
        }, $packages);

        sort($modules);

        return $modules;
    }

    /**
     * @param $module
     *
     * @return bool
     */
    protected function isSiteModule($module)
    {
        $composerFile = $_SERVER['DOCUMENT_ROOT'] . '/../vendor/composer/installed.json';
        $composer = (array) \Zend\Json\Json::decode(file_get_contents($composerFile));

        $repo = null;

        foreach ($composer as $package) {
            $packageModuleName = isset($package->extra) ? (array) $package->extra : null;

            if (isset($packageModuleName['module-name']) && $packageModuleName['module-name'] == $module) {
                $repo = (array) $package->extra;
                break;
            }
        }

        if ($repo) {
            if(isset($repo['melis-site'])) {
                return (bool)$repo['melis-site'] ?? false;
            }
        }

        return false;
    }

    protected function getComposerModulePath($moduleName, $returnFullPath = true)
    {
        $repos = $this->getComposer()->getRepositoryManager()->getLocalRepository();
        $packages = $repos->getPackages();

        if (!empty($packages)) {
            foreach ($packages as $repo) {
                if ($repo->getType() == 'melisplatform-module') {
                    if (array_key_exists('module-name', $repo->getExtra())
                        && $moduleName == $repo->getExtra()['module-name']) {
                        foreach ($repo->getRequires() as $require) {
                            $source = $require->getSource();

                            if ($returnFullPath) {
                                return $_SERVER['DOCUMENT_ROOT'] . '/../vendor/' . $source;
                            } else {
                                return '/vendor/' . $source;
                            }
                        }
                    }
                }
            }
        }

        return '';
    }
}

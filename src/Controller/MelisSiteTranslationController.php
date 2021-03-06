<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Controller;

use Zend\Form\Factory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MelisSiteTranslationController extends AbstractActionController
{
    const TOOL_INDEX = 'melis_site_translation';
    const TOOL_KEY = 'melis_site_translation_tool';
    const LOG_DELETE = 'SITE_TRANSLATE_DELETE';
    const LOG_ADD = 'SITE_TRANSLATE_ADD';
    const LOG_UPDATE = 'SITE_TRANSLATE_UPDATE';

    public function renderMelisSiteTranslationContentFiltersLimitAction()
    {
        return new ViewModel();
    }

    public function renderMelisSiteTranslationContentFiltersRefreshAction()
    {
        return new ViewModel();
    }

    public function renderMelisSiteTranslationContentFiltersSearchAction()
    {
        return new ViewModel();
    }

    public function renderMelisSiteTranslationActionEditAction()
    {
        return new ViewModel();
    }

    public function renderMelisSiteTranslationActionDeleteAction()
    {
        return new ViewModel();
    }

    /**
     * Function to render site translation
     *
     * @return ViewModel
     */
    public function renderMelisSiteTranslationAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Function to render site translation modal
     *
     * @return ViewModel
     */
    public function renderMelisSiteTranslationModalAction()
    {
        $id = $this->params()->fromRoute('id', $this->params()->fromQuery('id', ''));
        $melisKey = $this->params()->fromRoute('melisKey', $this->params()->fromQuery('melisKey', ''));

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->id = $id;
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Function to render site translation add modal
     *
     * @return ViewModel
     */
    /*public function renderMelisSiteTranslationModalAddSiteTranslationAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        // declare the Tool service that we will be using to completely create our tool.
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');

        // tell the Tool what configuration in the app.tools.php that will be used.
        $melisTool->setMelisToolKey(self::TOOL_INDEX, self::TOOL_KEY);
        //prepare the user profile form
        $form = $melisTool->getForm('melissitetranslation_form');

        $view = new ViewModel();
        $view->setVariable('melissitetranslation_form', $form);
        $view->melisKey = $melisKey;
        return $view;
    }*/

    /**
     * Function to render site translation edit modal
     *
     * @return ViewModel
     */
    public function renderMelisSiteTranslationModalEditSiteTranslationAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $translationKey = $this->params()->fromQuery('translationKey', null);
        $langid = $this->params()->fromQuery('langId', null);
        $siteId = $this->params()->fromQuery('siteId', 0);

        // declare the Tool service that we will be using to completely create our tool.
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');

        // tell the Tool what configuration in the app.tools.php that will be used.
        $melisTool->setMelisToolKey(self::TOOL_INDEX, self::TOOL_KEY);
        //prepare the user profile form
        $form = $melisTool->getForm('melissitetranslation_form');

        $melisSiteTranslationService = $this->getServiceLocator()->get('MelisSiteTranslationService');
        $data = $melisSiteTranslationService->getSiteTranslation($translationKey, $langid, $siteId);

        if($data){
            $form->setData($data[0]);
            /*if($data[0]['mst_id'] != 0) {
                $form->get('mst_key')->setAttribute('readonly', false);
            }*/
        }

        $view = new ViewModel();
        $view->setVariable('melissitetranslation_form', $form);
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Function to render the site translation content
     *
     * @return ViewModel
     */
    public function renderMelisSiteTranslationContentAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::TOOL_INDEX, self::TOOL_KEY);

        $columns = $melisTool->getColumns();
        // pre-add Action Columns
        $columns['actions'] = array('text' => $translator->translate('tr_meliscore_global_action'), 'css' => 'width:10%');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration();

        return $view;
    }

    /**
     * Function to delete the translation text from the database only
     *
     * @return JsonModel
     */
    public function deleteTranslationAction()
    {
        $success = false;
        $textTitle = 'tr_melis_site_translation_del_translation';
        $textMessage = 'tr_melis_site_translation_delete_fail';
        $id = 0;

        //get the request
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $id = $data['mst_id'];

            $melisSiteTranslationService = $this->getServiceLocator()->get('MelisSiteTranslationService');
            $res = $melisSiteTranslationService->deleteTranslation($data);
            if ($res) {
                $success = true;
                $textMessage = 'tr_melis_site_translation_delete_success';
            }
        }

        //prepare the data to return
        $response = array(
            'success' => $success,
        );

        $this->getEventManager()->trigger(
            'melissitetranslation_del_translation_end',
            $this,
            array_merge(
                $response,
                [
                    'textTitle' => $textTitle,
                    'textMessage' => $textMessage,
                    'typeCode' => self::LOG_DELETE,
                    'itemId' => $id
                ]
            )
        );

        return new JsonModel($response);
    }

    /**
     * Function to insert / update translation
     * The post data contains mst_data array, mstt_data array and the id of both(mst_id, mstt_id - for updating a record)
     * mst_data - contains the data to be insert / update in the melis_site_translation table
     * mstt_data - contains the data to be insert / update in the melis_site_translation_text table
     * mst_id - the id of the data to be update in melis_site_translation table
     * mstt_id the id of the data to be update in melis_site_translation_text table
     * @return JsonModel
     */
    public function saveTranslationAction()
    {
        $success = false;
        $errors = [];

        //get the request
        $request = $this->getRequest();
        //$logTypeCode = self::LOG_ADD;
        $id = 0;
        $textTitle = 'tr_melis_site_translations';
        $textMessage = 'tr_melis_site_translation_save_failed';

        //check if request is post
        if ($request->isPost()) {
            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('melis_site_translation/tools/melis_site_translation_tool/forms/melissitetranslation_form', 'melissitetranslation_form');

            $factory = new Factory();
            $formElements = $this->getServiceLocator()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);

            //get and sanitize the data
            $postValues = $melisTool->sanitizeRecursive($request->getPost()->toArray(), ['mstt_text'], false, true);

            if (!empty($postValues['mst_id'])) {
                //$logTypeCode = self::LOG_UPDATE;
                $id = $postValues['mst_id'];
                $textTitle = 'tr_melis_site_translation_edit_translation';
            }

            //we need to merge the data from mst_data and mstt_data array to validate the form
            $tempFormValidationData = array_merge($postValues['mst_data'], $postValues['mstt_data']);
            //assign the data to the form
            $propertyForm->setData($tempFormValidationData);
            //check if form is valid(if all the form field are match with the value that we pass from routes)
            if ($propertyForm->isValid()) {
                /** @var \MelisSiteTranslation\Service\MelisSiteTranslationService $melisSiteTranslationService */
                $melisSiteTranslationService = $this->getServiceLocator()->get('MelisSiteTranslationService');
                $res = $melisSiteTranslationService->saveTranslation($postValues);
                if ($res) {
                    //$textMessage = $logTypeCode === 'SITE_TRANSLATE_UPDATE' ? 'tr_melis_site_translation_update_success' : 'tr_melis_site_translation_inserting_success';
                    $textMessage = 'tr_melis_site_translation_update_success';
                    $success = true;
                    $id = $res;
                }
            } else {
                $appConfigForm = $appConfigForm['elements'];
                $formErrors = $propertyForm->getMessages();
                $errors = $this->processErrors($formErrors, $appConfigForm);
            }
        }

        //prepare the data to return
        $response = array(
            'success' => $success,
            'errors' => $errors
        );

        $this->getEventManager()->trigger(
            'melissitetranslation_save_translation_end',
            $this,
            array_merge(
                $response,
                [
                    'textTitle' => $textTitle,
                    'textMessage' => $textMessage,
                    'typeCode' => self::LOG_UPDATE,
                    'itemId' => $id
                ]
            )
        );

        return new JsonModel($response);
    }

    /**
     * Function to get all translation from both file and database
     *
     * @return JsonModel
     */
    public function getTranslationAction()
    {
        $dataCount = 0;
        $data = array();
        $draw = 0;
        $recordsFiltered = 0;
        $hasFilter = false;

        if($this->getRequest()->isPost()) {
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $melisTool->setMelisToolKey(self::TOOL_INDEX, self::TOOL_KEY);
            $colId = array_keys($melisTool->getColumns());

            //get the datatable parameters
            //get draw(page number)
            $draw = $this->getRequest()->getPost('draw');
            //get search value
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            //get site
            $site = $this->getRequest()->getPost('site_translation_site_name');
            //get start(where to start to get package)
            $start = $this->getRequest()->getPost('start');
            //get length(how many package will be displayed)
            $length = $this->getRequest()->getPost('length');

            $melisSiteTranslationService = $this->getServiceLocator()->get('MelisSiteTranslationService');
            //get the current usded lang id from the session
            $container = new Container('meliscore');
            $langIdBO = $container['melis-lang-id'];
            $langId = null;
            //get the language information
            /**
             * since there are possibility that the back office language and front language are not the same
             * we need to get the language information from back office and compare it from the cms language
             * using the language locale of both to get the exact language id
             * to make sure that we retrieve the exact translation
             */
            $langCoreTbl = $this->getServiceLocator()->get('MelisCoreTableLang');
            $langDetails = $langCoreTbl->getEntryById($langIdBO)->toArray();
            if($langDetails){
                $langCmsTbl = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
                foreach($langDetails as $langBO){
                    $localeBO = $langBO['lang_locale'];
                    $langCmsDetails = $langCmsTbl->getEntryByField('lang_cms_locale', $localeBO)->toArray();
                    foreach($langCmsDetails as $langCms){
                        $langId = $langCms['lang_cms_id'];
                    }
                }
            }
            //prepare the data to paginate
            $dataArr = $melisSiteTranslationService->getSiteTranslation();
            $a = [];

            $tempAr = [];
            $tempAr2 = [];
            $temp = [];

            //loop to separate the translation from the current BO language
            foreach($dataArr as $d){
                if($d['mstt_lang_id'] == $langId){
                    array_push($tempAr, $d);
                    array_push($temp, $d['mst_key']);
                }else{
                    array_push($tempAr2, $d);
                }
            }

            foreach($temp as $x){
                foreach($tempAr2 as $key => $val){
                    if($x == $val['mst_key']){
                        unset($tempAr2[$key]);
                    }
                }
            }

            /**
             * Lets check again to avoid duplicate translations
             *
             * This will check only those translations that
             * has different language from the BO
             *
             * Like if there are two or more translation keys inside the
             * array that has different language but not equal to the
             * BO lang, we need to get only one translations
             * to display
             */
            $otherTransKey = array();
            foreach($tempAr2 as $k => $v){
                if(in_array($v['mst_key'], $otherTransKey)){
                    unset($tempAr2[$k]);
                }else{
                    array_push($otherTransKey, $v['mst_key']);
                }
            }

            $data = array_merge($tempAr, $tempAr2);

            $data = array_values(array_unique($data, SORT_REGULAR));

            //striping tags to show full text on text column
            foreach ($data as $key =>$d){
                $data[$key]["mstt_text"] = strip_tags($data[$key]["mstt_text"]);
            }

            //process the translation list(pagination)
            for ($i = 0; $i < sizeof($data); $i++) {
                $data[$i]['mstt_text'] = $melisTool->sanitize($data[$i]['mstt_text']);

                //prepare the attribute for our row in the table
                $attrArray = array('data-lang-id'     => $data[$i]['mstt_lang_id'],
                    'data-mst-id'     => $data[$i]['mst_id'],
                    'data-mstt-id'    => $data[$i]['mstt_id'],
                    'data-site-id'    => $data[$i]['mstt_site_id']);

                //assign attribute data to table row
                $data[$i]['DT_RowAttr'] = $attrArray;

                //check if search is not empty(to filter by search)
                if (!empty($search)) {
                    $hasFilter = true;
                    //loop through each field to get its text, and check if has contain the $search value
                    foreach ($colId as $key => $val) {
                        if (isset($data[$i][$val])) {
                            if (strpos(strtolower($data[$i][$val]), strtolower($search)) !== false) {
                                //if found push the data
                                array_push($a, $data[$i]);
                                break;
                            }
                        }
                    }
                }else{//check if we need to filter by site
                    if(!empty($site)){
                        $hasFilter = true;
                        if($data[$i]['module'] == $site){
                            array_push($a, $data[$i]);
                        }
                    }
                }
            }

            if($hasFilter){
                //we need to make sure that there is no duplicate data in the array, and we need to re-index it again
                $data = array_values(array_unique($a, SORT_REGULAR));
                $recordsFiltered = $a;
            }else{
                $recordsFiltered = $data;
            }

            $data = array_splice($data, $start, $length);
            //sort the result by module name
            usort($data, function($a, $b){
                return strcasecmp($a['module'], $b['module']);
            });
        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  count($recordsFiltered),
            'data' => $data,
        ));
    }

    /**
     * Function to get translation by key and lang id
     *
     * @return JsonModel
     */
    public function getSiteTranslationByKeyAndLangIdAction()
    {
        $langid = $this->params()->fromQuery('langId', null);
        $siteid = $this->params()->fromQuery('siteId', null);
        $translationKey = $this->params()->fromQuery('translationKey', null);
        $melisSiteTranslationService = $this->getServiceLocator()->get('MelisSiteTranslationService');
        $data = $melisSiteTranslationService->getSiteTranslation($translationKey, $langid, $siteid, true);
        return new JsonModel(array(
            'data' => $data,
        ));
    }

    /**
     * Function list all sites from the page to used as filter by site
     *
     * @return ViewModel
     */
    public function renderMelisSiteTranslationFiltersSitesAction()
    {
        /**
         * get the page site published
         */
        $pagePublished = $this->getServiceLocator()->get('MelisEngineTablePagePublished');
        $published = $pagePublished->getEntryByField('page_type', 'SITE')->toArray();

        /**
         * get the page site saved
         */
        $pageSaved = $this->getServiceLocator()->get('MelisEngineTablePageSaved');
        $saved = $pageSaved->getEntryByField('page_type', 'SITE')->toArray();

        $data = array_merge($published, $saved);
        /**
         * get the folder name(module name) from template
         */
        $template = $this->getServiceLocator()->get('MelisEngineTableTemplate');
        $translator = $this->getServiceLocator()->get('translator');
        $sites = array();
        $sites[] = '<option value="">'. $translator->translate('tr_melis_site_translation_choose') .'</option>';
        $modules = array();
        for($i = 0; $i < sizeof($data); $i++){
            $tpl = $template->getEntryById($data[$i]['page_tpl_id']);
            foreach($tpl as $d){
                array_push($modules, array('site' => $data[$i]['page_name'], 'module' => $d->tpl_zf2_website_folder));
            }
        }

        $modules = array_values(array_unique($modules, SORT_REGULAR));
        foreach($modules as $site){
            $sites[] = '<option value="'.$site['module'].'">'. $site['site'].'</option>';
        }

        $view = new ViewModel();
        $view->sites = $sites;
        return $view;
    }

    /**
     * Function to process the errors
     *
     * @param array $errors
     * @param Form $appConfigForm
     * @return array $errors
     */
    private function processErrors($errors, $appConfigForm)
    {
        //loop through each errors
        foreach ($errors as $keyError => $valueError)
        {
            //look in the form for every failed field to specify the errors
            foreach ($appConfigForm as $keyForm => $valueForm)
            {
                if(isset($valueForm['spec'])) {
                    //check if field name is equal with the error key to highlight the field
                    if ($valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label']))
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }
        return $errors;
    }
}
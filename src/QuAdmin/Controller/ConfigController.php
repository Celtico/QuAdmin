<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;

class ConfigController extends AbstractController
{
    protected $events = null;
    protected $quProsesDataForm;

    public function variables()
    {

        /**
         * Conserve Local Model
         */
        $this->getModelBreadCrumb()->setQuAdminModelOptions($this->getOptions());


        /**
         * Reference Url Model
         */
        $LinkerModels = $this->getQuAdminModelOptions()->getLinkerModels();
        if(count($LinkerModels)){
            foreach($LinkerModels as $LinkerModel){
                if(isset($LinkerModel['model']) and $LinkerModel['model'] == 'qu_'.$this->getModel().'_model'){
                    $this->setOptions($this->Service('qu_'.$this->getModel().'_model'));
                    $this->setQuAdminModelOptions($this->getOptions());
                    $this->setIdParent($this->getId());

                    //Id Parent Linker
                    $TableKeyFields = $this->getQuAdminModelOptions()->getTableKeyFields();
                    $TableKeyFields['key_id_parent'] = $LinkerModel['key_id_parent'];
                    $this->getQuAdminModelOptions()->setTableKeyFields($TableKeyFields);
                }
            }
        }

        /**
         * get Field Keys
         */
        $this->getField();


        /**
         * Local Model
         */
        $this->getModelEdit()->setQuAdminModelOptions($this->getOptions());
        $dataDb = $this->getModelEdit()->findByLangIdByLang($this->getLang(),$this->getId());

        if(isset($dataDb[$this->KeyIdParent])){
            $mergeBreadCrumbModels = $this->getModelBreadCrumb()->breadCrumb($dataDb[$this->KeyIdParent],false,$this->getModel())->get();
        }else{
            $mergeBreadCrumbModels = null;
        }


        $dataController = array(
            'action'                 => 'config',
            'id_parent'              => $this->getIdParent(),
            'id'                     => $this->getId(),
            'lang'                   => $this->getLang(),
            'route'                  => $this->getRoute(),
            'options'                => $this->getOptions(),
            'key'                    => $this->key,
            'PathTemplateRender'     => $this->getPathTemplateRender(),
            'model'                  => $this->getModel(),
            'mergeBreadCrumbModels'  => $mergeBreadCrumbModels,
        );


        $this->getForm()->setOptionsForm($this->getOptions());
        $this->getForm()->addQuFormOptions($dataDb,$this->getModelForm(),$this->getServiceLocator());

        $dataController += array('form' => $this->getForm());

        if($this->getIsPost()){

            $dataPost = $this->getPost();

            if($dataPost['close'] != '')
            {
                return $this->getToRoute($this->getRoute(),array(
                    'action'=>'index',
                    'model'=>$this->getModel(),
                    'id'=> $this->check($dataDb),
                    'lang'=>$this->getLang()
                ));
            }
            /**
             * Process by Data
             */
            $DataForm = $this->getForm()->prosesDataForm($dataPost);

            if($this->KeyModified)  $DataForm[$this->KeyModified] = $this->getDate();
            if($this->KeyIdAuthor)  $DataForm[$this->KeyIdAuthor] = $this->getUser();
            if($this->KeyLang)      $DataForm[$this->KeyLang]     = $this->getLang();
            if($this->KeyIdParent)  $DataForm[$this->KeyIdParent] = $dataDb[$this->KeyIdParent];

            $DataForm[$this->KeyId] = $this->getId();

            if(!isset($DataForm['error'])){

                $this->getModelEdit()->update($DataForm,$this->getLang());

                if($dataPost['save'] != '')
                {
                    $this->getMessage(array('type'=>$this->getTranslate('EditSaveClassType'),'message' =>$this->getTranslate('EditSaveMessage')));
                    return $this->getToRoute($this->getRoute(),array(
                        'action'=>'config',
                        'model'=>$this->getModel(),
                        'id'=>$this->getId(),
                        'lang'=>$this->getLang()
                    ));
                }
                elseif($dataPost['saveandclose'] != '')
                {
                    $this->getMessage(array('type'=>$this->getTranslate('EditSaveCloseClassType'),'message'=>$this->getTranslate('EditSaveCloseMessage')));
                    return $this->getToRoute($this->getRoute(),array(
                        'action'=>'index',
                        'model'=>$this->getModel(),
                        'id' =>  $this->check($dataDb),
                        'lang'=>$this->getLang()
                    ));
                }

            }elseif(isset($DataForm['error']) and $DataForm['error']){

                $dataController['error'] = $DataForm;
            }

        }
        return $dataController;
    }

    public function getQuProsesDataForm(){

        return  $this->quProsesDataForm;
    }
    public function setQuProsesDataForm($quProsesDataForm){
        $this->quProsesDataForm = $quProsesDataForm;
        return $this;
    }

    public function getVariables(){

        return  $this->variables();
    }

    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/qu_admin_form');
        return  $model->setVariables($this->getVariables());

    }


    private  function check($dataDb){
        if(isset($dataDb[$this->KeyIdParent])){
            return $dataDb[$this->KeyIdParent];
        }else{
            return 0;
        }
    }




}
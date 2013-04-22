<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use QuAdmin\Object\Object;
use QuAdmin\Util;

use Zend\View\Model\ViewModel;


class AddController extends AbstractController
{


    public function variables()
    {
        /**
         * Conserve Local Model
         */
        $this->getModelBreadCrumb()->setQuAdminModelOptions($this->getOptions());
        $this->getModelBreadCrumb()->breadCrumb($this->getId(),false,$this->getModel());

        $LinkerModels = $this->getQuAdminModelOptions()->getLinkerModels();
        if(count($LinkerModels)){
            foreach($LinkerModels as $LinkerModel){
                if(isset($LinkerModel['model']) and $LinkerModel['model'] == 'qu_'.$this->getModel().'_model'){
                    $this->setOptions($this->Service('qu_'.$this->getModel().'_model'));
                    $this->setQuAdminModelOptions($this->getOptions());
                }
            }
        }



        $this->match('qu_admin_navigation')->setParam('model',null);
        $this->match('qu_admin_navigation')->setParam('id',0);
        $this->match('qu_admin_navigation')->setParam('action',null);


        $this->getModelAdd()->setQuAdminModelOptions($this->getOptions());
        $this->getField();

        $dataController = array(
            'action'        => 'add',
            'id'            => $this->getId(),
            'lang'          => $this->getLang(),
            'route'         => $this->getRoute(),
            'options'       => $this->getOptions(),
            'id_parent'     => $this->getIdParent(),
            'model'         => $this->getModel(),
            'key'           => $this->key,
            'PathTemplateRender' => $this->getPathTemplateRender(),
        );


        $this->getForm()->setOptionsForm($this->getOptions());
        $this->getForm()->addQuFormOptions($dataController,$this->getModelForm(),$this->getServiceLocator());

        $dataController += array('form' => $this->getForm());

        //Action for post Add
        if($this->getIsPost()){
            $dataPost = $this->getPost();
            if($dataPost['close'] != ''){

                // @TODO improve!
                if($this->getOptions()->getDocuments())
                {
                    $plupload = $this->Service('plupload_service');
                    $this->getEventManager()->trigger(__FUNCTION__.'.pre', $this, array(
                        'id'      => $this->getId(),
                        'options' => $this->getOptions(),
                        'plupload'  =>  $plupload
                    ));
                }

                return $this->getToRoute($this->getRoute(),array(
                    'action'    => 'index',
                    'id'        => $this->getId(),
                    'lang'      => $this->getLang(),
                    'model'     => $this->getModel(),
                ));

            }
            /**
             * Process by Data
             */
            $DataForm = $this->getForm()->prosesDataForm($dataPost);


            if($this->KeyDate)      $DataForm[$this->KeyDate] = $this->getDate();
            if($this->KeyModified)  $DataForm[$this->KeyModified] = $this->getDate();
            if($this->KeyIdAuthor)  $DataForm[$this->KeyIdAuthor] = $this->getUser();
            if($this->KeyIdParent)  $DataForm[$this->KeyIdParent] = $this->getId();
            if($this->KeyLang)      $DataForm[$this->KeyLang] = $this->getLang();
            if($this->KeyLevel)     $DataForm[$this->KeyLevel] = $breadCrumb->getLevel();
            if($this->KeyPath)      $DataForm[$this->KeyPath] =  $breadCrumb->getPath(1). Util::urlFilter(@$DataForm[$this->KeyTitle]);


            if(!isset($DataForm['error'])){

                $redirect_id = $this->getModelAdd()->insert($DataForm);

                // @TODO improve!
                if($this->getOptions()->getDocuments())
                {
                    $plupload = $this->Service('plupload_service');
                    $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array(
                        'id'        => $redirect_id,
                        'options'   => $this->getOptions(),
                        'plupload'  =>  $plupload
                    ));
                }

                if($redirect_id){
                    if($dataPost['save'] != ''){
                        $this->getMessage(array('type'=>$this->getTranslate('AddSaveClassType'),'message' =>$this->getTranslate('AddSaveMessage')));
                        return $this->getToRoute($dataController['route'],array(
                            'action'        => 'edit',
                            'id'            => $redirect_id,
                            'lang'          => $this->getLang(),
                            'model'         => $this->getModel(),
                        ));
                    }
                    elseif($dataPost['saveandclose'] != ''){
                        $this->getMessage(array('type'=>$this->getTranslate('AddSaveCloseClassType'), 'message'=>$this->getTranslate('AddSaveCloseMessage')));
                        return $this->getToRoute($this->getRoute(),array(
                            'id'        => $this->getId(),
                            'lang'      =>$this->getLang(),
                            'model'     => $this->getModel(),
                            'action'    => 'index',
                        ));
                    }
                }

            }elseif(isset($DataForm['error']) and $DataForm['error']){

                $dataController['error'] = $DataForm;
            }



        }
        return $dataController;
    }

    public function getVariables(){

        return  $this->variables();
    }

    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/qu_admin_form');
        return  $model->setVariables($this->getVariables());

    }



}
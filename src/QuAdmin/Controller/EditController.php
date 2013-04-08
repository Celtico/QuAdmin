<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;

class EditController extends AbstractController
{

    protected $quProsesDataForm;

    public function variables()
    {
        $dataController = array(
            'action'        => 'edit',
            'id_parent'     => $this->getIdParent(),
            'id'            => $this->getId(),
            'lang'          => $this->getLang(),
            'route'         => $this->getRoute(),
            'options'       => $this->getOptions(),
            'key'           => $this->key,
            'PathTemplateRender' => $this->getPathTemplateRender()
        );


        $this->getModelEdit()->setQuAdminModelOptions($this->getOptions());

        $dataDb = $this->getModelEdit()->findByLangIdByLang($this->getLang(),$this->getId());

        $this->getForm()->setOptionsForm($this->getOptions());

        $this->getForm()->addQuFormOptions($dataDb,$this->getModelForm(),$this->getServiceLocator());

        $dataController += array('form' => $this->getForm());

        if($this->getIsPost()){

            $dataPost = $this->getPost();

            if($dataPost['close'] != '')
            {
                $this->getMessage(array('type' =>$this->getTranslate('EditCloseClassType'),'message' =>$this->getTranslate('EditCloseMessage')));
                return $this->getToRoute($this->getRoute(),array('id'=> $this->check($dataDb),'lang'=>$this->getLang()));
            }
            /**
             * Process by Data
             */
            $DataForm = $this->getForm()->prosesDataForm($dataPost);

            if($this->KeyModified)  $DataForm[$this->KeyModified] = $this->getDate();
            if($this->KeyIdAuthor)  $DataForm[$this->KeyIdAuthor] = $this->getUser();
            if($this->KeyLang)      $DataForm[$this->KeyLang]     = $this->getLang();
            $DataForm[$this->KeyId] = $this->getId();

            $data = false;

            if(!isset($DataForm['error'])){

                $data = $this->getModelEdit()->update($DataForm,$this->getLang());
            }

            if(!isset($DataForm['error'])){
                if($dataPost['save'] != '')
                {
                    $this->getMessage(array('type'=>$this->getTranslate('EditSaveClassType'),'message' =>$this->getTranslate('EditSaveMessage')));
                    return $this->getToRoute($this->getRoute(),array('action'=>'edit','id'=>$this->getId(),'lang'=>$this->getLang()));
                }
                elseif($dataPost['saveandclose'] != '')
                {
                    $this->getMessage(array('type'=>$this->getTranslate('EditSaveCloseClassType'),'message'=>$this->getTranslate('EditSaveCloseMessage')));
                    return $this->getToRoute($this->getRoute(),array('id' =>  $this->check($dataDb),'lang'=>$this->getLang()));
                }
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
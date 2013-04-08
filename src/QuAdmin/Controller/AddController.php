<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use QuAdmin\Util;
use Zend\View\Model\ViewModel;

class AddController extends AbstractController
{

    public function variables()
    {
        $dataController = array(
            'action'        => 'add',
            'id'            => $this->getId(),
            'lang'          => $this->getLang(),
            'route'         => $this->getRoute(),
            'options'       => $this->getOptions(),
            'id_parent'     => $this->getIdParent(),
            'key'           => $this->key,
            'PathTemplateRender' => $this->getPathTemplateRender()
        );


        $this->getModelAdd()->setQuAdminModelOptions($this->getOptions());

        $breadCrumb = $this->getModelAdd()->breadCrumb($this->getId());

        $this->getForm()->setOptionsForm($this->getOptions());

        $this->getForm()->addQuFormOptions($dataController,$this->getModelForm(),$this->getServiceLocator());

        $dataController += array('form' => $this->getForm());

        //Action for post Add
        if($this->getIsPost()){
            $dataPost = $this->getPost();
            if($dataPost['close'] != ''){
                $this->getMessage(array('type' =>$this->getTranslate('AddCloseClassType'),'message' =>$this->getTranslate('AddCloseMessage')));
                return $this->getToRoute($this->getRoute(),array('id' => @$dataController['id_parent'],'lang'=>$this->getLang()));
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

            $redirect_id = false;

            if(!isset($DataForm['error'])){
               $redirect_id = $this->getModelAdd()->insert($DataForm);
            }

            if($redirect_id){
                if($dataPost['save'] != ''){
                    $this->getMessage(array('type'=>$this->getTranslate('AddSaveClassType'),'message' =>$this->getTranslate('AddSaveMessage')));
                    return $this->getToRoute( $dataController['route'],array('action'=> 'edit','id' => $redirect_id,'lang' => $this->getLang()));
                }
                elseif($dataPost['saveandclose'] != ''){
                    $this->getMessage(array('type'=>$this->getTranslate('AddSaveCloseClassType'), 'message'=>$this->getTranslate('AddSaveCloseMessage')));
                    return $this->getToRoute($this->getRoute(),array( 'id' => $this->getId(), 'lang'=>$this->getLang()));
                }
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
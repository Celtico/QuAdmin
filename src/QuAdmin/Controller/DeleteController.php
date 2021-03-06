<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;

class DeleteController extends AbstractController
{
    public function variables()
    {

        $LinkerModels = $this->getQuAdminModelOptions()->getLinkerModels();
        if(count($LinkerModels)){
            foreach($LinkerModels as $LinkerModel){
                if(isset($LinkerModel['model']) and $LinkerModel['model'] == 'qu_'.$this->getModel().'_model'){
                    $this->setOptions($this->Service('qu_'.$this->getModel().'_model'));
                    $this->setQuAdminModelOptions($this->getOptions());
                }
            }
        }

        $this->getModelDelete()->setQuAdminModelOptions($this->getOptions());
        $this->getField();

        // @TODO improve!
        if($this->getOptions()->getDocuments())
        {
            //$plupload = $this->Service('plupload_service');
            //$this->getModelDelete()->setPlupload($plupload);
        }

        $redirectBack =  array('action'=>'index','id'=>$this->getId(),'model'=>$this->getModel(),'lang'=>$this->getLang());
        if($this->getIsPost()){
            $Action = $this->getPost();
            if($Action['checkRow'] == ''){
                $this->getMessage(array('type'=> $this->getTranslate('DeleteNotCheckedClassType'),'message' => $this->getTranslate('DeleteNotCheckedMessage')));
                return   $this->getToRoute($this->getRoute(),$redirectBack);
            }
            if($Action['action'] == 'delete'){
                $Delete = array();
                foreach($Action['checkRow'] as $idCheck){
                    $Delete[] = $this->getModelDelete()->findByIdLang($idCheck);
                }
                return array(
                    'Delete' => $Delete,
                    'options' => $this->getOptions(),
                    'id' => $this->getId(),
                    'lang' =>  $this->getLang(),
                    'route' => $this->getRoute(),
                    'model'=> $this->getModel(),
                    'key' => $this->key,
                );

           }elseif($Action['delete'] != ''){
               foreach($Action['checkRow'] as $idCheck){
                   $this->getModelDelete()->remove($idCheck,$this->getOptions());
               }
               $this->getMessage(array('type'=> $this->getTranslate('DeleteClassType'),'message' => $this->getTranslate('DeleteMessage')));
               return   $this->getToRoute($this->getRoute(),$redirectBack);

           }elseif($Action['cancel'] != ''){
               $this->getMessage(array('type'=> $this->getTranslate('DeleteCancelClassType'),'message' => $this->getTranslate('DeleteCancelMessage')));
               return   $this->getToRoute($this->getRoute(),$redirectBack);

           }
       }elseif($this->getIdParent() != 0){
           $Delete = array($this->getModelDelete()->findByIdLang($this->getIdParent()));
           return array(
               'Delete' => $Delete,
               'options' => $this->getOptions(),
               'id' => $this->getId(),
               'lang' =>  $this->getLang(),
               'route' => $this->getRoute(),
               'model'         => $this->getModel(),
               'key' => $this->key,
           );
       }else{
          // $this->getMessage(array('type'=> $this->getTranslate('DeleteNotCheckedClassType'),'message' => $this->getTranslate('DeleteNotCheckedMessage')));
           return   $this->getToRoute($this->getRoute(),$redirectBack);

        }
        return false;
    }


    public function getVariables(){

        return  $this->variables();
    }

    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/delete');
        return  $model->setVariables($this->getVariables());

    }
}
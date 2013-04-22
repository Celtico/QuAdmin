<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;

class DuplicateController extends AbstractController
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

        $this->getModelDuplicate()->setQuAdminModelOptions($this->getOptions());
        $this->getField();

        $redirectBack =  array('action'=>'index','id'=>$this->getId(),'model'=>$this->getModel(),'lang'=>$this->getLang());
        if($this->getIsPost()){
            $dataPost = $this->getPost();
            if($dataPost['checkRow'] == ''){
                $this->getMessage(array('type'=>$this->getTranslate('DuplicateNotCheckedClassType'),'message' =>$this->getTranslate('DuplicateNotCheckedMessage')));
                return
                    $this->getToRoute($this->getRoute(), $redirectBack);
            }
            if($dataPost['action'] == 'duplicate'){
                $Duplicate = array();
                foreach($dataPost['checkRow'] as $idCheck){
                    $Duplicate[] = $this->getModelDuplicate()->findByIdLang($idCheck);
                }
                return array(
                    'Duplicate' => $Duplicate,
                    'options' => $this->getOptions(),
                    'id' => $this->getId(),
                    'lang'=>$this->getLang(),
                    'route'=> $this->getRoute(),
                    'key' => $this->key,
                    'model' => $this->getModel(),
                );
            }elseif($dataPost['duplicate'] != ''){

                foreach($dataPost['checkRow'] as $idCheck){
                    $this->getModelDuplicate()->duplicate($idCheck,$this->getOptions());
                }

                $this->getMessage(array('type' => $this->getTranslate('DuplicateClassType'), 'message' => $this->getTranslate('DuplicateMessage')));
                return
                    $this->getToRoute($this->getRoute(), $redirectBack);

            }elseif($dataPost['cancel'] != ''){
                $this->getMessage(array('type'=>$this->getTranslate('DuplicateCancelClassType'),'message'=>$this->getTranslate('DuplicateCancelMessage')));
                return
                    $this->getToRoute($this->getRoute(), $redirectBack);
            }
        }elseif($this->getIdParent() != 0){
            $Duplicate = array($this->getModelDuplicate()->findByIdLang($this->getIdParent()));
            return array(
                'Duplicate' => $Duplicate,
                'options' => $this->getOptions(),
                'id' => $this->getId(),
                'lang'=> $this->getLang(),
                'route'=> $this->getRoute(),
                'key' => $this->key,
                'model' => $this->getModel(),
            );
        }else{
            $this->getMessage(array('type' =>$this->getTranslate('DuplicateNotCheckedClassType'), 'message'=>$this->getTranslate('DuplicateNotCheckedMessage')));
            return
                $this->getToRoute($this->getRoute(), $redirectBack);
        }
        return false;
    }

    public function getVariables(){

        return  $this->variables();
    }

    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/duplicate');
        return  $model->setVariables($this->getVariables());

    }

}
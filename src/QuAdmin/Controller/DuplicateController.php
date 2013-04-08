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
        $this->getModelDuplicate()->setQuAdminModelOptions($this->getOptions());
        $redirectBack =  array('action'=>'index','id'=>$this->getId(),'lang'=>$this->getLang());
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
                );
            }elseif($dataPost['duplicate'] != ''){
                foreach($dataPost['checkRow'] as $idCheck){
                    $this->getModelDuplicate()->duplicate($idCheck);
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
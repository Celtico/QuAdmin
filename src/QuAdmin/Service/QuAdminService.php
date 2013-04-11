<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuAdminService implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;
    protected $index;
    protected $add;
    protected $edit;
    protected $delete;
    protected $duplicate;
    protected $ajax;
    protected $quAdminModelOptions;
    protected $modelAdd;
    protected $upload;

    public function getUpload()
    {
        if (!$this->upload) {
            $this->setUpload(
                $this->getServiceLocator()->get('qu_admin_controller_upload_ajax')
            );
        }
        return $this->upload;
    }
    public function setUpload($upload)
    {
        $this->upload = $upload;
        return $this;
    }

    public function getEdit()
    {
        if (!$this->edit) {
            $this->setEdit(
                $this->getServiceLocator()->get('qu_admin_controller_edit')
            );
        }
        return $this->edit;
    }
    public function setEdit($edit)
    {
        $this->edit = $edit;
        return $this;
    }

    public function getDelete()
    {
        if (!$this->delete) {
            $this->setDelete(
                $this->getServiceLocator()->get('qu_admin_controller_delete')
            );
        }
        return $this->delete;
    }
    public function setDelete($delete)
    {
        $this->delete = $delete;
        return $this;
    }

    public function getDuplicate()
    {
        if (!$this->duplicate) {
            $this->setDuplicate(
                $this->getServiceLocator()->get('qu_admin_controller_duplicate')
            );
        }
        return $this->duplicate;
    }
    public function setDuplicate($duplicate)
    {
        $this->duplicate = $duplicate;
        return $this;
    }


    public function getIndex()
    {
        if (!$this->index) {
            $this->setIndex(
                $this->getServiceLocator()->get('qu_admin_controller_index')
            );
        }
        return $this->index;
    }
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    public function getAjax()
    {
        if (!$this->ajax) {
            $this->setAjax(
                $this->getServiceLocator()->get('qu_admin_controller_index_ajax')
            );
        }
        return $this->ajax;
    }
    public function setAjax($ajax)
    {
        $this->ajax = $ajax;
        return $this;
    }

    public function getAdd()
    {
        if (!$this->add) {
            $this->setAdd(
                $this->getServiceLocator()->get('qu_admin_controller_add')
            );
        }
        return $this->add;
    }
    public function setAdd($add)
    {
        $this->add = $add;
        return $this;
    }


    public function t($text = ''){
        $translator = $this->serviceLocator->get('translator');
        return $translator->Translate($text);
    }

    public function userId()
    {
        $user = $this->serviceLocator->get('zfcuser_auth_service')->getIdentity();

        if(method_exists($user,'getId')){
            return $user->getId();
        }else{
            echo 'error security'; die();
        }

    }

    public  function getModelAdd()
    {
        if(!$this->modelAdd){
            $this->setModelAdd(
                $this->serviceLocator->get('qu_admin_model_add')
            );
        }
        return $this->modelAdd;
    }

    public function setModelAdd($modelAdd)
    {
        $this->modelAdd = $modelAdd;
    }

    public function getQuAdminModelOptions()
    {
        return $this->quAdminModelOptions;
    }
    public function setQuAdminModelOptions($quAdminModelOptions)
    {
        $this->quAdminModelOptions = $quAdminModelOptions;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }


}
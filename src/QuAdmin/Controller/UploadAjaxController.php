<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;
use QuPlupload\Options\PluploadOptions;

class UploadAjaxController extends AbstractController
{


    public function variables()
    {
        $options = $this->getOptions();
        $docs    = $options->getDocuments();

        $op = new PluploadOptions;
        $op->setTableName($docs['tableName']);
        $op->setDirUploadAbsolute($docs['DirUploadAbsolute']);
        $op->setThumbResize($docs['ThumbResize']);
        $op->setResize($docs['Resize']);
        $op->setDirUpload($docs['DirUpload']);


        $PluploadService =  $this->Service('plupload_service');
        $PluploadService->setPluploadOptions($op);


        if ($this->app()->getRequest()->isPost()){
            $data = array_merge_recursive(
                $this->app()->getRequest()->getPost()->toArray(),
                $this->app()->getRequest()->getFiles()->toArray()
            );
            $PluploadService->uploadPlupload($this->getId(),$data,$options->getTableName());
        }

        return array();
    }

    public function loadAction()
    {
        $view        = new ViewModel();
        $view->id    = $this->getId();
        $view->options = $this->getOptions();
        $view->route = $this->getRoute();
        $view->setTemplate('qu-admin/qu-plupload/load');
        return $view->setTerminal(true);
    }

    public function removeAction()
    {

        $options = $this->getOptions();
        $docs    = $options->getDocuments();

        $op = new PluploadOptions;
        $op->setTableName($docs['tableName']);
        $op->setDirUploadAbsolute($docs['DirUploadAbsolute']);
        $op->setThumbResize($docs['ThumbResize']);
        $op->setResize($docs['Resize']);
        $op->setDirUpload($docs['DirUpload']);

        $PluploadService =  $this->Service('plupload_service');
        $PluploadService->setPluploadOptions($op);
        $PluploadService->PluploadRemove($this->getId());

        $view          = new ViewModel();
        $view->id      = $this->getIdParent();
        $view->options = $this->getOptions();
        $view->route = $this->getRoute();
        $view->setTemplate('qu-admin/qu-plupload/remove');
        return $view->setTerminal(true);
    }

    public function getVariables(){

        return  $this->variables();
    }
    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-plupload/upload');
        $model->setVariables($this->getVariables());
        return  $model->setTerminal(true);
    }


}

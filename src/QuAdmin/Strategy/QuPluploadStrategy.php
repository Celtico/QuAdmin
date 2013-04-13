<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Strategy;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response as HttpResponse;
use QuPlupload\Options\PluploadOptions;
use QuPlupload\Service\PluploadService;

/**
 * Class QuAdminStrategy
 * @package QuAdmin\Strategy
 */
class QuPluploadStrategy implements ListenerAggregateInterface
{

    protected $options;
    protected $listeners = array();
    protected $serviceLocator;


    public function attach(EventManagerInterface $events)
    {

       $event = $events->getSharedManager();

       $event->attach('QuAdmin\Controller\AddController', 'variables.pre', function($e) {

           $params    = $e->getParams();

           $id_parent = $params['id'];
           $options   = $params['options'];
           $plupload  = $params['plupload'];

           $docs      = $options->getDocuments();
           $model     = $options->getTableName();

           $op = new PluploadOptions;
           $op->setTableName($docs['tableName']);
           $op->setDirUploadAbsolute($docs['DirUploadAbsolute']);
           $op->setThumbResize($docs['ThumbResize']);
           $op->setResize($docs['Resize']);
           $op->setDirUpload($docs['DirUpload']);

           $plupload->setPluploadOptions($op);
           $plupload->pluploadRemoveAll($model,$id_parent);


       }, 1);

        $event->attach('QuAdmin\Controller\AddController', 'variables.post',  function($e)  {

            $params    = $e->getParams();

            $id_parent = $params['id'];
            $options   = $params['options'];
            $plupload  = $params['plupload'];

            $model     = $options->getTableName();
            $docs      = $options->getDocuments();

            $plupload->pluploadUpdate($model,$id_parent);


        }, 1);


        $event->attach('QuAdmin\Model\DeleteMapper', 'postEventRemove', function($e) {

            $params    = $e->getParams();

            $id_parent = $params['id'];
            $options   = $params['options'];
            $plupload  = $params['plupload'];

            $docs      = $options->getDocuments();
            $model     = $options->getTableName();

            $op = new PluploadOptions;
            $op->setTableName($docs['tableName']);
            $op->setDirUploadAbsolute($docs['DirUploadAbsolute']);
            $op->setThumbResize($docs['ThumbResize']);
            $op->setResize($docs['Resize']);
            $op->setDirUpload($docs['DirUpload']);


            $plupload->setPluploadOptions($op);
            $plupload->pluploadRemoveAll($model,$id_parent);

        }, 1);


    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /*@TODO  $this in php 5.3 not work :( */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}

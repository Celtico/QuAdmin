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
       $event->attach('QuAdmin', 'variables', function($e){

           $params = $e->getParams();

           if(isset($params['id'])){
               $plupload_service =  $params['service'];
               $id    = $params['id'];
               $model = $params['model'];
               $plupload_service->pluploadUpdate($id,$model);
           }

           if(isset($params['close'])){

               $plupload_service =  $params['service'];
               $model = $params['model'];
               $docs  =  $params['options'];

               $op = new PluploadOptions;
               $op->setTableName($docs['tableName']);
               $op->setDirUploadAbsolute($docs['DirUploadAbsolute']);
               $op->setThumbResize($docs['ThumbResize']);
               $op->setResize($docs['Resize']);
               $op->setDirUpload($docs['DirUpload']);

               $plupload_service->setPluploadOptions($op);
               $plupload_service->pluploadRemoveAll($model);
           }

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

    public function inject($e)
    {

    }


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

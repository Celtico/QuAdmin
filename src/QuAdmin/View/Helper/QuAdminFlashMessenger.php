<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

class QuAdminFlashMessenger extends AbstractHelper
{

    protected $serviceLocator;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return string
     */
    public function __invoke()
    {

        $flash  = $this->serviceLocator->get('ControllerPluginManager')->get('flashMessenger');
        $config = $this->serviceLocator->get('config');
        $class  = $config['QuAdminConfig']['QuFlashMCss'];
        $flash->setNamespace('QuAdmin');

        if(count($flash))
        {
            foreach($flash as $msg){}

            if(isset($msg['type'])){
               $type = $msg['type'];
            }else{
               $type = '';
            }

            if($class == 'alert'){
                $button = '<button type="button" class="close" data-dismiss="alert">×</button>';
            }else{
                $button = '';
            }

            $message = '
            <div class="'.$class.' '.$type.'">
                '.$button.'
                '.$msg['message'].'
            </div>';
            return $message;
        }
        else
        {
            return false;
        }
    }
}

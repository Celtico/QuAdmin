<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuAdminUserId extends AbstractHelper
{

    protected $serviceLocator;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }


    public function __invoke(){

        $zfcUser =  $this->serviceLocator->get('zfcuser_auth_service')->getIdentity();
        if(method_exists($zfcUser,'getId')){
        return  $zfcUser->getId();
        }else{
         return false;
        }
    }

}

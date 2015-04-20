<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuAdminUserName extends AbstractHelper
{

    protected $serviceLocator;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function __invoke($id){

        $zfcUser =  $this->serviceLocator->get('zfcuser_user_service')->getUserMapper();
        if($zfcUser->findById($id)){
            // $zfcUser->findById($id)->getDisplayName() . ' / ' . $zfcUser->findById($id)->getUserName();
        return  $zfcUser->findById($id)->getDisplayName();
        }else{
            return false;
        }
    }

}
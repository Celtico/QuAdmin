<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuAdminStatus extends AbstractHelper
{

    protected $serviceLocator;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }
    /**
     * @param $status
     * @return string
     */
    public function __invoke($status){

        if($status == 'Public'){
            $icon = 'success';
        }elseif($status == 'Privat'){
            $icon = 'warning';
        }elseif($status == 1){
            $status =  'Public';
            $icon = 'success';
        }elseif($status == 0){
            $status =  'Privat';
            $icon = 'warning';
        }else {
            $icon = 'info';
        }

        return ' <span class="stateLabel label label-'.$icon.'">'.$status.'</span>';
    }
}
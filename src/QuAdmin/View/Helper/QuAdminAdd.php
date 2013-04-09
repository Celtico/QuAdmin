<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuAdminAdd extends AbstractHelper
{

    protected $serviceLocator;

    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke($route,$id,$lang,$options,$action)
    {
        if($action == ''){

            $url = $this->view->url($route, array('action'=>'add','id'=>$id,'lang'=>$lang));
            $btn = '
            <a href="'.$url.'" title="Add" class="btn btn-inverse" style="margin:3px 3px 0 0;"> Add </a>
            ';

        }else{

            $btn = '
            <input class="btn"  style="margin:3px 3px 0 0;" value="Close"  name="close" type="submit">
            <input class="btn btn-success"  data-icon="&#xe0c8;" style="margin:3px 3px 0 0;" value="Save" name="save" type="submit">
            <input class="btn btn-inverse"  style="margin:3px 3px 0 0;" value="Save & Close" name="saveandclose" type="submit">
            ';
        }

        return '<div class="buttons-nav">'.$btn.'</div>';
    }
}
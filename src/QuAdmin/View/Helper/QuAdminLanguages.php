<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;


class QuAdminLanguages extends AbstractHelper
{

    protected $service;


    public function __construct($service)
    {
        $this->service = $service;
    }

    public function __invoke($options,$route,$lang,$action,$id,$model)
    {
        $mHelp = $this->service->get('qu_admin_model_languages');
        $o     = $this->service->get('qu_languages_model');
        $mHelp->setQuAdminModelOptions($o);


        if($action == ''){
            $action = 'index';
        }


        if($options->getDefaultLanguage())
        {
            $menu = '';
            foreach( $mHelp->languages() as $key => $language ){
                $menu .= '
            <li>
                <a href="'.$this->view->url($route,array('lang'=>$key,'model'=>$model,'action'=>$action,'id'=>$id)).'" style="margin:3px 3px 0 0;" class="btn'.$this->Active($key,$lang,' active').'">
                    <span class="iconb" data-icon=&#xe07e;></span> <span>'.$language.'</span>
                </a>

            </li>' ;
            }
            return '<ul class="languages">'.$menu.'</ul>';
        }
        return false;
    }

    public function Active($param,$active,$css)
    {
        if($param == $active){
            $active = $css;
        }else{
            $active = '';
        }
        return $active;
    }
}
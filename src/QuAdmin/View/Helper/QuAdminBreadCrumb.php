<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use QuAdmin\Util;

/**
 * Class QuAdminBreadCrumb
 * @package QuAdmin\View\Helper
 */
class QuAdminBreadCrumb extends AbstractHelper
{

    protected $serviceLocator;

    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke($options,$id,$route,$lang)
    {
        $model_helper = $this->serviceLocator->get('qu_admin_model_helper');
        $model_helper->setQuAdminModelOptions($options);
        $breadCrumb = $model_helper->breadCrumb($id,$lang);
        $this->getField($options->getTableKeyFields());
        $menu = '';
        $count = 0;

        if(!count($breadCrumb))
        {
            $menu .= '
            <li>
                <a href="'.$this->view->url($route).'">
                    Index<span class="arrow-r"></span>
                </a>
            </li>';
        }else{
            foreach($breadCrumb as $link)
            {
                $count++;

               if(isset($link['id_parent'])){
                    $idParent =  $link['id'];
               }else{
                   $idParent  = 0;
               }
               if($count == 1){
                    $menu .= '
                    <li>
                        <a href="'.$this->view->url($route,array('id'=>$idParent,'action'=>'index','lang'=>$lang)).'">
                            Index
                            <span class="arrow-r"></span>
                        </a>
                    </li>';
               }else{

                    $menu .= '
                    <li '.Util::Active($link['id'],$id,'class="current active"').'>
                        <a href="'.$this->view->url($route,array('id'=>$idParent,'action'=>'index','lang'=>$lang)).'">
                            '.$link['title'].'
                            <span class="arrow-r"></span>
                        </a>
                    </li>';
               }
            }
        }
        $this->view->placeholder('bread-crumbs')->set($menu);
    }
    public function getField($TableFields)
    {
        foreach($TableFields as $k => $e){
            $this->$k = $e;
        }
        return $this;
    }
}
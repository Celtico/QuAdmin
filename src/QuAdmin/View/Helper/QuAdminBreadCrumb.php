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
    protected $id_parent;
    protected $id_parent_local;

    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke($options,$id,$route,$lang,$model,$mergeBreadCrumbModels = false)
    {

        $model_helper = $this->serviceLocator->get('qu_admin_model_bread_crumb');
        $keys = $options->getTableKeyFields();

        if($mergeBreadCrumbModels)
        {
            //$model_helper->setTableKeyFields($keys);
            //$model_helper->setQuAdminModelOptions($options);
            //$bread_model = $model_helper->breadCrumb($id,$lang,$model,$options->getTableName());
            //$bread_model = $this->breadCrumbModel($route,$lang,$keys,$id,end($bread_model->get()));

            $bread_local = $this->breadCrumb($route,$lang,$keys,$this->id_parent,$mergeBreadCrumbModels);
            $breadCrumb  = $bread_local;
        }
        else
        {
            $bread_local = $model_helper->breadCrumb($id,$lang,$model);
            $breadCrumb  = $this->breadCrumb($route,$lang,$keys,$id,$bread_local->get());
        }

        $this->view->placeholder('bread-crumbs')->set($breadCrumb);
    }
    public function breadCrumbModel($route,$lang,$keys,$id,$link)
    {
        $menu = '';

            if(isset($keys['key_id_parent']) and isset($link[$keys['key_id_parent']])){

                $idParent        =  $link['id'];
                $this->id_parent =  $link["id_parent"];
                $title = $link['title'];
                if($title == ''){
                    $title = '---';
                }
                $menu .= '
                <li '.Util::Active($link['id'],$id,'class="current active"').'>
                    <a href="'.$this->view->url($route,array('id'=>$idParent,'model'=>null,'action'=>'index','lang'=>$lang)).'">
                        '.$title.'
                        <span class="arrow-r"></span>
                    </a>
                </li>';
            }

        return $menu;
    }
    public function breadCrumb($route,$lang,$keys,$id,$breadCrumb)
    {

        $menu = '';
        if(!count($breadCrumb))
        {

            $menu .= '
            <li>
                <a href="'.$this->view->url($route).'">
                    Index<span class="arrow-r"></span>
                </a>
            </li>';


        }else{

            $level = 0;
            foreach($breadCrumb as $link)
            {

                $level++;
                if(isset($keys['key_id_parent']) and isset($link[$keys['key_id_parent']])){

                    $idParent  =  $link['id'];
                    $this->id_parent_local[$level] = $link[$keys['key_id_parent']];

                }else{

                    $idParent  = 0;

                }
                if($level == 1){

                    $menu .= '
                    <li>
                        <a href="'.$this->view->url($route,array('id'=>$idParent,'model'=>null,'action'=>'index','lang'=>$lang)).'">
                            Index
                            <span class="arrow-r"></span>
                        </a>
                    </li>';

                }else{

                    $title = $link['title'];
                    if($title == ''){
                        $title = '---';
                    }

                    $menu .= '
                    <li '.Util::Active($link['id'],$id,'class="current active"').'>
                        <a href="'.$this->view->url($route,array('id'=>$idParent,'model'=>null,'action'=>'index','lang'=>$lang)).'">
                            '.$title.'
                            <span class="arrow-r"></span>
                        </a>
                    </li>';
                }
            }

        }
        return $menu;
    }

}
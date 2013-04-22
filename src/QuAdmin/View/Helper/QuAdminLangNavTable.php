<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class QuAdminLangNavTable
 * @package QuAdmin\View\Helper
 */
class QuAdminLangNavTable extends AbstractHelper
{

    /**
     * @var
     */
    protected  $menu_li;
    /**
     * @var
     */
    protected  $menu_td;
    /**
     * @var
     */
    protected  $count_child;
    /**
     * @var
     */
    protected  $link_model;


    /**
     * @var
     */
    protected $service;


    public function __construct($service)
    {
        $this->service = $service;
    }


    /**
     * @param $id
     * @param $lang
     * @param $options
     * @param $route
     * @param $model
     * @param $id_parent
     * @return $this
     */
    public function __invoke($id,$lang,$options,$route,$model,$id_parent){

        $menu_li    = false;
        $menu_td    = false;
        $link_model = false;

        $langOptions = $this->service->get('qu_languages_model');

        $mapperBreadCrumb  = $this->service->get('qu_admin_model_bread_crumb');
        $mapperBreadCrumb->setQuAdminModelOptions($options);

        $languages = $this->service->get('qu_admin_model_languages');
        $languages->setQuAdminModelOptions($langOptions);

        $localTable = $options->getTableName();
        $this->getField($options->getTableKeyFields());


        if($options->getDefaultLanguage())
        {

            foreach($languages->languages() as $key => $language){

                $langActive = $mapperBreadCrumb->langActive($id,$key,$localTable);

                if(!$langActive){

                    $menu_li .= '
                    <li>
                    <a href="'.$this->view->url($route,array('action'=>'edit', 'id' =>$id,'id_parent' => $id_parent,'lang'=>$key)).'">
                        <span class="icon-flag-2"></span>
                        Translate '.$language.
                        '</a>
                    </li>';

                    $menu_td .= '
                    <a class="badge badge-warning"  href="'.$this->view->url($route,array('action'=>'edit','model'=>$model, 'id' => $id,'id_parent' => $id_parent,'lang'=>$key)).'">
                        '.$key.'
                    </a>';

                }else{

                    $menu_td .= '
                    <a class="badge badge-success"  href="'.$this->view->url($route,array('action'=>'edit','model'=>$model,  'id' => $id,'id_parent' => $id_parent,'lang'=>$key)).'">
                         '.$key.'
                    </a>';
                }
            }
            $this->menu_li = $menu_li;
            $this->menu_td = '<td class="center">'.$menu_td.'</td>';
        }


        /*
         * Load model
         * */
        $link_child_count = '';
        $link_model_count = '';
        $LinkerModels = $options->getLinkerModels();
        if(count($LinkerModels)){
            foreach($LinkerModels as $LinkerModel){

                if($LinkerModel['level'] === true)
                {
                    $model = str_replace('qu_','', str_replace('_model','',$LinkerModel['model']));

                    $options->setTableName($LinkerModel['table']);
                    $count = $mapperBreadCrumb->countChild($id,$options->getTableName());

                    $link_model .= '
                     <td class="add">
                        <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'add','model'=> $model,'id' => $id)).'" class="btn td-child-add btn-small" title="Add">
                            <span class="iconb" data-icon="&#xe14a;"></span>
                            </a>';

                    if($count){

                        $link_model .= '
                            <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'index','model'=> $model,'id' => $id)).'" class="btn td-child  btn-inverse">
                                <span class="iconb" data-icon="&#xe016;"></span>  '.$count.'
                            </a>
                         </td>
                        ';
                    }
                }elseif($LinkerModel['level'] == $mapperBreadCrumb->getLevel()){

                    $model = str_replace('qu_','', str_replace('_model','',$LinkerModel['model']));

                    $options->setTableName($LinkerModel['table']);
                    $count = $mapperBreadCrumb->countChild($id,$options->getTableName());

                    $link_model .= '
                     <td class="add">
                        <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'add','model'=> $model,'id' => $id)).'" class="btn td-child-add btn-small" title="Add">
                            <span class="iconb" data-icon="&#xe14a;"></span>
                        </a>';

                    if($count){

                        $link_model_count .= '
                            <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'index','model'=> $model,'id' => $id)).'" class="btn td-child  btn-inverse">
                                <span class="iconb" data-icon="&#xe016;"></span>  '.$count.'
                            </a>

                        ';
                    }
                }
            }
        }




        $options->setTableName($localTable);
        $count = $mapperBreadCrumb->countChild($id,$options->getTableName());

            $count_child= '
             <td class="add">
                <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'add','model'=>null, 'id' => $id)).'" class="btn td-child-add btn-small" title="Add">
                    <span class="iconb" data-icon="&#xe14a;"></span>
                   </a>';

        if($count){

            $link_child_count .= '
                <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'index','model'=>null, 'id' => $id)).'" class="btn td-child  btn-inverse">
                    <span class="iconb" data-icon="&#xe016;"></span> '.$count.'
                </a>

            ';
        }


        $this->count_child = $count_child . $link_child_count .'</td>';
        $this->link_model  = $link_model . $link_model_count .'</td>';

        return $this;
    }

    public function getField($TableFields)
    {
        $fil = new \Zend\Filter\Word\UnderscoreToCamelCase();
        foreach($TableFields as $k => $e){
            $k = $fil->filter($k);

            $this->$k = $e;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNavMenuLi()
    {
        return $this->menu_li;
    }

    /**
     * @return mixed
     */
    public function getLinkModel()
    {
        return $this->link_model;
    }

    /**
     * @return mixed
     */
    public function getNavMenuTd()
    {
        return $this->menu_td;
    }

    /**
     * @return mixed
     */
    public function getCountChild()
    {
        return $this->count_child;
    }


}
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
     * @return array
     */
    public function __invoke($id,$lang,$options,$route){

        $menu_li = false;
        $menu_td = false;

        $languages = $this->service->get('qu_admin_model_languages');
        $languagesOptions = $this->service->get('qu_languages_model');
        $languages->setQuAdminModelOptions($languagesOptions);
        $this->getField($languagesOptions->getTableKeyFields());

        $mapperHelper = $this->service->get('qu_admin_model_helper');
        $mapperHelper->setQuAdminModelOptions($options);


        if($options->getDefaultLanguage())
        {
            foreach($languages->languages()  as $key => $language){

                if(!$mapperHelper->langActive($id,$key)){

                    $menu_li .= '
                    <li>
                    <a href="'.$this->view->url($route,array('action'=>'edit', 'id' =>$id,'lang'=>$key)).'">
                        <span class="icon-flag-2"></span>
                        Translate '.$language.
                        '</a>
                    </li>';

                    $menu_td .= '
                    <a class="badge badge-warning"  href="'.$this->view->url($route,array('action'=>'edit', 'id' => $id,'lang'=>$key)).'">
                        '.$key.'
                    </a>';

                }else{

                    $menu_td .= '
                    <a class="badge badge-success"  href="'.$this->view->url($route,array('action'=>'edit', 'id' => $id,'lang'=>$key)).'">
                        '.$key.'
                    </a>';
                }
            }
            $this->menu_li = $menu_li;
            $this->menu_td = '<td class="center">'.$menu_td.'</td>';
        }

        $count_child = false;
        $count = $mapperHelper->countChild($id);
        if($count != 0)
        {
            $count_child= '
            <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'index','id' => $id)).'" class="btn btn-success">
               '.$count.'
            </a>';
        }
        $this->count_child = $count_child;

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
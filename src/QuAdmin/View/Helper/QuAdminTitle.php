<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuAdminTitle extends AbstractHelper
{


    /**
     * @var null
     */
    protected $pages;

    /**
     * @param null $pages
     */
    public function __construct($pages = null)
    {
        return  $this->pages = $pages;
    }

    public function __invoke()
    {

        $title = $this->getPageActive();

        $icon = false;

        if(isset($title['icon'])){
            $icon  = '<span class="iconb" data-icon="'.$title['icon'].'"></span>';
        }

        if(isset($title['label'])){
            $this->view->headTitle($title['label']);
            $this->view->placeholder('title')->set($icon.$title['label']);
        }
    }

    /**
     * @return null
     */
    public function getPages()
    {
        return  $this->pages;
    }

    /**
     * @return mixed
     */
    public function getPageActive()
    {
        $active = '';
        $pageArray = $this->pages->toArray();
        foreach($this->pages->toArray() as $p){
            $active[$p['route']] = $p['pages'];
        }
        $keyPrimary = array_keys($active);
        foreach($keyPrimary as $test){
            foreach($active[$test] as $ac){
                if($ac['active']){
                    $act = $ac;
                }
            }
        }
        if(isset($act['label'])){
            return $act;
        }elseif(isset($pageArray[0])){
            return $pageArray[0];
        }
        return false;
    }

}
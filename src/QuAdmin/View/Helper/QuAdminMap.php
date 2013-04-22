<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class QuAdminDocuments
 * @package QuAdmin\View\Helper
 */
class QuAdminMap extends AbstractHelper
{

    protected $serviceLocator;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke($id,$options,$model,$route,$lang)
    {
        if(!$model){

        $mapperHelper = $this->serviceLocator->get('qu_admin_model_form');
        $mapperHelper->setQuAdminModelOptions($options);
        $this->getField($options->getTableKeyFields());
        $level = 0;
        $this->recursive($level,$id,$mapperHelper,$model,$options,$route,$lang);

        }
    }

    public function recursive($level,$id,$mapperHelper,$model,$options,$route,$lang){


        $maps = $mapperHelper->findByParentRecursive($id);
        if($maps){
            echo '<ul class="map">';
            foreach($maps as $map){
                if($map[$this->KeyTitle]){
                    $title = $map[$this->KeyTitle];
                }else{
                    $title = '<span class="Unnamed">Unnamed</span>';
                }
                echo '<li>
                    <a href="'.$this->view->url($route,array('id'=>$map[$this->KeyIdParent],'model'=>null,'action'=>'index','lang'=>$lang)).'">
                    <span class="iconb" data-icon=&#xe017;></span>'.
                        $title.
                        $this->recursive($level,$map[$this->KeyId],$mapperHelper,$model,$options,$route,$lang)
                    .'</a>
                    </li>';
            }
            echo '</ul>';
        }


        /**
         * Load Model
         */
        foreach($options->getLinkerModels() as $LinkerModel){
            $maps = $mapperHelper->findByParentRecursive($id,$LinkerModel['table'],$LinkerModel['key_id_parent'],$LinkerModel['key_id']);
            if($maps){
                echo '<ul class="map">';
                foreach($maps as $map){
                    if($map[$LinkerModel['key_title']]){
                        $title = $map[$LinkerModel['key_title']];
                    }else{
                        $title = '<span class="Unnamed">Unnamed</span>';
                    }
                    $model = str_replace('qu_','', str_replace('_model','',$LinkerModel['model']));
                    echo '<li>
                            <a class="model" href="'.$this->view->url($route,array('id'=>$map[$LinkerModel['key_id_parent']],'model'=>$model,'action'=>'index','lang'=>$lang)).'">
                            <span class="iconb" data-icon=&#xe014;></span>'.
                                $LinkerModel['name']  .' | '.  $title .'
                          </a>
                          </li>';
                }
                echo '</ul>';
            }
        }
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
}
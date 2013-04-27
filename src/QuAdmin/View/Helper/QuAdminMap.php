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

        $mapperForm = $this->serviceLocator->get('qu_admin_model_form');
        $mapperForm->setQuAdminModelOptions($options);
        $this->getField($options->getTableKeyFields());
        $level = 0;



        $this->recursive($level,$id,$mapperForm,$model,$options,$route,$lang);


        }
    }

    public function recursive($level,$id,$mapperForm,$model,$options,$route,$lang){

$level++;

        $subModels       = $options->getLinkerModels();
        $local_maps      = $mapperForm->findByParentRecursive($id);


if(count($local_maps) and $level == 1){
    echo '<div id="map-init">';
}

        /* SUB MODEL */


        foreach($subModels as $LinkerModel){
            $maps_model = $mapperForm->findByParentRecursive($id,$LinkerModel['table'],$LinkerModel['key_id_parent'],$LinkerModel['key_id']);
            if($maps_model){

                $ul = 0;

                foreach($maps_model as $map){

                    $ul++;

                    if($ul == '1') echo '
'.$this->space($level).'<ul id="map-'.$level.'" class="map">';


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

                if($ul >= '1') echo '
'.$this->space($level).'</ul>';

            }
        }




        /* BASE */





        if($local_maps){

            $ul = 0;

            foreach($local_maps as $map){

            $ul++;

            if($ul == '1') echo '
'.$this->space($level).'<ul id="map-'.$level.'" class="map">';

                if($map[$this->KeyTitle]){
                    $title = $map[$this->KeyTitle];
                }else{
                    $title = '<span class="Unnamed">Unnamed</span>'."\n";
                }

echo '
'.$this->space($level).'   <li>';
echo '
'.$this->space($level).'        <a href="'.$this->view->url($route,array('id'=>$map[$this->KeyIdParent],'model'=>null,'action'=>'index','lang'=>$lang)).'">
'.$this->space($level).'            <span class="iconb" data-icon=&#xe017;></span> '.$title.'
'.$this->space($level).'        </a>   '.$this->space($level).'
'.$this->space($level).'    ';
echo '
'.$this->recursive($level,$map[$this->KeyId],$mapperForm,$model,$options,$route,$lang).'';
echo '
'.$this->space($level).'   </li>';

            }

            if($ul >= '1') echo '
'.$this->space($level).'</ul>';


        }


        if(count($local_maps) and $level == 1){
            echo '</div>';
        }
    }

    public function space($num)
    {
        $space = ' ';
        for ($i = 1; $i <= $num; $i++) {
            $space .= '   ';
        }
        return $space;
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
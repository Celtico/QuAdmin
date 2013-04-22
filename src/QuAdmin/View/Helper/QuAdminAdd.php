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

    protected $service;

    public function __construct($service){

        $this->service = $service;
    }

    public function __invoke($route,$id,$lang,$options,$action,$model)
    {


        if($action == ''){

            $btn = '<a href="'.$this->view->url($route, array('action'=>'add','model'=>$model,'id'=>$id,'lang'=>$lang)).'" title="Add" class="btn btn-inverse" style="margin:3px 3px 0 0;">
             <span class="iconb" data-icon="&#xe14a;"></span> Add   '.$options->getTableLabel().'
             </a>';

            if($model){
                $LinkerModels = $options->getLinkerModels();
                if(count($LinkerModels)){
                    $mapperBreadCrumb  = $this->service->get('qu_admin_model_bread_crumb');
                    $mapperBreadCrumb->setQuAdminModelOptions($options);
                    foreach($LinkerModels as $LinkerModel)
                    {
                        if($LinkerModel['level'] === true){
                            $model = str_replace('qu_','', str_replace('_model','',$LinkerModel['model']));
                            $btn .= '
                            <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'add','model'=> $model,'id' => $id)).'"  title="Add" class="btn btn-inverse" style="margin:3px 3px 0 0;">
                                <span class="iconb" data-icon="&#xe14a;"></span>  Add  '.$LinkerModel['name'].'
                            </a>
                        ';
                        }elseif($LinkerModel['level'] != 1 and $LinkerModel['level'] == (1-$mapperBreadCrumb->getLevel())){
                            $model = str_replace('qu_','', str_replace('_model','',$LinkerModel['model']));
                            $btn .= '
                            <a href="'.$this->view->url($route,array('lang'=>$lang,'action'=>'add','model'=> $model,'id' => $id)).'"  title="Add" class="btn btn-inverse" style="margin:3px 3px 0 0;">
                                <span class="iconb" data-icon="&#xe14a;"></span>  Add  '.$LinkerModel['name'].'
                            </a>
                        ';
                        }
                    }
                }
            }


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
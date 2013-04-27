<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use QuPlupload\Util;
use Zend\Validator\File;

class QuPluploadHelpLoad extends AbstractHelper
{

    /**
     * @var
     */
    protected $pluploadService;

    /**
     * @var
     */
    protected $Config;

    /**
     * @param $pluploadService
     * @param $Config
     */
    public function __construct($pluploadService,$Config)
    {
        $this->pluploadService = $pluploadService;
        $this->Config          = $Config;
    }


    /**
     * @param $id
     * @param $route
     * @param $options
     * @return bool|string
     */
    public function __invoke($id,$route,$options,$model)
    {

        $this->pluploadService->setPluploadIdAndModelList($id,$options->getTableName());
        $docs = $options->getDocuments();

        if($model != ''){
            $model = $model.'/';
        }

        $listDb  = $this->pluploadService->getPluploadIdAndModelList();
        $Util    = new Util();

        if(count($listDb) > 0){

            $list = '<ul>';

            foreach($listDb as $a){

                $file      = $docs['DirUploadAbsolute'] . '/' . $a->getName();
                $url       = $docs['DirUpload'] . '/'  . $a->getName();
                $urlSmall  = $docs['DirUpload'] . '/s' . $a->getName();
                $name      = $a->getName();
                $id_in     = $a->getIdPlupload();
                $size      = $Util->formatBytes($a->getSize());
                $ex        = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $Exists    = new File\NotExists($file);

                if($Exists->isValid($file)){


                    if($ex == 'jpg' or $ex == 'jpeg' or $ex == 'gif' or $ex == 'png'){

                        $list .= '
                        <li>
                            <a href="'.$url.'" class="img">
                                <img src="'.$urlSmall.'">
                                <div class="name">'.$name.'</div>
                                <span class="label size">'.$size.'</span>
                                <div id="'.$id_in.'" class="action"><a href="#"></a></div>
                            </a>
                        </li>';

                    }else{

                        $list .= '
                        <li>
                            <a href="'.$url.'" class="doc">
                                <span class="iconb"><span class="ex">.'.$ex.'</span></span>
                                <div class="name">'.$name.'</div>
                                 <span class="label size">'.$size.'</span>
                                <div id="'.$id_in.'" class="action"><a href="#"></a></div>
                            </a>
                        </li>';
                    }

                }
            }

            $list .= '</ul>';

            $list .= "
            <script type=\"text/javascript\">

                $('.action').click(function(){
                    $('.PluploadLoad').load('". $this->view->Url($route) ."/remove/".$model."' + $(this).attr('id') + '/". $id  ."');
                });

            </script>";

            return $list;

        }else{

            return false;
        }

    }
}
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
class QuAdminDocuments extends AbstractHelper
{

    protected $serviceLocator;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }


    /**
     * @param $id
     * @param $options
     * @return bool|string
     */
    public function __invoke($id,$options)
    {
        $plupload = $this->serviceLocator->get('plupload_service');
        $config   = $this->serviceLocator->get('config');
        $pluploadConfig = $config['QuConfig']['QuPlupload'];

        $list    = '';
        $listDb  = $plupload->setPluploadIdAndModelList($id,$options);
        $listDb  = $listDb->getPluploadIdAndModelList();
        if(count($listDb) > 0){
        foreach($listDb as $a){}

            $file      = $pluploadConfig['DirUploadAbsolute'] . '/' . $a->getName();
            $url       = $pluploadConfig['DirUpload'] . '/'  . $a->getName();
            $urlSmall  = $pluploadConfig['DirUpload'] . '/s' . $a->getName();
            $ex        = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if(is_file($file)){

                if($ex == 'jpg' or $ex == 'jpeg' or $ex == 'gif' or $ex == 'png'){

                    $list .= '<a href="'.$url.'" class="img"><img src="'.$urlSmall.'"></a>';

                }else{

                    $list .= '<a href="'.$url.'" class="doc"></a>';
                }
            }
            return $list;
        }else{
            return false;
        }
    }
}
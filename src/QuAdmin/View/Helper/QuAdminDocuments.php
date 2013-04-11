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
        $docs = $options->getDocuments();
        $list    = '';
        $listDb  = $plupload->setPluploadIdAndModelList($id,$options->getTableName());
        $listDb  = $listDb->getPluploadIdAndModelList();
        if(count($listDb) > 0){
        foreach($listDb as $a){}

            $file      = $docs['DirUploadAbsolute'] . '/' . $a->getName();
            $url       = $docs['DirUpload'] . '/'  . $a->getName();
            $urlSmall  = $docs['DirUpload'] . '/s' . $a->getName();
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
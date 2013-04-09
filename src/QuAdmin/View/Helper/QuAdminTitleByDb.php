<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuAdminTitleByDb extends AbstractHelper
{


    protected $serviceLocator;


    public function __construct($serviceLocator){

        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke($options,$key,$route){

        $mapperHelper = $this->serviceLocator->get('qu_admin_model_helper');
        $mapperHelper->setQuAdminModelOptions($options);
        $endBreadCrumb = $mapperHelper->endBreadCrumb();

        if($endBreadCrumb['level']){

            return '
            <div class="title-nav">
                <a href="'.$this->view->url($route, array('action'=>'index','id'=>@$endBreadCrumb[@$key['IdParent']])).'">
                    <span class="iconb" data-icon="&#xe032;"></span>'
                    .$endBreadCrumb['title'].
                '</a>
            </div>';

        }else{

            return '
            <div class="title-nav">'
                .$endBreadCrumb['title'].
            '</div>';
        }
    }
}
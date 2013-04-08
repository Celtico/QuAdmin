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

    public function __invoke($options){

        $mapperHelper = $this->serviceLocator->get('qu_admin_model_helper');
        $mapperHelper->setQuAdminModelOptions($options);
        $title = $mapperHelper->title();
        return '<div class="title-nav">'.$title['title'].'</div>';

    }
}
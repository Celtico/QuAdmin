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

    public function __invoke($id,$options)
    {
        $mapperHelper = $this->serviceLocator->get('qu_admin_model_helper');
        $mapperHelper->setQuAdminModelOptions($options);
        $this->getField($options->getTableKeyFields());
        $this->recursive($id,$mapperHelper);
    }

    public function recursive($id,$mapperHelper){

        $maps = $mapperHelper->findByParent($id);
        if($maps){
            echo '<ul class="map">';
            foreach($maps as $map){

                if($map[$this->KeyTitle]){
                    $title = $map[$this->KeyTitle];
                }else{
                    $title = '<span class="Unnamed">Unnamed</span>';
                }

                echo '<li>'.
                        $title.
                        $this->recursive($map[$this->KeyId],$mapperHelper)
                   .'</li>';

            }
            echo '</ul>';
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
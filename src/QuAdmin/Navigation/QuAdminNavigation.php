<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;


/**
 * Class QuAdminNavigation
 * @package QuAdmin\Navigation
 */
class QuAdminNavigation extends DefaultNavigationFactory
{
    /**
     * @var
     */
    protected $pages;



    /**
     * @return string
     */
    protected function getName()
    {
        return 'qu_admin_navigation';
    }


}

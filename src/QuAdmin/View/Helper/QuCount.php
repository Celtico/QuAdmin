<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuCount extends AbstractHelper
{
    protected $QuUtilities;

    /**
     * @param $QuUtilities
     */
    public function __construct($QuUtilities){

        $this->QuUtilities = $QuUtilities;
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function __invoke($id){

        return $this->QuUtilities->CountChildrenS($id);
    }

}
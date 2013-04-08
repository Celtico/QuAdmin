<?php
/**
 * Class by platform SpeckCommerce Version 0.0.1
 * @link https://github.com/speckcommerce/speck
 */

namespace QuAdmin\Db\Adapter;

use Zend\Db\Adapter\Adapter;

/**
 * Class DbAdapterAwareInterface
 * @package QuAdmin\Mapper
 */
interface DbAdapterAwareInterface
{
    /**
     * @return mixed
     */
    public function getDbAdapter();

    /**
     * @param Adapter $dbAdapter
     * @return mixed
     */
    public function setDbAdapter(Adapter $dbAdapter);
}
<?php

namespace QuAdmin\Model\Interfaces;

interface WebMapperInterface
{
    public function getRow($array = array(),$order = null);
    public function getAll($array = array(),$order = null);
}

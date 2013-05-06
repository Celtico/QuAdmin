<?php

namespace QuAdmin\Model\Interfaces;

interface WebMapperInterface
{
    public function getRow($array = array(),$order = null);
    public function getAll($array = array(),$order = null);
    public function getMapperPage($array = array(),$order = null,$numberPage,$page,$TableName = null);
}

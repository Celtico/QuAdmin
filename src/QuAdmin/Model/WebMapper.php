<?php

namespace QuAdmin\Model;

class WebMapper extends AbstractMapper implements  Interfaces\WebMapperInterface
{

    public function getRow($array = array(),$order = null)
    {
        $this->Order($order);
        $this->where($array);
        return $this->row();
    }

    public function getAll($array = array(),$order = null)
    {
        $this->Order($order);
        $this->where($array);
        return $this->all();
    }

    public function getMapperPage($array = array(),$order = null,$numberPage,$page,$TableName = null)
    {
        $this->Order($order);
        $this->where($array);
        return $this->mapperPage($numberPage,$page,$TableName = null);
    }
}
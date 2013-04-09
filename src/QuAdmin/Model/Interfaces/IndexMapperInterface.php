<?php

namespace QuAdmin\Model\Interfaces;

interface IndexMapperInterface
{

    public function newOrder($Order, $n, $options = null);

    public function findByParent($string, $id_parent, $lang = null, $page,$numberPage);
}

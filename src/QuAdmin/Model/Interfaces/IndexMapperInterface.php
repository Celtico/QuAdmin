<?php

namespace QuAdmin\Model\Interfaces;

interface IndexMapperInterface
{

    public function search($string, $options = null);

    public function newOrder($Order, $n, $options = null);

    public function findByParent($id_parent, $lang = null, $page,$numberPage);
}

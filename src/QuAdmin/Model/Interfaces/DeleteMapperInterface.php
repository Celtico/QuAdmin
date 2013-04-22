<?php

namespace QuAdmin\Model\Interfaces;

interface DeleteMapperInterface
{
    public function remove($id,$options = null);
}
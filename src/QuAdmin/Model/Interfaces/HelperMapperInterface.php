<?php

namespace QuAdmin\Model\Interfaces;

interface HelperMapperInterface
{
    public function breadCrumb($id,$lang = false);

    public function countChild($id);

    public function langActive($id,$lang);
}

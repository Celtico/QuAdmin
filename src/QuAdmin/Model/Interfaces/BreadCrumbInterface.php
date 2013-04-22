<?php

namespace QuAdmin\Model\Interfaces;

interface BreadCrumbInterface
{
    public function breadCrumb($id,$lang = false,$model = false);

    public function countChild($id,$tableName = false);

    public function getLevel($id = false);

    public function getPath($id);

    public function langActive($id,$lang);
}

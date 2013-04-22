<?php

namespace QuAdmin\Model;

class LanguagesMapper extends AbstractMapper implements  Interfaces\LanguagesMapperInterface
{
    public function languages()
    {
        $selector = array();
        $this->Order($this->getOptionsOrder());
        $this->toArray();
        foreach($this->all() as  $sel){
            if($sel[$this->KeyTitle]){
                $selector[$sel[$this->KeyName]] = $sel[$this->KeyTitle];
            }
        }
        return $selector;
    }

}
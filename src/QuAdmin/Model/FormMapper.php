<?php

namespace QuAdmin\Model;

class FormMapper extends AbstractMapper implements Interfaces\FormMapperInterface
{

    public function options($form,$quFormOptions)
    {

        $this->setQuAdminModelOptions($quFormOptions);

        $where                  = $form['where'];
        $order                  = $form['order'];
        $fieldKeyName           = $form['fieldKeyName'];
        $fieldKeyLabel          = $form['fieldKeyLabel'];

        if($order) {  $this->order($order); }
        if($where) {  $this->where($where); }


        $selector = array();

        if(isset($form['default']) and $form['default']){
            $selector  +=  $form['default'];
        }

        $this->toArray();
        foreach($this->all($this->getQuAdminModelOptions()->getTableName()) as  $sel){
            if(isset($sel[$fieldKeyName]) and isset($sel[$fieldKeyLabel])){
                $selector[$sel[$fieldKeyName]] =  $sel[$fieldKeyLabel];
            }
        }

        return $selector;
    }
}
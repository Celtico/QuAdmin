<?php

namespace QuAdmin\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;
use Zend\Db\Sql\Predicate;

class FormMapper extends AbstractMapper implements Interfaces\FormMapperInterface
{

    public function options($form,$quFormOptions)
    {

        $this->setQuAdminModelOptions($quFormOptions);

        $where                  = $form['where'];
        $order                  = $form['order'];
        $fieldKeyName           = $form['fieldKeyName'];
        $fieldKeyLabel          = $form['fieldKeyLabel'];
        $fieldKeyLabelParent    = $form['fieldKeyLabelParent'];

        if($order) {  $this->order($order); }
        if($where) {  $this->where($where); }

        $selector = array();
        $this->toArray();
        foreach($this->all($this->getQuAdminModelOptions()->getTableName()) as  $sel){
            if(isset($sel[$fieldKeyName]) and isset($sel[$fieldKeyLabel])){

                // Get Info Parent (title)
                $parent = false;
                /*
                if($this->KeyIdParent and $fieldKeyLabelParent)
                {   if(isset($sel[$this->KeyIdParent]))
                    {
                        $this->where( array($this->KeyId => $sel[$this->KeyIdParent] ) );
                        $parents = $this->row();
                        if(isset($parents[$this->KeyTitle])){
                            $parent = $parents[$this->KeyTitle].' :: ';
                        }
                    }
                }
                */
                $selector[$sel[$fieldKeyName]] =  $parent.$sel[$fieldKeyLabel];
            }
        }

        if(count($selector) == 0){
            $selector = array(0 => '-' );
        }

        return $selector;

    }


}
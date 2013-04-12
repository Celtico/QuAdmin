<?php

namespace QuAdmin\Model;

use Zend\Db\Sql\Predicate;

class IndexMapper extends AbstractMapper implements Interfaces\IndexMapperInterface
{


    public function findByParent($string,$id_parent, $lang = null, $page,$numberPage)
    {
        $where = array();

        if($string){

            $string = str_replace('%20',' ',$string);

            $fields = $this->getTableFieldsCleanData();
            $likeField = array();
            /** @var $fields array config options */
            foreach($fields as $field){
                $likeField[] =  new Predicate\Like($field, '%'.$string.'%');
            }
            $where = array(new Predicate\PredicateSet($likeField,Predicate\PredicateSet::OP_OR));

        }else{

            if($this->KeyIdParent){
                $where = array($this->KeyIdParent => $id_parent);
            }
            if($this->KeyLang and $lang) {
                $where += array( $this->KeyLang => $lang);
            }
        }

        $this->where($where);
        $this->toArray();
        $this->Order($this->getOptionsOrder());

        return $this->page($numberPage,$page);
    }



    public function newOrder($Order, $n, $options = null)
    {
        $order = explode(' ',$this->getOptionsOrder());
        $order = trim($order[1]);

        $result  = false;

        if($order == 'desc'){
            $count   = $n+1;
        }else{
            $count   = $n-1;
        }

        foreach($Order as $id){

            if($order == 'desc'){
                $count--;
            }else{
                $count++;
            }

            $data[$this->KeyOrder] = $count;
            $result = $this->onUpdate($data,array($this->KeyId => $id));
        }
        return $result;
    }
}
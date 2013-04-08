<?php

namespace QuAdmin\Model;

use Zend\Db\Sql\Predicate;

class IndexMapper extends AbstractMapper implements Interfaces\IndexMapperInterface
{

    public function search($string, $options = null)
    {
        $fields = $this->getTableFieldsCleanData();
        $likeField = array();
        /** @var $fields array config options */
        foreach($fields as $field){
            $likeField[] =  new Predicate\Like($field, '%'.$string.'%');
        }
        $like = new Predicate\PredicateSet($likeField,Predicate\PredicateSet::OP_OR);
        $this->where(array($like));
        $this->toArray();
        $this->Order($this->getOptionsOrder());
        return $this->page();
    }

    public function newOrder($Order, $n, $options = null)
    {
        $result  = false;
        $count   = $n+1;
        foreach($Order as $id){
            $count--;
            $data[$this->KeyOrder] = $count;
            $result = $this->onUpdate($data,array($this->KeyId => $id));
        }
        return $result;
    }

    public function findByParent($id_parent, $lang = null, $page,$numberPage)
    {

        $where = array();
        if($this->KeyIdParent){
            $where = array($this->KeyIdParent => $id_parent);
        }
        if($this->KeyLang and $lang) {
            $where += array( $this->KeyLang => $lang);
        }


        $this->where($where);
        $this->toArray();
        $this->Order($this->getOptionsOrder());




        $paginator = $this->page();

        $paginator->setItemCountPerPage($numberPage);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
}
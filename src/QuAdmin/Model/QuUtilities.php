<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;

use QuAdmin\Form\QuFilter;

class QuUtilities extends AbstractTableGateway
{
    public $table = 'QuAdmin';
    public $adapter;

    /**
     * @param \Zend\Db\Adapter\Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new QuFilter());
        $this->initialize();
    }

    /**
     * @param $type
     * @param $column
     *
     * @return array
     */
    public function SelectOptions($type,$column)
    {
        $sql = $this->getSql();
        $selector = array();
        $select = $sql->select();

        $where  = new Where();
        $where->equalTo('type', $type);
        $select->where($where)->order('order desc');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $select = array_values(iterator_to_array($result));

        foreach($select as  $sel){
            if(isset($sel['title'])){

                // Get Info Parent (title)
                $parent = '';
                $parents = $this->SelectById($sel['id_parent']);
                if(isset($parents[0])){
                    $parent = $parents[0].' :: ';
                }

                $selector[$sel[$column]] = $parent.$sel['title'];
            }
        }
        if(count($selector) == 0){
            $selector = array(''=>'-');
        }
        return $selector;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function SelectById($id)
    {
        $sql = $this->getSql();
        $selector = array();
        $select = $sql->select();

        $where  = new Where();
        $where->equalTo('id', $id);
        $select->where($where)->order('order desc');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $select = array_values(iterator_to_array($result));

        foreach($select as  $sel){
            if(isset($sel['title'])){
                $selector[] =  $sel['title'];
            }
        }
        return $selector;
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function CountChildrenS($id)
    {
        $sql      = $this->getSql();
        $select   = $sql->select();

        $where  = new Where();
        $where->equalTo('id_parent', $id);
        $select->where($where)->order('order desc');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $select = array_keys(iterator_to_array($result));

        return count($select);
    }

    /**
     * @param $string
     *
     * @return mixed|string
     */
    public function urlc($string){

        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);

        $NOT_acceptable_characters_regex = '#[^-a-zA-Z0-9_ ]#';
        $string = preg_replace($NOT_acceptable_characters_regex, '', $string);
        $string = trim($string);
        $string = preg_replace('#[-_ ]+#', '-', $string);
        $string =  strtolower($string);

        return $string;
    }


}

<?php

namespace QuAdmin\Model;



use QuAdmin\Db\Adapter\DbAdapterAwareInterface;
use QuAdmin\Object\Object;
use QuAdmin\Object\Mapper;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;


/**
 * @property mixed hydrator
 */
class AbstractMapper  implements DbAdapterAwareInterface
{



    /* OPTIONS */

    protected $tableName;
    protected $tableLabel;
    protected $entity;
    protected $tableKeyFields;
    protected $linkerModels;
    protected $linkerParent;
    protected $optionsPaginator;
    protected $defaultLanguage;
    protected $documents;
    protected $optionsForm;
    protected $optionsOrder;
    protected $tableFieldsCleanData;
    protected $quAdminModelOptions;
    protected $sql;

    public $KeyIdParent;
    public $KeyId;
    public $KeyLang;
    public $KeyName;
    public $KeyTitle;
    public $KeyIdLang;
    public $KeyIdAuthor;
    public $KeyDate;
    public $KeyModified;
    public $KeyOrder;
    public $KeyLevel;
    public $KeyPath;

    protected $dbAdapter;
    protected $Order;
    protected $where;
    protected $array;
    protected $select;
    protected $objectEntity;


    public function entity()
    {
        $entity =  $this->getEntity();
        $entity =   new $entity;
        return   $entity;
    }

    public function sql()
    {
        return  new Sql($this->getDbAdapter());
    }

    public function getSelect()
    {
        if(!$this->select){
            $this->setSelect($this->getTableName());
        }
       return $this->select;
    }

    public function setSelect($TableName)
    {
        $this->select = $this->sql()->select($TableName);
        return $this;
    }

    public function resultSet()
    {
        if($this->array and !$this->objectEntity){
            //return new ResultSet(ResultSet::TYPE_ARRAYOBJECT);
            return new ResultSet(ResultSet::TYPE_ARRAY);
        }else{
            return new HydratingResultSet(new ReflectionHydrator, $this->entity());
        }
    }

    public function toArray()
    {
        $this->array = true;
        return $this;
    }
    public function toObjectEntity()
    {
        $this->objectEntity = true;
        return $this;
    }

    public function Order($Order)
    {
        $this->Order = $Order;
        return $this;
    }

    public function where(array $where)
    {
        $this->where = $where;
        return $this;
    }

    public function selectByWhereByOrder($TableName)
    {
        $select = $this->sql()->select($TableName ?: $this->getTableName());
        $sel =  $select;
        if($this->Order) $sel = $select->order($this->Order);
        if($this->where) $sel = $select->where($this->where);
        if($this->where and $this->Order) $sel = $select->where($this->where)->order($this->Order);
        return $sel;
    }
    public function all($TableName = null)
    {
        $select = $this->selectByWhereByOrder($TableName);
        $stmt   = $this->sql()->prepareStatementForSqlObject($select);
        $result = $this->resultSet()->initialize($stmt->execute());
        return  $result;
    }
    public function row($TableName = null)
    {
        $select = $this->selectByWhereByOrder($TableName);
        $stmt = $this->sql()->prepareStatementForSqlObject($select);
        $result = $this->resultSet()->initialize($stmt->execute())->current();
        return $result;
    }

    public function mapperAll($TableName = null)
    {
        $select     = $this->selectByWhereByOrder($TableName);
        $stmt       = $this->sql()->prepareStatementForSqlObject($select);
        $resultSet  = new ResultSet(ResultSet::TYPE_ARRAY);
        $result     = $resultSet->initialize($stmt->execute());
        $return     = array();
        foreach ($result as $row) {
            $Mapper = Mapper::accumulateByMap($row,$row,array_flip($this->getTableKeyFields()));
            $Mapper = (array)$Mapper;
                $return[] = $this->entity()->addData($Mapper);
        }
        return $return;

    }

    public function mapperRow($TableName = null)
    {
        $select    = $this->selectByWhereByOrder($TableName);
        $stmt      = $this->sql()->prepareStatementForSqlObject($select);
        $resultSet = new ResultSet(ResultSet::TYPE_ARRAY);
        $row       = $resultSet->initialize($stmt->execute())->current();
        $Mapper    = Mapper::accumulateByMap($row,$row,array_flip($this->getTableKeyFields()));
        $Mapper    = (array)$Mapper;
        return $this->entity()->addData($Mapper);
    }


    public function mapperPage($numberPage,$page,$TableName = null)
    {
        $select     = $this->selectByWhereByOrder($TableName);
        $stmt       = $this->sql()->prepareStatementForSqlObject($select);
        $resultSet  = new ResultSet(ResultSet::TYPE_ARRAY);
        $result     = $resultSet->initialize($stmt->execute());
        $return     = array();
        foreach ($result as $row) {
            $Mapper = Mapper::accumulateByMap($row,$row,array_flip($this->getTableKeyFields()));
            $Mapper = (array)$Mapper;
            $return[] = $this->entity()->addData($Mapper);
        }
        $return = (array) $return;
        $paginator = new Paginator(new ArrayAdapter($return));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($numberPage);
        //$paginator->setPageRange(5);
        return $paginator;

    }

    public function page($numberPage,$page,$TableName = null)
    {
        $select = $this->selectByWhereByOrder($TableName);
        $paginator = new Paginator(new DbSelect($select, $this->getDbAdapter(), $this->resultSet()));
        $paginator->setItemCountPerPage($numberPage);
        $paginator->setCurrentPageNumber($page);
        return $paginator;

    }

    public function onInsert($data,$tableName = null)
    {

        if(!$tableName){
            $tableName = $this->getTableName();
        }
        $data = $this->cleanData($data);
        $sql       = new Sql($this->getDbAdapter());
        $insert    = $sql->insert($tableName);
        $insert->values($data);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result    = $statement->execute();
        return $result->getGeneratedValue();
    }

    public function onUpdate($data,$where = null,$tableName = null)
    {

        if(!$tableName){
            $tableName = $this->getTableName();
        }
        $data = $this->cleanData($data);
        $sql    =  new Sql($this->getDbAdapter());
        $update = $sql->update($tableName);
        $update->set($data)->where($where);
        $statement = $sql->prepareStatementForSqlObject($update);
        return $statement->execute();
    }

    public function onRemove($where = null,$tableName = null)
    {

        if(!$tableName){
            $tableName = $this->getTableName();
        }

        $sql       = new Sql($this->getDbAdapter());
        $delete = $sql->delete($tableName);
        $delete->where($where);
        $statement = $sql->prepareStatementForSqlObject($delete);
        return $statement->execute();
    }

    public function cleanData($data)
    {
        if (!is_array($this->getTableFieldsCleanData()) || !count($this->getTableFieldsCleanData())) {
            return $data;
        }
        foreach ($data as $key => $val) {
           if (!in_array($key, $this->getTableFieldsCleanData())) {
               unset($data[$key]);
           }

        }
        return $data;
    }
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }
    public function setDbAdapter(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }






    /* QuAdminModelOptions */







    public function getTableName()
    {
        if (!$this->tableName) {
            $this->setTableName($this->getQuAdminModelOptions()->getTableName());
        }
        return $this->tableName;
    }
    public function setTableName($tableName)
    {
        $this->tableName =  $tableName;
        return $this;
    }

    public function setOptionsOrder($optionsOrder)
    {
        $this->optionsOrder = $optionsOrder;
    }

    public function getOptionsOrder()
    {
        if (!$this->optionsOrder) {
            $this->setOptionsOrder($this->getQuAdminModelOptions()->getOptionsOrder());
        }
        return $this->optionsOrder;
    }

    public function setDefaultLanguage($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    public function getDefaultLanguage()
    {
        if (!$this->defaultLanguage) {
            $this->setDefaultLanguage($this->getQuAdminModelOptions()->getDefaultLanguage());
        }
        return $this->defaultLanguage;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function getDocuments()
    {
        if (!$this->documents) {
            $this->setDocuments($this->getQuAdminModelOptions()->getDocuments());
        }
        return $this->documents;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        if (!$this->entity) {
            $this->setEntity($this->getQuAdminModelOptions()->getEntity());
        }
        return $this->entity;
    }

    public function setLinkerModels($linkerModels)
    {
        $this->linkerModels = $linkerModels;
    }

    public function getLinkerModels()
    {
        if (!$this->linkerModels) {
            $this->setLinkerModels($this->getQuAdminModelOptions()->getLinkerModels());
        }
        return $this->linkerModels;
    }

    public function setOptionsForm($optionsForm)
    {
        $this->optionsForm = $optionsForm;
    }

    public function getOptionsForm()
    {
        if (!$this->optionsForm) {
            $this->setOptionsForm($this->getQuAdminModelOptions()->getOptionsForm());
        }
        return $this->optionsForm;
    }

    public function setOptionsPaginator($optionsPaginator)
    {
        $this->optionsPaginator = $optionsPaginator;
    }

    public function getOptionsPaginator()
    {
        if (!$this->optionsPaginator) {
            $this->setOptionsPaginator($this->getQuAdminModelOptions()->getOptionsPaginator());
        }
        return $this->optionsPaginator;
    }

    public function setTableKeyFields($tableKeyFields)
    {
        $this->tableKeyFields = $tableKeyFields;
    }

    public function getTableKeyFields()
    {
        if (!$this->tableKeyFields) {
          $this->setTableKeyFields($this->getQuAdminModelOptions()->getTableKeyFields());
        }
        return $this->tableKeyFields;
    }

    public function setTableLabel($tableLabel)
    {
        $this->tableLabel = $tableLabel;
    }

    public function getTableLabel()
    {
        if (!$this->tableLabel) {
            $this->setTableLabel($this->getQuAdminModelOptions()->getTableLabel());
        }
        return $this->tableLabel;
    }

    public function setTableFieldsCleanData($tableFieldsCleanData)
    {
        $this->tableFieldsCleanData = $tableFieldsCleanData;
    }

    public function getTableFieldsCleanData()
    {
        if (!$this->tableFieldsCleanData) {
            $this->setTableFieldsCleanData($this->getQuAdminModelOptions()->getTableFieldsCleanData());
        }
        return $this->tableFieldsCleanData;
    }

    public function setLinkerParent($linkerParent)
    {
        $this->linkerParent = $linkerParent;
    }

    public function getLinkerParent()
    {
        if (!$this->linkerParent) {
            $this->setLinkerParent($this->getQuAdminModelOptions()->getLinkerParent());
        }
        return $this->linkerParent;
    }


    /* INSERT QuAdminModelOptions  */



    public function getQuAdminModelOptions()
    {
        return $this->quAdminModelOptions;
    }
    public function setQuAdminModelOptions($quAdminModelOptions)
    {
        $this->quAdminModelOptions = $quAdminModelOptions;
        $this->getField();
        return $this;
    }

    public function getField()
    {
        $fil = new \Zend\Filter\Word\UnderscoreToCamelCase();
        $TableFields    = $this->getTableKeyFields();
        foreach($TableFields as $k => $e){
            $k = $fil->filter($k);
            $this->$k = $e;
        }
        return $this;
    }


}

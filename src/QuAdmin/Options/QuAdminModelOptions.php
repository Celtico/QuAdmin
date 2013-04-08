<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class QuAdminModelOptions
 * @package QuAdmin\Options
 */
class QuAdminModelOptions extends AbstractOptions
{

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

    public function setTableFieldsCleanData($tableFieldsCleanData)
    {
        $this->tableFieldsCleanData = $tableFieldsCleanData;
    }

    public function getTableFieldsCleanData()
    {
        return $this->tableFieldsCleanData;
    }

    public function setOptionsOrder($optionsOrder)
    {
        $this->optionsOrder = $optionsOrder;
    }

    public function getOptionsOrder()
    {
        return $this->optionsOrder;
    }

    public function setDefaultLanguage($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setLinkerModels($linkerModels)
    {
        $this->linkerModels = $linkerModels;
    }

    public function getLinkerModels()
    {
        return $this->linkerModels;
    }

    public function setOptionsForm($optionsForm)
    {
        $this->optionsForm = $optionsForm;
    }

    public function getOptionsForm()
    {
        return $this->optionsForm;
    }

    public function setOptionsPaginator($optionsPaginator)
    {
        $this->optionsPaginator = $optionsPaginator;
    }

    public function getOptionsPaginator()
    {
        return $this->optionsPaginator;
    }

    public function setTableKeyFields($tableKeyFields)
    {
        $this->tableKeyFields = $tableKeyFields;
    }

    public function getTableKeyFields()
    {
        return $this->tableKeyFields;
    }

    public function setTableLabel($tableLabel)
    {
        $this->tableLabel = $tableLabel;
    }

    public function getTableLabel()
    {
        return $this->tableLabel;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setLinkerParent($linkerParent)
    {
        $this->linkerParent = $linkerParent;
    }

    public function getLinkerParent()
    {
        return $this->linkerParent;
    }


}

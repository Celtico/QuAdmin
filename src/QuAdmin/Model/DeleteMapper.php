<?php

namespace QuAdmin\Model;

use Zend\EventManager\EventManager;

class DeleteMapper extends AbstractMapper implements Interfaces\DeleteMapperInterface
{

    public $events;
    protected $plupload;

    /**
     * Get checkRow
     *
     * @param $id
     * @return array|\ArrayObject|null
     */
    public function findByIdLang($id)
    {
        $this->toArray();
        if($this->KeyIdLang){
            $this->where(array($this->KeyIdLang => $id));
            return $this->row();
        }else {
            $this->where(array($this->KeyId => $id));
            return $this->row();
        }
    }


    /**
     * @param $id
     * @param null $options
     */
    public function remove($id,$options = null)
    {
        $findByParent = $this->findByParentRemove($id);


        //
        if($findByParent) {
            foreach($findByParent as $Parent){
                if($Parent[$this->KeyId] != $id){
                    $this->remove($Parent[$this->KeyId],$options);
                }
            }
        }

        /*
         * SubModel
         */
        $LinkerModel = $options->getLinkerModels();
        if(isset($LinkerModel[0])){
            foreach($LinkerModel as $model){
                $this->findByParentRemoveSubModels(
                    $id,
                    $model['table'],
                    $model['key_id_parent'],
                    $model['key_id']
                );
            }
        }

        $this->removeById($id);
    }

    /**
     * @param $id
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function removeById($id)
    {
        $remove = $this->onRemove(array($this->KeyId => $id));
        /*
        $remove = $this->onUpdate(array('remove'=>1),array($this->KeyId => $id));
        */
       $this->postEventRemove($id);
       return $remove;
   }

   /**
    * @param $id
    * @return bool|\Zend\Db\ResultSet\ResultSet
    */
    public function findByParentRemove($id)
    {
        if($this->KeyIdParent){
            $this->where(array($this->KeyIdParent=> $id));
            $this->toArray();
            return $this->all();
        }
        return false;
    }

    /**
     * @param $id
     * @param $tableName
     * @param $KeyId
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function removeByIdSubModels($id,$tableName,$KeyId)
    {
        $remove = $this->onRemove(array($KeyId => $id),$tableName);
        /*
        $remove = $this->onUpdate(array('remove'=>1),array($KeyId => $id),$tableName);
        */
        $this->postEventRemove($id);
        return $remove;
    }


    /**
     * @param $id
     * @param $tableName
     * @param $KeyIdParent
     * @param $KeyId
     */
    public function findByParentRemoveSubModels($id,$tableName,$KeyIdParent,$KeyId)
    {
        if($KeyIdParent){

            $this->where(array($KeyIdParent=> $id));
            $this->toArray();

            foreach($this->all($tableName) as $rowModel){
                $this->removeByIdSubModels($rowModel[$KeyId],$tableName,$KeyId);
            }
        }
    }


    /**
     * @param $id
     */
    public function postEventRemove($id)
    {

        //print_r($this->events()->getIdentifiers());
        /*
        $this->events()->trigger(__FUNCTION__, $this, array(
            'id' => $id,
            'options' => $this->getQuAdminModelOptions(),
            'plupload'  =>  $this->plupload
        ));
        */
    }

    /**
     * @return EventManager
     */
    public function events()
    {
        if (!$this->events) {
            $this->events = new EventManager(__CLASS__);
        }

        return $this->events;
    }

    public function setPlupload($plupload)
    {
        $this->plupload = $plupload;
        return $this;
    }
    public function getPlupload()
    {
        return $this->events;
    }
}

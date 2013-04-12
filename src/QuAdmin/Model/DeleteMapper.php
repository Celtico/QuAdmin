<?php

namespace QuAdmin\Model;

use Zend\EventManager\EventManager;

class DeleteMapper extends AbstractMapper implements Interfaces\DeleteMapperInterface
{

    public $events;

    /**
     * Get checkRow
     *
     * @param $id
     * @return array|\ArrayObject|null
     */
    public function findByIdLang($id)
    {
        $this->toArray();
        if($this->KeyIdLangLinker){
            $this->where(array($this->KeyIdLangLinker => $id));
            return $this->row();
        }else {
            $this->where(array($this->KeyId => $id));
            return $this->row();
        }
    }

    /**
     * Remove
     *
     * @param $id
     */
    public function remove($id)
    {
        $findByParent = $this->findByParentRemove($id);
        if($findByParent) {
            foreach($findByParent as $Parent){
                $this->remove($Parent[$this->KeyId]);
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
     */
    public function postEventRemove($id)
    {
        //print_r($this->events()->getIdentifiers());
        $this->events()->trigger(__FUNCTION__, $this, array(
            'id' => $id,
            'options' => $this->getQuAdminModelOptions(),
        ));
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
}

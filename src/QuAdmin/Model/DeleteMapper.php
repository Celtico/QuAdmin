<?php

namespace QuAdmin\Model;

class DeleteMapper extends AbstractMapper implements Interfaces\DeleteMapperInterface
{

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

    public function removeById($id)
    {
        return $this->onRemove(array($this->KeyId => $id));
    }

    public function findByParentRemove($id)
    {
        if($this->KeyIdParent){
            $this->where(array($this->KeyIdParent=> $id));
            $this->toArray();
            return $this->all();
        }
        return false;
    }

}

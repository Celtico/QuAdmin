<?php

namespace QuAdmin\Model;

class DuplicateMapper extends AbstractMapper implements Interfaces\DuplicateMapperInterface
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
        if($this->KeyIdLang){
            $this->where(array($this->KeyIdLang => $id));
            return $this->row();
        }else {
            $this->where(array($this->KeyId => $id));
            return $this->row();
        }
    }

    /**
     * Duplicate
     *
     * @param $id
     */
    public function duplicate($id)
    {
        if($id != '' and $id != 0)
        {
            $last = $this->duplicateById($id);
            if($this->KeyIdParent){ $this->duplicateParent($id,$last); }
        }
    }


    public function duplicateParent($id,$last)
    {
        //check
        $models = $this->findByParentDuplicate($id);
        foreach($models as $model){
            //If exist parent duplicate
            $lastNext = $this->duplicateByIdParent($model[$this->KeyId],$last);
            //New check parent
            $this->duplicateParent($model[$this->KeyId],$lastNext);
        }
    }

    public function duplicateById($id)
    {
        $this->where(array($this->KeyId => $id));
        $this->toArray();
        $data = $this->row();
        //Insert Duplicate
        unset($data[$this->KeyId]);
        $data[$this->KeyOrder]   =  $data[$this->KeyOrder] + 1;
        $data[$this->KeyTitle]   =  $data[$this->KeyTitle] . ' (d)';
        $last = $this->onInsert($data);
        //Update Id Lang Linker
        if($this->getDefaultLanguage()){
            $data[$this->KeyIdLang] =  $last;
            $this->onUpdate($data,array($this->KeyId => $last));
        }
        return $last;
    }

    public function duplicateByIdParent($id,$last)
    {
        $this->where(array($this->KeyId => $id));
        $this->toArray();
        $data = $this->row();
        //Insert Duplicate Parent
        unset($data[$this->KeyId]);
        if($this->KeyIdParent){
            $data[$this->KeyIdParent] = $last;
        }
        $data[$this->KeyTitle] = $data[$this->KeyTitle] . ' (d)';
        $last = $this->onInsert($data);
        //Update Id Lang Linker
        if($this->KeyIdLang){
            $data[$this->KeyIdLang] =  $last;
            $this->onUpdate($data,array($this->KeyId => $last));
        }
        return $last;
    }

    public function findByParentDuplicate($id_parent)
    {
        if($this->KeyIdParent){
            $this->where(array($this->KeyIdParent=> $id_parent));
            $this->toArray();
            return $this->all();
        }
        return false;
    }
}

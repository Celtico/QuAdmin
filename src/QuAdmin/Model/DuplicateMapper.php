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
        $this->Order($this->KeyId.' asc');
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
     * @param $options
     */
    public function duplicate($id,$options)
    {
        if($id != '' and $id != 0)
        {
            $last = $this->duplicateById($id,$options);

            //check SubModel
            $this->subModelDuplicate($last,$id,$options,'duplicate');

            if($this->KeyIdParent){ $this->duplicateParent($id,$last,$options); }
        }
    }


    public function duplicateParent($id,$last,$options)
    {
        //check
        $models = $this->findByParentDuplicate($id);
        foreach($models as $model){

            //check SubModel
            //$this->subModelDuplicate($last,$id,$options,'duplicateParent');

            //If exist parent duplicate
            $lastNext = $this->duplicateByIdParent($model[$this->KeyId],$last,$options);

            //New check parent
            $this->duplicateParent($model[$this->KeyId],$lastNext,$options);
        }
    }

    public function duplicateById($id,$options)
    {
        $this->where(array($this->KeyId => $id));
        $this->toArray();
        $data = $this->row();
        //Insert Duplicate
        unset($data[$this->KeyId]);
        $data[$this->KeyOrder]   =  $data[$this->KeyOrder] + 1;
        $data[$this->KeyTitle]   =  $data[$this->KeyTitle] . ' (d)';
        $last = $this->onInsert($data);
        //check SubModel
        //$this->subModelDuplicate($last,$id,$options,'duplicateById');
        //Update Id Lang Linker
        if($this->getDefaultLanguage()){
            $data[$this->KeyIdLang] =  $last;
            $this->onUpdate($data,array($this->KeyId => $last));
        }
        return $last;
    }

    public function duplicateByIdParent($id,$last,$options)
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
        $lastUpdate = $this->onInsert($data);
        //check SubModel
        if($lastUpdate){
            $this->subModelDuplicate($lastUpdate,$id,$options,'duplicateByIdParent');
        }
        //Update Id Lang Linker
        if($this->getDefaultLanguage()){
            $data[$this->KeyIdLang] =  $lastUpdate;
            $this->onUpdate($data,array($this->KeyId => $lastUpdate));
        }
        return $lastUpdate;
    }

    public function subModelDuplicate($last,$id,$options,$event = null)
    {

        foreach($options->getLinkerModels() as $model)
        {
            if(
                isset($model['table']) and
                isset($model['key_id_parent']) and
                isset($model['key_id']) and
                isset($model['key_id_lang']) and
                isset($model['key_title'])
            ){

                $tableName   = $model['table'];
                $KeyIdParent = $model['key_id_parent'];
                $KeyId       = $model['key_id'];
                $KeyIdLang   = $model['key_id_lang'];
                $KeyTitle    = $model['key_title'];

                $this->where(array($model['key_id_parent']=>$id));
                $this->toArray();
                $select = $this->all($tableName);
                foreach($select as $data)
                {
                    //Insert Duplicate Parent
                    unset($data[$KeyId]);

                    if(isset($data[$KeyIdParent])){
                        $data[$KeyIdParent] = $last;
                        $data[$KeyTitle] = $data[$KeyTitle] . ' (d)';   // . $event . ' (d)' . count($select);
                        $lastUpdate = $this->onInsert($data,$tableName);
                        //Update Id Lang Linker
                        if(isset($data[$KeyIdLang])){
                            $data[$KeyIdLang] =  $lastUpdate;
                            $this->onUpdate($data,array($KeyId=>$lastUpdate),$tableName);
                        }
                    }
                }
                unset($data);
            }
        }
    }


    /*SubModels*/

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

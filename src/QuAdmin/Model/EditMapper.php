<?php

namespace QuAdmin\Model;

class EditMapper extends AbstractMapper implements Interfaces\EditMapperInterface
{

    public function update($data,$lang)
    {
        $id = $data[$this->KeyId];
        unset($data[$this->KeyId]);

        if($this->KeyIdLangLinker){

            $this->where(array($this->KeyIdLangLinker => $id,$this->KeyLang => $lang));
            $this->toArray();
            $find = $this->row();

            if ($find) {
                $this->onUpdate($data,array($this->KeyId => $find[$this->KeyId]));
            } else {
                $data = $this->saveAddLang($data,$id);
            }

        }else{

            $this->onUpdate($data,array($this->KeyId => $id));
        }
        return $data;
    }

    public function saveAddLang($data,$id)
    {
        unset($data[$this->KeyId]);
        $data[$this->KeyIdLangLinker] = $id;
        $this->onInsert($data);
        return $data;
    }

    public function findByLangIdByLang($lang,$id)
    {
        if($this->KeyIdLangLinker){

            $this->where(array($this->KeyLang=>$lang,$this->KeyIdLangLinker => $id));
            $this->toArray();
            $data = $this->row();
            if($data[$this->KeyId] == null){
                $this->where(array($this->KeyId => $id));
                $this->toArray();
                $data = $this->row();
            }

        }else{

            $this->where(array($this->KeyId => $id));
            $this->toArray();
            $data = $this->row();
        }
        return $data;
    }



}

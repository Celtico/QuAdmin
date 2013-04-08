<?php

namespace QuAdmin\Model;

class HelperMapper extends AbstractMapper implements Interfaces\HelperMapperInterface
{
    protected $breadCrumb;

    public function breadCrumb($id,$lang = false)
    {
        $level = '0';
        $breadCrumb  = array();
        if($id != '0' and $id != ''){
            while((int)$id != 0){

                if($this->KeyIdLangLinker){
                    $where = array($this->KeyIdLangLinker => $id);
                }else{
                    $where = array($this->KeyId => $id);
                }

                $this->where($where);
                $this->toArray();
                $row = $this->row();

                $values = array(
                    'id' => $row[$this->KeyId],
                    'title' => $row[$this->KeyTitle],
                    'level'=> $level
                );

                if($this->KeyIdParent){
                    $values += array($this->KeyIdParent => $row[$this->KeyIdParent]);
                }

                array_unshift($breadCrumb, $values);

                if($this->KeyIdParent){
                    $id = (int)$row[$this->KeyIdParent];
                    $level++;
                }else{
                    $this->breadCrumb = array($values,$values);
                    return $this->breadCrumb;
                }
            }
        }

        if($id > 0){

            $this->where(array($this->KeyIdParent => $id));
            $this->toArray();
            $row = $this->row();
            $values = array(
                'id' => $row[$this->KeyId],
                'title' => $row[$this->KeyTitle],
                'level'=> $level
            );

        }else{

            $values = array(
                'id' => 0,
                'title' => 'Index',
                'level'=> 0
            );
        }

        array_unshift($breadCrumb, $values);
        $this->breadCrumb = $breadCrumb;

        return $breadCrumb;
    }

    public function title()
    {
        return end($this->breadCrumb);
    }

    public function countChild($id)
    {
        if($this->KeyIdParent){
            $this->toArray();
            $this->where(array($this->KeyIdParent => $id));
            return  count($this->all());
        }
        return false;
    }

    public function langActive($id,$lang)
    {
        if($this->KeyIdLangLinker){
            $this->where(array($this->KeyIdLangLinker => $id,$this->KeyLang => $lang));
            $this->toArray();
            return  $this->row();
        }
        return false;
    }

    public function findByParent($id_parent)
    {
        if($this->KeyIdParent){
            $this->where(array($this->KeyIdParent=> $id_parent));
            $this->toArray();
            return $this->all();
        }
        return false;
    }
}

<?php

namespace QuAdmin\Model;

class AddMapper extends AbstractMapper implements Interfaces\AddMapperInterface
{

    protected $path;
    protected $level;
    protected $breadCrumb;

    public function insert($data)
    {
        if(isset($data[$this->KeyId])){
            unset($data[$this->KeyId]);
        }

        if($this->KeyOrder){
            $data[$this->KeyOrder] = $this->getNextOrder($data);
        }

        if($this->KeyIdLangLinker) {
            //add MultiLanguage
            $last = $this->onInsert($data);
            //Update Id Lang Linker
            $this->onUpdate(array($this->KeyIdLangLinker=>$last),array($this->KeyId => $last));
        }else{
            $last = $this->onInsert($data);
        }

        return $last;
    }

    public function getNextOrder($data)
    {
        if($this->KeyIdParent){
            $this->where(array($this->KeyIdParent => $data[$this->KeyIdParent]));
            $this->toArray();
            $this->Order($this->getOptionsOrder());
            $select = $this->row();
        }else{
            $this->toArray();
            $this->Order($this->getOptionsOrder());
            $select = $this->row();
        }
        if($select){
            return  $select[$this->KeyOrder]+1;
        }else{
            return  1;
        }
    }

    public function breadCrumb($id)
    {
        $this->level  = 0;
        $breadCrumb = array();
        if($id != ''){
            while((int)$id != 0){

                $this->where(array($this->KeyId => $id));
                $this->toArray();
                $row = $this->row();
                $id = (int)$row[$this->KeyIdParent];
                $values = array('name' => $row[$this->KeyName]);
                $this->level++;
                array_unshift($breadCrumb, $values);
            }
        }
        $this->breadCrumb = $breadCrumb;
        return $this;
    }
    public function getLevel()
    {
        return $this->level;
    }
    public function getPath($limit)
    {
        $this->path = false;
        $count = 0;
        foreach($this->breadCrumb as $path){
            $count++;
            if($count > $limit){
                $this->path .= $path['name'].'/';
            }
        }
        return $this->path;
    }
}
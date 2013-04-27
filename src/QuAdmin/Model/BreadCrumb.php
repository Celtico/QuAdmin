<?php

namespace QuAdmin\Model;

class BreadCrumb extends AbstractMapper implements Interfaces\BreadCrumbInterface
{

    protected $path;
    protected $level;
    protected $lang;
    protected $breadCrumb;

    public function breadCrumb($id,$lang = false,$model = false,$tableName = false)
    {
        if($tableName){
            $this->setTableName($tableName);

        }

        $this->level  = 0;
        $breadCrumb  = array();
        if($id != '0' and $id != ''){
            while((int)$id != 0){

                $this->level++;

                if($this->KeyIdParent){

                    if($this->KeyIdLang){
                        $where = array($this->KeyIdLang => $id);
                    }else{
                        $where = array($this->KeyId => $id);
                    }

                    $this->where($where);
                    $this->toArray();
                    $row = $this->row($tableName);


                    $values = array(
                        'id' => $row[$this->KeyId],
                        'title' => $row[$this->KeyTitle],
                        'level'=> $this->level
                    );

                    if($this->KeyIdParent){
                        $values += array($this->KeyIdParent => $row[$this->KeyIdParent]);
                    }

                    array_unshift($breadCrumb, $values);


                    $id = (int)$row[$this->KeyIdParent];

                }else{

                    if($this->KeyIdLang){
                        $where = array($this->KeyIdLang => $id);
                    }else{
                        $where = array($this->KeyId => $id);
                    }

                    $this->where($where);
                    $this->toArray();
                    $row = $this->row($tableName);

                    $values = array(
                        'id' => $row[$this->KeyId],
                        'title' => $row[$this->KeyTitle],
                        'level'=> $this->level
                    );

                    $this->breadCrumb = array($values,$values);
                    return $this;
                }
            }
        }

        if($id > 0){

            $this->where(array($this->KeyIdParent => $id));
            $this->toArray();
            $row = $this->row($tableName);
            $values = array(
                'id' => $row[$this->KeyId],
                'title' => $row[$this->KeyTitle],
                'level'=> $this->level
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
        return $this;
    }

    public function get()
    {
        return $this->breadCrumb;
    }

    public function endBreadCrumb()
    {
        return end($this->breadCrumb);
    }


    public function countChild($id,$tableName = false,$KeyIdParent = false)
    {
        if($tableName){
            $this->setTableName($tableName);

        }

        $where = array();

        if($KeyIdParent){
            $where = array($KeyIdParent => $id);
        }elseif($this->KeyIdParent){
            $where = array($this->KeyIdParent => $id);
        }


        if($this->getLang() and $this->KeyLang){
           $where += array($this->KeyLang => $this->getLang());
        }


        $this->toArray();
        $this->where($where);
        $count = count($this->all());

        if($count){
            return $count ;
        }else{
            return false;
        }
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }


    public function getLevel($id = false)
    {
        if($id){
           $this->breadCrumb($id);
        }
        return $this->level;
    }

    public function getPath($limit)
    {
        $this->path = false;
        $count = 0;
        foreach($this->breadCrumb as $path){
            $count++;
            if($count > $limit){
                $this->path .= $path[$this->KeyName].'/';
            }
        }
        return $this->path;
    }

    public function langActive($id,$lang,$tableName = false)
    {
        if($tableName){
            $this->setTableName($tableName);
        }
        if($this->KeyIdLang){
            $this->where(array($this->KeyIdLang => $id,$this->KeyLang => $lang));
            $this->toArray();
            return  $this->row();
        }
        return false;
    }

}

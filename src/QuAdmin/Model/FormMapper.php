<?php

namespace QuAdmin\Model;

class FormMapper extends AbstractMapper implements Interfaces\FormMapperInterface
{

    public function options($form,$quFormOptions)
    {

        $this->setQuAdminModelOptions($quFormOptions);

        $where                  = $form['where'];
        $order                  = $form['order'];
        $fieldKeyName           = $form['fieldKeyName'];
        $fieldKeyLabel          = $form['fieldKeyLabel'];

        if($order) {  $this->order($order); }
        if($where) {  $this->where($where); }


        $selector = array();

        if(isset($form['default']) and $form['default']){
            $selector  +=  $form['default'];
        }

        $this->toArray();
        foreach($this->all($this->getQuAdminModelOptions()->getTableName()) as  $sel){
            if(isset($sel[$fieldKeyName]) and isset($sel[$fieldKeyLabel])){

                if(isset($sel['element'])){

                    $selector[$sel[$fieldKeyName]] = array(
                        'label'=>$sel[$fieldKeyLabel],
                        'element'=>$sel['element'],
                        'filter'=>$sel['filter']
                    );

                }else{

                    $selector[$sel[$fieldKeyName]] = $sel[$fieldKeyLabel];

                }

            }
        }

        return $selector;
    }


    public function findByParentRecursive($id_parent,$tableName = null,$KeyIdParent = null,$KeyId = null)
    {
        if($KeyIdParent)
        {
            $this->where(array($KeyIdParent=> $id_parent));
            $this->toArray();
            $this->Order($KeyId.' asc');
            return $this->all($tableName);
        }
        elseif($this->KeyIdParent)
        {
            $this->where(array($this->KeyIdParent=> $id_parent));
            $this->toArray();
            $this->Order($this->KeyId.' asc');
            return $this->all($tableName);
        }
        return false;
    }


}
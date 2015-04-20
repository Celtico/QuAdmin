<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{

    public function variables()
    {

        /**
         * Conserve Local Model
         */
        $this->getModelBreadCrumb()->setQuAdminModelOptions($this->getOptions());
        $this->getModelBreadCrumb()->breadCrumb($this->getId(),false,$this->getModel());
        /**
         * Reference Url Model
         */
        $LinkerModels = $this->getQuAdminModelOptions()->getLinkerModels();
        if(count($LinkerModels)){
            foreach($LinkerModels as $LinkerModel){
                if(isset($LinkerModel['model']) and $LinkerModel['model'] == 'qu_'.$this->getModel().'_model'){

                    $this->setOptions($this->Service('qu_'.$this->getModel().'_model'));
                    $this->setQuAdminModelOptions($this->getOptions());

                    //Id Parent Linker
                    $TableKeyFields = $this->getQuAdminModelOptions()->getTableKeyFields();
                    $TableKeyFields['key_id_parent'] = $LinkerModel['key_id_parent'];
                    $this->getQuAdminModelOptions()->setTableKeyFields($TableKeyFields);

                }
            }
        }






        /**
         * get Field Keys
         */
        $this->getField();

        /**
         * Local Model
         */
        $modelIndex = $this->getModelIndex()->setQuAdminModelOptions($this->getOptions());
        $PagOptions = $modelIndex->getOptionsPaginator();
        $this->setPage($PagOptions['p']);
        $this->setNumberPage($PagOptions['n']);
        $model = $this->getModelIndex()->findByParent(null,$this->getId(),$this->getLang(),$this->getPage(),$this->getNumberPage());



        /**
         * PARENT
         */
        $row = array();
        $rowModel = array();
        /*
        if($this->KeyIdParent != '')
        {
            $modelParent = $this->getModelIndex();
            $table       =  '`'.$modelParent->getTableName().'`';
            $parentAll   = $modelParent->SELECT_ALL("SELECT * FROM  ".$table." ");

            if(isset($LinkerModels[0]['table'])){

                $table       =  '`'.$LinkerModels[0]['table'].'`';
                $parentModel = $modelParent->SELECT_ALL("SELECT * FROM  ".$table." ");

            }
            foreach($parentAll as $row_all){
                $id_parent = $row_all[$this->KeyId];
                foreach($parentAll as $ll){
                    if($id_parent == $ll[$this->KeyIdParent]){
                        $row[$id_parent][] = array();
                    }
                }

                if(isset($parentModel)){
                    foreach($parentModel as $allM){
                        if($id_parent == $allM[$this->KeyIdParent]){
                            $rowModel[$id_parent][] = array();
                        }
                    }
                }
            }

        }


        else if($this->KeyIdParent == ''){

            $modelParent = $this->getModelIndex();
            $table       =  '`'.$modelParent->getTableName().'`';
            $parentAll   = $modelParent->SELECT_ALL("SELECT * FROM  ".$table." ");

            if(isset($LinkerModels[0]['table'])){

                $table       =  '`'.$LinkerModels[0]['table'].'`';
                $parentModel = $modelParent->SELECT_ALL("SELECT * FROM  ".$table." ");

            }


            foreach($parentAll as $row_all){
                $id_parent = $row_all[$this->KeyId];
                if(isset($parentModel)){
                    foreach($parentModel as $allM){
                        if($id_parent == $allM[$LinkerModels[0]['key_id_parent']]){
                            //$rowModel[$id_parent][] = array();
                        }
                    }
                }
            }

        }
*/


        $dataController = array(
            'id'                    => $this->getId(),
            'lang'                  => $this->getLang(),
            'route'                 => $this->getRoute(),
            'npp'                   => $this->getNumberPage(),
            'page'                  => $this->getPage(),
            'options'               => $this->getOptions(),
            'id_parent'             => $this->getIdParent(),
            'q'                     => '',
            'list'                  => $model,
            'key'                   => $this->key,
            'level'                 => $this->getLevel(),
            'PathTemplateRender'    => $this->getPathTemplateRender(),
            'model'                 => $this->getModel(),
            'parent'                => $row,
            'parentModel'           => $rowModel,
        );

        return  $dataController;
    }

    public function getVariables(){

        return  $this->variables();
    }

    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/index');
        return  $model->setVariables($this->getVariables());

    }

    public function getCsv() {

        /**
         * Local Model
         */
        $modelIndex = $this->getModelIndex()->setQuAdminModelOptions($this->getOptions());
        $PagOptions = $modelIndex->getOptionsPaginator();
        $this->setPage($PagOptions['p']);
        $this->setNumberPage($PagOptions['n']);
        $model = $this->getModelIndex()->findByParent(null,$this->getId(),$this->getLang(),1,0);
        $fields = $this->getModelIndex()->getTableFieldsCleanData();
        $name = $this->getModelIndex()->getTableName();

        $tabla = '
        <%response.ContentType="application/vnd.ms-excel"%>
        <html>
        <head>
            <style type="text/css">
                td { border:1px dotted #ccc;}
            </style>
        </head>
        <body>';

        $tabla .= '<table>';

        $tabla .= '<tr>';
        foreach($fields as $f){
            $tabla .= '<td>'.$f.'</td>';
        }


        if($name == 'qu-inscripcions'){
            $tabla .= '<td>Aco.</td>';
            $tabla .= '<td>PVP</td>';
        }


        $tabla .= "</tr>";

        foreach($model  as $d){
            $tabla .= '<tr>';
            foreach($fields as $f){
                $tabla .= '<td>'.$d[$f].'</td>';
            }

            if($name == 'qu-inscripcions'){



                if( $d['acompanante'] == '1'){

                    $amount = 120.00;
                    $aco = 'M - 1';

                }elseif($d['acompanante'] == '2'){

                    $aco = 'C' . ' - '.$d['num_acompanante'].'';
                    $amount = 65.00 + ( $d['num_acompanante'] * 25.00);

                }else {

                    $aco = 'S';
                    $amount = 65.00;

                }



                $tabla .= '<td>'. $aco.'</td>';
                $tabla .= '<td>'.$amount.' €</td>';
            }

            $tabla .= "</tr>";
        }

        $tabla .= "</table>";
        $tabla .= "</body></html>";

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/csv');
        $model->setVariables(array('tabla'=>$tabla,'name'=>$name));
        return  $model->setTerminal(true);

    }
}
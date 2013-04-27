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
}
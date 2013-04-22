<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;

class IndexAjaxController extends AbstractController
{
    public function variables()
    {

        $LinkerModels = $this->getQuAdminModelOptions()->getLinkerModels();
        if(count($LinkerModels)){
            foreach($LinkerModels as $LinkerModel){
                if(isset($LinkerModel['model']) and $LinkerModel['model'] == 'qu_'.$this->getModel().'_model'){
                    $this->setOptions($this->Service('qu_'.$this->getModel().'_model'));
                    $this->setQuAdminModelOptions($this->getOptions());
                }
            }
        }


        $this->getModelIndex()->setQuAdminModelOptions($this->getOptions());
        if($this->getNewOrder() != '')
            $this->getModelIndex()->newOrder($this->getNewOrder(),$this->getPageOrder(),$this->getPage(),$this->getNumberPage());

        $this->getField();

        return array(
            'id'        => $this->getId(),
            'lang'      => $this->getLang(),
            'id_parent' => $this->getIdParent(),
            'route'     => $this->getRoute(),
            'q'         => $this->getSearch(),
            'npp'       => $this->getNumberPage(),
            'page'      => $this->getPage(),
            'options'   => $this->getOptions(),
            'list'      => $this->getModelIndex()->findByParent(
                           $this->getSearch(),
                           $this->getId(),
                           $this->getLang(),
                           $this->getPage(),
                           $this->getNumberPage()),
            'ajax'      => 1,
            'key'       => $this->key,
            'level'     => $this->getLevel(),
            'PathTemplateRender' => $this->getPathTemplateRender(),
            'model'     => $this->getModel(),
        );
    }

    public function getVariables(){

        return  $this->variables();
    }

    public function getViewModel(){

        $model = new ViewModel();
        $model->setTemplate('qu-admin/qu-admin/qu_admin_table_ajax');
        $model->setVariables($this->getVariables());
        return  $model->setTerminal(true);

    }
}

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
        $this->getModelIndex()->setQuAdminModelOptions($this->getOptions());

        if($this->getNewOrder() != '')
            $this->getModelIndex()->newOrder($this->getNewOrder(),$this->getPageOrder(),$this->getPage(),$this->getNumberPage());


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
            'PathTemplateRender' => $this->getPathTemplateRender()
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

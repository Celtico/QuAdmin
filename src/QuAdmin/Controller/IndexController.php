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
        $modelIndex = $this->getModelIndex()->setQuAdminModelOptions($this->getOptions());
        $PagOptions = $modelIndex->getOptionsPaginator();
        $this->setPage($PagOptions['p']);
        $this->setNumberPage($PagOptions['n']);
        $model = $this->getModelIndex()->findByParent(null,$this->getId(),$this->getLang(),$this->getPage(),$this->getNumberPage());

        return  array(
            'id'            => $this->getId(),
            'lang'          => $this->getLang(),
            'route'         => $this->getRoute(),
            'npp'           => $this->getNumberPage(),
            'page'          => $this->getPage(),
            'options'       => $this->getOptions(),
            'list'          => $model,
            'id_parent'     => $this->getIdParent(),
            'q'             => '',
            'key'           => $this->key,
            'PathTemplateRender' => $this->getPathTemplateRender()
        );
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
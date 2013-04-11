<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Filter\Word\CamelCaseToDash as CamelCaseToDashFilter;


class AbstractController extends AbstractActionController
{

    protected $quAdminModelOptions;

    protected $id;
    protected $id_parent;
    protected $route;
    protected $lang;
    protected $message;
    protected $post;
    protected $isPost;
    protected $toRoute;
    protected $date;
    protected $user;
    protected $options;
    protected $translate;
    protected $form;
    protected $modelAdd;
    protected $modelDelete;
    protected $modelDuplicate;
    protected $modelEdit;
    protected $modelForm;
    protected $modelIndex;
    protected $newOrder;
    protected $pageOrder;
    protected $numberPage;
    protected $page;
    protected $search;

    public $KeyIdParent;
    public $KeyId;
    public $KeyLang;
    public $KeyName;
    public $KeyTitle;
    public $KeyIdLangLinker;
    public $KeyIdAuthor;
    public $KeyDate;
    public $KeyModified;
    public $KeyOrder;
    public $KeyLevel;
    public $KeyPath;
    public $key;

    protected $pathTemplateRender;

    public function setPathTemplateRender($pathTemplateRender)
    {
        $this->pathTemplateRender = $pathTemplateRender;
    }

    public function getPathTemplateRender()
    {
        $controllerClass = get_class( $this->app()->getMvcEvent()->getTarget());
        $fil = new CamelCaseToDashFilter;
        if(!$this->pathTemplateRender){
            $this->setPathTemplateRender(
                   strtolower ( str_replace('\\Controller\\','/', $fil->filter($controllerClass)) )
            );
        }
        return $this->pathTemplateRender;
    }

    public function setNewOrder($newOrder)
    {
        $this->newOrder = $newOrder;
    }

    public function getNewOrder()
    {
        if(!$this->newOrder){
            $this->setNewOrder(
                $this->query()->get('item')
            );
        }
        return $this->newOrder;
    }



    public function setPageOrder($pageOrder)
    {
        $this->pageOrder = $pageOrder;
    }

    public function getPageOrder()
    {
        if(!$this->pageOrder){
            $this->setPageOrder(
                $this->query()->get('n')
            );
        }
        return $this->pageOrder;
    }

    public function setNumberPage($numberPage)
    {
        $this->numberPage = $numberPage;
    }

    public function getNumberPage()
    {
        if(!$this->numberPage){
            $this->setNumberPage(
                $this->query()->get('npp')
            );
        }
        return $this->numberPage;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        if(!$this->page){
            $this->setPage(
                $this->query()->get('page')
            );
        }
        return $this->page;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getSearch()
    {
        if(!$this->search){
            $this->setSearch(
                $this->query()->get('q')
            );
        }
        return $this->search;
    }

    protected function query()
    {
        return $this->app()->getRequest()->getQuery();
    }
    protected function match()
    {
        return $this->app()->getMvcEvent()->getRouteMatch();
    }
    protected function app()
    {
        return $this->getServiceLocator()->get('application');
    }
    protected function plug()
    {
        return $this->getServiceLocator()->get('ControllerPluginManager');
    }
    protected function Service($s)
    {
        $service = $this->getServiceLocator();
        return  $service->get($s);
    }

    protected function setDate($date)
    {
        $this->date = $date;
    }

    protected function getDate()
    {
        if(!$this->date){ $this->setDate(date("Y-m-d H:i:s")); }
        return $this->date;
    }


    protected function setId($id)
    {
        $this->id = $id;
    }

    protected function getId()
    {
        if(!$this->id){
            $this->setId(
                $this->match()->getParam('id','0')
            );
        }
        return $this->id;
    }

    protected function setIdParent($id_parent)
    {
        $this->id_parent = $id_parent;
    }

    protected function getIdParent()
    {
        if(!$this->id_parent){
            $this->setIdParent(
                $this->match()->getParam('id_parent','0')
            );
        }
        return $this->id_parent;
    }

    protected function setIsPost($isPost)
    {
        $this->isPost = $isPost;
    }

    protected function getIsPost()
    {
        if(!$this->isPost){ $this->setIsPost($this->app()->getRequest()->isPost()); }
        return $this->isPost;
    }

    protected function setLang($lang)
    {
        $this->lang = $lang;
    }

    protected function getLang()
    {
        if(!$this->lang){ $this->setLang($this->match()->getParam('lang')); }
        return $this->lang;
    }

    protected function setMessage($message)
    {
        $this->message = $message;
    }

    protected function getMessage(array $message)
    {
        if(!$this->message){
            $this->setMessage(
                $this->plug()->get('flashmessenger')->setNamespace('QuAdmin')
            );
        }
        $this->message->addMessage($message);
        return $this->message;
    }

    protected function setModelAdd($modelAdd)
    {
        $this->modelAdd = $modelAdd;
    }

    protected function getModelAdd()
    {
        if(!$this->modelAdd){
            $this->setModelAdd(
                $this->Service('qu_admin_model_add')
            );
        }
        return $this->modelAdd;
    }

    protected function setModelDelete($modelDelete)
    {
        $this->modelDelete = $modelDelete;
    }

    protected function getModelDelete()
    {
        if(!$this->modelDelete){
            $this->setModelDelete(
                $this->Service('qu_admin_model_delete')
            );
        }
        return $this->modelDelete;
    }

    protected function setModelDuplicate($modelDuplicate)
    {
        $this->modelDuplicate = $modelDuplicate;
    }

    protected function getModelDuplicate()
    {
        if(!$this->modelDuplicate){
            $this->setModelDuplicate(
                $this->Service('qu_admin_model_duplicate')
            );
        }
        return $this->modelDuplicate;
    }

    protected function setModelEdit($modelEdit)
    {
        $this->modelEdit = $modelEdit;
    }

    protected function getModelEdit()
    {
        if(!$this->modelEdit){
            $this->setModelEdit(
                $this->Service('qu_admin_model_edit')
            );
        }
        return $this->modelEdit;
    }

    protected function setModelForm($modelForm)
    {
        $this->modelForm = $modelForm;
    }

    protected function getModelForm()
    {
        if(!$this->modelForm){
            $this->setModelForm(
                $this->Service('qu_admin_model_form')
            );
        }
        return $this->modelForm;
    }

    protected function setForm($form)
    {
        $this->form = $form;
    }

    protected function getForm()
    {
        if(!$this->form){
            $this->setForm(
                $this->Service('qu_admin_form')
            );
        }
        return $this->form;
    }

    protected function setModelIndex($modelIndex)
    {
        $this->modelIndex = $modelIndex;
    }

    protected function getModelIndex()
    {
        if(!$this->modelIndex){
            $this->setModelIndex(
                $this->Service('qu_admin_model_index')
            );
        }
        return $this->modelIndex;
    }

    protected function setOptions($options)
    {
        $this->options = $options;
    }

    protected function getOptions()
    {
        if(!$this->options){
            $this->setOptions(
                $this->getQuAdminModelOptions()
            );
        }
        return $this->options;
    }

    protected function setPost($post)
    {
        $this->post = $post;
    }

    protected function getPost()
    {
        if(!$this->post){
            $this->setPost(
                $this->app()->getRequest()->getPost()
            );
        }
        return $this->post;
    }



    protected function setRoute($route)
    {
        $this->route = $route;
    }

    protected function getRoute()
    {
        if(!$this->route){
            $this->setRoute(
                $this->match()->getMatchedRouteName()
            );
        }
        return $this->route;
    }

    protected function setToRoute($toRoute)
    {
        $this->toRoute = $toRoute;
    }

    protected function getToRoute($route,array $array)
    {
        if(!$this->toRoute){
            $this->setToRoute(
                $this->plug()->get('redirect')->toRoute($route,$array)
            );
        }
        return $this->toRoute;
    }

    protected function setTranslate($translate)
    {
        $this->translate = $translate;
    }

    protected function getTranslate($tex)
    {
        if(!$this->translate){
            $this->setTranslate($this->Service('translator'));
        }
        return $this->translate->Translate($tex);
    }

    protected function setUser($user)
    {
        $this->user = $user;
    }

    protected function getUser()
    {
        if(!$this->user){
            $this->setUser(
              $this->Service('zfcuser_auth_service')->getIdentity()
            );
        }
        if(method_exists($this->user,'getId')){
            return $this->user->getId();
        }else{
            echo 'error security'; die();
        }
    }



    /* INSERT QuAdminModelOptions  */



    public function getQuAdminModelOptions()
    {
        if(!$this->quAdminModelOptions){
            $this->setQuAdminModelOptions($this->Service('qu_admin_service')->getQuAdminModelOptions());
        }
        return $this->quAdminModelOptions;
    }
    public function setQuAdminModelOptions($quAdminModelOptions)
    {
        $this->quAdminModelOptions = $quAdminModelOptions;
        $this->getField();
        return $this;
    }
    public function getField()
    {
        $TableFields    = $this->getQuAdminModelOptions()->getTableKeyFields();
        foreach($TableFields as $k => $e){

            $this->$k = $e;
            $kView  = str_replace('Key','',$k);
            $this->key[$kView]  =  $e;
        }
        return $this;
    }

}
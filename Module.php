<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin;

use QuAdmin\Db\Adapter\DbAdapterAwareInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Validator\AbstractValidator;

/**
 * Class Module
 * @package QuAdmin
 */
class Module implements BootstrapListenerInterface
{

    /**
     * @param \Zend\EventManager\EventInterface $e
     *
     * @return array|void
     */
    public function onBootstrap(EventInterface $e)
    {
        $app        = $e->getApplication();
        $sm         = $app->getServiceManager();
        $em         = $app->getEventManager();

        $em->attach($sm->get('qu_admin_strategy'));
        $em->attach($sm->get('qu_plupload_strategy'));
    }


    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                function($instance, $sm){
                    if($instance instanceof DbAdapterAwareInterface){
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $instance->setDbAdapter($dbAdapter);
                    }
                },
            ),
            'invokables' => array(

                'qu_admin_controller_add'          => 'QuAdmin\Controller\AddController',
                'qu_admin_controller_delete'       => 'QuAdmin\Controller\DeleteController',
                'qu_admin_controller_duplicate'    => 'QuAdmin\Controller\DuplicateController',
                'qu_admin_controller_edit'         => 'QuAdmin\Controller\EditController',
                'qu_admin_controller_config'       => 'QuAdmin\Controller\ConfigController',
                'qu_admin_controller_index'        => 'QuAdmin\Controller\IndexController',
                'qu_admin_controller_index_ajax'   => 'QuAdmin\Controller\IndexAjaxController',
                'qu_admin_controller_upload_ajax'  => 'QuAdmin\Controller\UploadAjaxController',

                'qu_admin_model_add'          => 'QuAdmin\Model\AddMapper',
                'qu_admin_model_delete'       => 'QuAdmin\Model\DeleteMapper',
                'qu_admin_model_duplicate'    => 'QuAdmin\Model\DuplicateMapper',
                'qu_admin_model_edit'         => 'QuAdmin\Model\EditMapper',
                'qu_admin_model_bread_crumb'  => 'QuAdmin\Model\BreadCrumb',
                'qu_admin_model_index'        => 'QuAdmin\Model\IndexMapper',
                'qu_admin_model_index_ajax'   => 'QuAdmin\Model\IndexAjaxMapper',
                'qu_admin_model_languages'    => 'QuAdmin\Model\LanguagesMapper',
                'qu_admin_model_form'         => 'QuAdmin\Model\FormMapper',

                'qu_admin_service'              => 'QuAdmin\Service\QuAdminService',
                'qu_admin_strategy'             => 'QuAdmin\Strategy\QuAdminStrategy',
                'qu_admin_form'                 => 'QuAdmin\Form\QuForm',
            ),
            'factories' => array(
                'qu_admin_navigation' => 'QuAdmin\Navigation\QuAdminNavigation',
                'qu_plupload_strategy' => function($sl) {
                   $qu_plupload_strategy =  new \QuAdmin\Strategy\QuPluploadStrategy;
                    $qu_plupload_strategy->setServiceLocator($sl->get('plupload_service'));
                    return $qu_plupload_strategy;
                },
            ),
        );
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(

                'qu_admin_user_name' => function($sm) {
                    return new View\Helper\QuAdminUserName($sm->getServiceLocator());
                },
                'qu_admin_user_id' => function($sm) {
                    return new View\Helper\QuAdminUserId($sm->getServiceLocator());
                },
                'qu_admin_documents' => function ($sm) {
                    return new View\Helper\QuAdminDocuments($sm->getServiceLocator());
                },
                'qu_admin_messages' => function($sm) {
                    return new View\Helper\QuAdminFlashMessenger($sm->getServiceLocator());
                },
                'qu_admin_title' => function ($sm) {
                    $page = $sm->getServiceLocator()->get('qu_admin_navigation');
                    return new View\Helper\QuAdminTitle($page);
                },
                'qu_admin_lang_nav_table'=>function($sm){
                    return new View\Helper\QuAdminLangNavTable($sm->getServiceLocator());
                },
                'qu_admin_languages' => function ($sm) {
                    return new View\Helper\QuAdminLanguages($sm->getServiceLocator());
                },
                'qu_admin_bread_crumb' => function ($sm) {
                    return new View\Helper\QuAdminBreadCrumb($sm->getServiceLocator());
                },
                'qu_admin_title_by_db' => function ($sm) {
                    return new View\Helper\QuAdminTitleByDb($sm->getServiceLocator());
                },
                'qu_admin_navigation' => function ($sm) {
                    return new View\Helper\QuAdminNavigation($sm->getServiceLocator());
                },
                'qu_admin_add' => function ($sm) {
                    return new View\Helper\QuAdminAdd($sm->getServiceLocator());
                },
                'qu_admin_status' => function ($sm) {
                    return new View\Helper\QuAdminStatus($sm->getServiceLocator());
                },
                'qu_admin_map' => function ($sm) {
                    return new View\Helper\QuAdminMap($sm->getServiceLocator());
                },

                'QuPluploadHelp' => function ($sm) {
                    $config = $sm->getServiceLocator()->get('config');
                    return new View\Helper\QuPluploadHelp(
                        isset($config['QuConfig']['QuPlupload']) ? $config['QuConfig']['QuPlupload'] : array()
                    );
                },
                'QuPluploadHelpLoad' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $plupload_service = $sm->get('plupload_service'); $config = $sm->get('config');
                    return new View\Helper\QuPluploadHelpLoad($plupload_service,
                        isset($config['QuConfig']['QuPlupload']) ? $config['QuConfig']['QuPlupload'] : array()
                    );
                },

            ),
        );

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
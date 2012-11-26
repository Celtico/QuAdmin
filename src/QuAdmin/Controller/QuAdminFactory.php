<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use QuAdmin\Model\QuView;
use QuAdmin\Model\QuSave;
use QuAdmin\Model\QuOrder;
use QuAdmin\Model\QuDelete;
use QuAdmin\Model\QuDuplicate;
use QuAdmin\Model\QuUtilities;

use QuAdmin\Form\QuForm;
use QuAdmin\Model\QuThumb;

class QuAdminFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $service
     *
     * @return QuController|mixed
     */
    public function createService(ServiceLocatorInterface $service) {

        // Service
        $sm              = $service->getServiceLocator();
        $Config          = $sm->get('config');
        $db              = $sm->get('Zend\Db\Adapter\Adapter');
        $User            = $sm->get('zfcuser_auth_service');
        $PhpThumb        = $sm->get('WebinoImageThumb');
        $Translator      = $sm->get('translator');
        $Application     = $sm->get('application');

        // March
        $Match           = $Application->getMvcEvent()->getRouteMatch();
        $Route           = $Match->getMatchedRouteName();
        $Id              = (int)$Match->getParam('id','0');

        // Presses Type
        $Type            = explode('/',$Route);
        $Type            = $Type[1];
        $Type            = strtolower($Type);

        // Model
        $View            = new QuView        ($db);
        $Save            = new QuSave        ($db);
        $Order           = new QuOrder       ($db);
        $Delete          = new QuDelete      ($db);
        $Duplicate       = new QuDuplicate   ($db);
        $Utilities       = new QuUtilities   ($db);

        // Set Config in Duplicate
        $Duplicate->setConfig($Config['QuAdminConfig']['QuPhpThumb']);

        // Thumbnail in Save and Delete
        $QuPhpThumb      = new QuThumb($PhpThumb,$Config['QuAdminConfig']['QuPhpThumb']);
        $Save            ->setQuPhpThumb($QuPhpThumb);
        $Delete          ->setQuPhpThumb($QuPhpThumb);

        // Form
        $Form            = new QuForm  ($Utilities,$Translator,$Id,$Type);

        // Actions
        $ViewAction      = new QuActionView         ($View,$Form,$User);
        $AjaxAction      = new QuActionAjax         ($View,$Order);
        $AddAction       = new QuActionAdd          ($View,$Form,$Save,$User);
        $EditAction      = new QuActionEdit         ($View,$Form,$Save,$User,$Delete);
        $DuplicateAction = new QuActionDuplicate    ($Duplicate);
        $DeleteAction    = new QuActionDelete       ($Delete);
        $DeleteDocAction = new QuActionDeleteDoc    ($Delete);

        // Controller
        $controller      = new QuAdmin;

        $controller->setViewAction      ($ViewAction);
        $controller->setAjaxAction      ($AjaxAction);
        $controller->setAddAction       ($AddAction);
        $controller->setEditAction      ($EditAction);
        $controller->setDuplicateAction ($DuplicateAction);
        $controller->setDeleteAction    ($DeleteAction);
        $controller->setDeleteDocAction ($DeleteDocAction);
        $controller->setTranslator      ($Translator);

        return $controller;
    }

}
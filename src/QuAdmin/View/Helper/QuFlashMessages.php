<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

class QuFlashMessages extends AbstractHelper
{
    /**
     * @var \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected $flashMessenger;

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $plugin
     */
    public function __construct(FlashMessenger $plugin)
    {
        $this->flashMessenger = $plugin;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $message = '';
        $this->flashMessenger->setNamespace('Cms');
        if (count($this->flashMessenger)) {
            foreach ($this->flashMessenger as $msg){

                $class = (isset($msg['type'])) ? "{$msg['type']}" : '';
                $message = '
                <div class="alert alert-'. strtolower($class) .'">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    '.$msg['message'].'
                </div>';
            }
        }
        $this->flashMessenger->setNamespace('default');
        return $message;
    }
}

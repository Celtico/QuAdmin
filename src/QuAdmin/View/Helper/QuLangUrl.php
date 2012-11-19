<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class QuLangUrl extends AbstractHelper
{
    protected $Service;

    /**
     * @param $Service
     */
    public function __construct($Service)
    {
        $this->Service = $Service;
    }

    /**
     * @param $lang
     *
     * @return string
     */
    public function __invoke($lang)
    {
        return $this->getLangUrl($lang);
    }

    /**
     * @param $lang
     *
     * @return string
     */
    public function getLangUrl($lang)
    {
        $router       = $this->Service->get('router');
        $request      = $this->Service->get('request');
        $routeMatch   = $router->match($request);
        $MatchName    = strtolower($routeMatch->getMatchedRouteName());
        $MatchNameEx  = explode('/',$MatchName);

        if(isset($MatchNameEx[1])){
            return  $MatchNameEx[0].'/'.$lang.'/'.$MatchNameEx[1];
        }else{
            return;
        }
    }
}

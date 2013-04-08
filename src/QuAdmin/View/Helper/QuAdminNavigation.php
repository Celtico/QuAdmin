<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\View\Helper;

use Zend\Navigation\Page\AbstractPage;
use Zend\View\Helper\Navigation\Menu;

class QuAdminNavigation extends  Menu {

    /**
     * CSS class to use for the ul element
     *
     * @var string
     */
    protected $ulClass = 'navigation';

    /**
     * @var
     */
    public  $icon;

    // Public methods:

    /**
     * Returns an HTML string containing an 'a' element for the given page if
     * the page's href is not empty, and a 'span' element if it is empty
     *
     * Overrides {@link AbstractHelper::htmlify()}.
     *
     * @param  AbstractPage $page   page to generate HTML for
     * @param bool $escapeLabel     Whether or not to escape the label
     * @return string               HTML string for the given page
     */
    public function htmlify(AbstractPage $page, $escapeLabel = true)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();

        // translate label and title?
        if (null !== ($translator = $this->getTranslator())) {
            $textDomain = $this->getTranslatorTextDomain();
            if (is_string($label) && !empty($label)) {
                $label = $translator->translate($label, $textDomain);
            }
            if (is_string($title) && !empty($title)) {
                $title = $translator->translate($title, $textDomain);
            }
        }

        // get attribs for element
        $attribs = array(
            'id'   => $page->getId(),
            'title'  => $title,
            'class'  => $page->getClass()
        );

        // does page have a href?
        $href = $page->getHref();
        if ($href) {
            $element = 'a';
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
        } else {
            $element = 'span';
        }

        $html = '<' . $element . $this->htmlAttribs($attribs) . '>';
        if($page->icon){
            $html .= '<span class="iconb" data-icon="'.$page->icon.'"></span>';
        }
        if ($escapeLabel === true) {
            $escaper = $this->view->plugin('escapeHtml');
            $html .= $escaper($label);
        } else {
            $html .= $label;
        }
        $html .= '</' . $element . '>';

        return $html;
    }
/*
    public function title(){
        return $this->getTitle();
    }
*/

}
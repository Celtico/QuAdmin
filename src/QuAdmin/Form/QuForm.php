<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

class QuForm extends Form
{
    /**
     * @var
     */
    protected $sm;

    /**
     * @param string $sm
     */
    public function __construct($sm = '')
    {

        $translator = new Translator;
        $translator->addTranslationFile("phparray",'./vendor/ZF2/resources/languages/es/Zend_Validate.php');
        AbstractValidator::setDefaultTranslator($translator);

        parent::__construct('cms');

        $this->setAttribute('method', 'post');
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'parametres',
            'options' => array(
                'label' => 'Parametres',
                'value_options' => $sm->Sel('parameters','name'),
            ),
        ));

        //Int
        $this->add(array(
            'name'     => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name'     => 'id_parent',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name'     => 'id_author',
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name'     => 'id_lang',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name'     => 'lang',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name'     => 'order',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        //Data
         $this->add(array(
            'name'     => 'date',
             'attributes' => array(
                 'type'  => 'hidden',
             ),
        ));
         $this->add(array(
            'name'     => 'modified',
             'attributes' => array(
                 'type'  => 'hidden',
             ),
        ));
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'text',
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name'     => 'status',
             'options' => array(
                 'label' => 'Status',
                 'value_options' => array(
                     'Public' =>'Public',
                     'Prèvia' =>'Prèvia',
                     'Privat' =>'Privat',
                 ),
             ),
             'attributes' => array(
                 'type'  => 'select',
                 'value' => '1' //set selected to '1'

             ),
         ));
         $this->add(array(
            'name'     => 'name',
             'options' => array(
                 'label' => 'Url',
             ),
             'attributes' => array(
                 'type'  => 'text',
             ),
         ));
        $this->add(array(
            'name'     => 'title',
            'options' => array(
                'label' => 'Titól',
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
         $this->add(array(
            'name'     => 'resum',
             'options' => array(
                 'label' => 'Resum',
             ),
             'attributes' => array(
                 'type'  => 'textarea',
             ),
         ));
        $this->add(array(
            'name'     => 'text',
            'options' => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'type'  => 'textarea',
            ),
        ));
         $this->add(array(
            'name'     => 'type',
             'options' => array(
                 'label' => 'text',
             ),
             'attributes' => array(
                 'type'  => 'text',
             ),
         ));
        $this->add(array(
            'name'     => 'imatge',
            'options' => array(
                'label' => 'imatge',
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Guardar',
                'id' => 'submitbutton',
            ),
        ));
        $this->add(array(
            'name'     => 'notes',
            'options' => array(
                'label' => 'notes',
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
    }

}

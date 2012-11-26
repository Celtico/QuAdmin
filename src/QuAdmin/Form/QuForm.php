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
    protected $Utilities;
    protected $Translator;
    protected $Id;
    protected $Type;

    /**
     * @param int|null|string $Utilities
     * @param array           $Translator
     * @param                 $Id
     * @param                 $Type
     */
    public function __construct($Utilities,$Translator,$Id,$Type)
    {
        parent::__construct();

        $this->Utilities     = $Utilities;
        $this->Translator   = $Translator;
        $this->Id           = $Id;
        $this->Type         = $Type;

        $translator = new Translator;
        $translator->addTranslationFile("phparray",'./vendor/ZF2/resources/languages/es/Zend_Validate.php');
        AbstractValidator::setDefaultTranslator($translator);

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name'    => 'id',
            'options' => array(
                'label' => $this->t('id'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name'     => 'id_parent',
            'options' => array(
                'label' => $this->t('id_parent'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name'     => 'id_author',
            'options' => array(
                'label' => $this->t('id_author'),
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name'     => 'id_lang',
            'options' => array(
                'label' => $this->t('id_lang'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name'     => 'type',
            'options' => array(
                'label' => $this->t('type'),
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name'     => 'name',
            'options' => array(
                'label' => $this->t('name'),
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name'     => 'order',
            'options' => array(
                'label' => $this->t('order'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name'     => 'date',
            'options' => array(
                'label' => $this->t('date'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name'     => 'modified',
            'options' => array(
                'label' => $this->t('modified'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name'     => 'status',
            'options' => array(
                'label' => $this->t('status'),
                'value_options' => array(
                    'Public'    =>'Public',
                    'Previous'  =>'Previous',
                    'Private'   =>'Private',
                ),
            ),
            'attributes' => array(
                'type'  => 'select',
            ),
        ));
        $this->add(array(
            'name'     => 'lang',
            'options' => array(
                'label' => $this->t('lang'),
            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'parameters',
            'options' => array(
                'label' => $this->t('parameters'),
                'value_options' => $this->Utilities->SelectOptions('parameters','name'),
            ),
        ));

        $SelectOptionsIdParent  =  array('0'=>'-');
        $SelectOptionsIdParent += $this->Utilities->SelectOptions($this->Type,'id');
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_parent',
            'options' => array(
                'label' => $this->t('id_parent'),
                'value_options' => $SelectOptionsIdParent,
            ),
            'attributes' => array(
                'value' => $this->Id,
            ),
        ));

        $this->add(array(
            'name'     => 'title',
            'options' => array(
                'label' => $this->t('title'),
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name'     => 'summary',
            'options' => array(
                'label' => $this->t('summary'),
            ),
            'attributes' => array(
                'type'  => 'textarea',
            ),
        ));
        $this->add(array(
            'name'     => 'documents',
            'options' => array(
                'label' => $this->t('documents'),
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name'     => 'content',
            'options' => array(
                'label' => $this->t('content'),
            ),
            'attributes' => array(
                'type'  => 'textarea',
            ),
        ));

        $this->add(array(
            'name'     => 'notes',
            'options' => array(
                'label' => $this->t('notes'),
            ),
            'attributes' => array(
                'type'  => 'textarea',
            ),
        ));
    }

    /**
     * @param string $text
     *
     * @return mixed
     */
    public function t($text = ''){
        return $this->Translator->Translate($text);
    }

}

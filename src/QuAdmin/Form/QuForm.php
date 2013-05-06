<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Form;

use QuAdmin\Util;

use Zend\Form\Element\Checkbox;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;
use Zend\EventManager\EventManager;

class QuForm extends Form
{
    protected $optionsForm;
    protected $options;
    protected $subForm;
    public    $events;

    public function __construct()
    {
        parent::__construct();
        $this->filter   = new InputFilter;
        $csrf = new CsrfElement('csrf');
        $csrf->setCsrfValidatorOptions(array('timeout' => null));
        $this->add($csrf);
    }

    public function addQuFormOptions($data,$model,$sl)
    {
        $tr = $sl->get('translator');
        $translator = new Translator;
        $translator->addTranslationFile(
            "phparray",
            './vendor/zendframework/zendframework/resources/languages/'.$tr->getLocale().'/Zend_Validate.php'
        );
        AbstractValidator::setDefaultTranslator($translator);

        foreach($this->optionsForm as $forms){
            $this->addForm($forms,$data,$model,$sl);
        }
        return $this;
    }

    public function addForm($forms,$data,$model,$sl)
    {
        $factory = new InputFactory();

        $fieldName = $forms['fieldset']['name'];

        if(isset($forms['serialized']) and $forms['serialized']){
            if(isset($data[$forms['fieldset']['name']])){
                $data['unSerialize'] = unserialize($data[$forms['fieldset']['name']]);
                if($data['unSerialize']){
                    $data = $data['unSerialize'];
                }
            }
        }
        if(isset($forms['explode']) and $forms['explode']){
            if(isset($data[$forms['fieldset']['name']])){
                $data[$forms['fieldset']['name']] = explode(', ',$data[$forms['fieldset']['name']]);
            }
        }

        $forms['fieldset']['name'] = $forms['fieldset']['name'].'-';
        $this->setBaseFieldset($this->add($forms['fieldset']));

        $this->add($subForm = new Form(),array('name'=>$fieldName));
        $subForm->setInputFilter( $subFilter  = new InputFilter() );

        if(isset($forms['serialized'])){
            unset($forms['serialized']);
        }
        if(isset($forms['explode'])){
            unset($forms['explode']);
        }
        unset($forms['fieldset']);

        if(isset($forms['database']))
        {
            $modelOptions = $sl->get($forms['database']['database']);
            $formDatabase = $model->options($forms['database'], $modelOptions);

            foreach($formDatabase as $name => $label)
            {
                if(isset($label['element'])){

                    if($label['element'] == 'checkbox'){

                        $subForm->add(array(
                            'type' => 'Zend\Form\Element\Checkbox',
                            'name' => $name,
                            'options' => array(
                                'label' => $label['label'],
                                'use_hidden_element' => true,
                                'checked_value' => '1',
                                'unchecked_value' => '0'
                            ),
                            'attributes' => array(
                                'class' => 'check',
                            )
                        ));

                    }elseif($label['element'] == 'ckeditor'){

                        $subForm->add(array(
                            'name'     => $name,
                            'options' => array(
                                'label' => $label['label']
                            ),
                            'attributes' => array(
                                'type' => 'textarea',
                                'id'=>'editor2',
                                'ck-editor'=>array(
                                    'tools'=>'large',
                                    'height'=>'250',
                                ),
                            ),
                        ));

                    }else{

                        $subForm->add(array(
                            'name'     => $name,
                            'options' => array(
                                'label' => $label['label']
                            ),
                            'attributes' => array(
                                'type' => $label['element'],
                                'class' => $forms['form']['attributes']['class'],
                            ),
                        ));
                    }

                }else{

                    $forms['form']['name']  = $name;
                    $forms['form']['options']['label'] = $label;
                    $subForm->add($forms['form']);
                    $subFilter->add($factory->createInput($forms['filter']));
                }
            }

        }else{

            foreach($forms as $form)
            {
                if(isset($form['form']))
                {
                    if(isset($form['attributes']))
                    {
                        $modelOptions = $sl->get($form['attributes']['database']);
                        $values = $model->options($form['attributes'],$modelOptions);
                        $subForm->add($form['form']);
                        $subFilter->add($factory->createInput($form['filter']));
                        $subForm->get($form['form']['name'])->setAttribute('options',$values);

                    }else{

                        $subForm->add($form['form']);
                        $subFilter->add($factory->createInput($form['filter']));
                    }
                }
            }
        }

        $subForm->populateValues($data);
        $this->subForm = $subForm;
        return $this;
    }

    public function prosesDataForm($dataPost)
    {
        if ($this->setData($dataPost))
        {
            if ($this->isValid())
            {
                $data = $this->getData($dataPost);
                $data = $this->dataFilterPost($data);
                $data = $this->postEventFormFilter($data);
                //var_dump($data);
                return $data;

            } else{

                //var_dump($dataPost);
                return  array(
                    'error'=>$this->subForm->getMessages(),
                    'filter'=>$this->subForm->filter->getMessages()
                );
            }
        }
        return false;
    }

    public function postEventFormFilter($data)
    {
        //print_r($this->events()->getIdentifiers());
        foreach($data as $key => $d){
            $da[$key] = $this->events()->trigger(__FUNCTION__.'.'.$key, $this,array($key => $d))->last();
            if($da[$key] != ''){
                $data[$key] = $da[$key];
            }
        }
        return $data;
    }

    public function dataFilterPost($dataPost)
    {

        foreach($this->optionsForm as $options)
        {
            if(isset($dataPost[$options['fieldset']['name']]))
            {
                if(isset($options['serialized']) and $options['serialized']){

                    $dataPost[$options['fieldset']['name']] = serialize($dataPost[$options['fieldset']['name']]);

                }elseif(isset($options['explode']) and $options['explode']){

                    $dataPost[$options['fieldset']['name']] = implode(', ',$dataPost[$options['fieldset']['name']][$options['fieldset']['name']]);

                }else{

                    foreach($dataPost[$options['fieldset']['name']] as $name => $value){
                        $dataPost[$name] = $value;
                    }
                    unset($dataPost[$options['fieldset']['name']]);
                }
            }
        }

        $Key = $this->getOptions()->getTableKeyFields();

        if($Key['key_name']){
            if($dataPost[$Key['key_name']] == ''){
                $dataPost[$Key['key_name']] = $dataPost[$Key['key_title']];
            }
        }

        if(isset($Key['key_title']) and $Key['key_title'] and isset($dataPost[$Key['key_title']])){
            $dataPost[$Key['key_title']] = stripslashes($dataPost[$Key['key_title']]);
        }

        if($Key['key_name']){
            $dataPost[$Key['key_name']] = Util::urlFilter($dataPost[$Key['key_name']]);
        }

        return  $dataPost;
    }

    public function setOptionsForm($optionsForm)
    {
        $this->options    = $optionsForm;
        $this->optionsForm = $optionsForm->getOptionsForm();
    }
    public function getOptions()
    {
        return $this->options;
    }
    public function getOptionsForm()
    {
        return $this->optionsForm;
    }
    public function events()
    {
        if (!$this->events) {
            $this->events = new EventManager(__CLASS__);
        }
        return $this->events;
    }

}
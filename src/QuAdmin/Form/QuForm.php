<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Form;

use QuAdmin\Util;

use Zend\Crypt\Password\Bcrypt;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

class QuForm extends Form
{
    protected $optionsForm;
    protected $options;
    protected $subForm;

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
        if($forms['serialized']){
            if(isset($data[$forms['fieldset']['name']])){
                $data = unserialize($data[$forms['fieldset']['name']]);
            }
        }

        $forms['fieldset']['name'] = $forms['fieldset']['name'].'-';
        $this->setBaseFieldset($this->add($forms['fieldset']));

        $this->add($subForm = new Form(),array('name'=>$fieldName));
        $subForm->setInputFilter( $subFilter  = new InputFilter() );

        unset($forms['serialized']);
        unset($forms['fieldset']);

        if(isset($forms['database']))
        {
            $modelOptions = $sl->get($forms['database']['model']);
            $formDatabase = $model->options($forms['database'], $modelOptions);
            foreach($forms as $form){}

            foreach($formDatabase as $name => $label)
            {
                $form['form']['name']  = $name;
                $form['form']['options']['label'] = $label;
                $subForm->add($form['form']);
                $subFilter->add($factory->createInput($form['filter']));
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
                $data = $this->prosesPassword($data);

                return  $data;

            } else{

                return  array(
                    'error'=>$this->subForm->getMessages(),
                    'filter'=>$this->subForm->filter->getMessages()
                );
            }
        }
        return false;
    }


    public function prosesPassword($data)
    {
        if(isset($data['password'])){
            $bCrypt = new Bcrypt;
            $bCrypt->setCost(8);
            $data['password'] = $bCrypt->create($data['password']);
        }
        return $data;
    }

    public function dataFilterPost($dataPost)
    {

        foreach($this->optionsForm as $options)
        {
            if(isset($dataPost[$options['fieldset']['name']]))
            {
                if($options['serialized']){

                    $dataPost[$options['fieldset']['name']] = serialize($dataPost[$options['fieldset']['name']]);

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

        if($Key['key_title']){
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
}
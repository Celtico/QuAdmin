<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class QuFilter implements InputFilterAwareInterface
{
    public $id;
    public $id_parent;
    public $id_author;
    public $id_lang;
    public $lang;
    public $date;
    public $modified;
    public $order;
    public $status;
    public $name;
    public $title;
    public $resum;
    public $text;
    public $type;
    public $imatge;
    public $parametres;
    protected $inputFilter;

    /**
     * @param $data
     */
    public function exchangeArray($data)
    {

        $this->id           = (isset($data['id'])) ? $data['id'] : null;
        $this->id_parent     = (isset($data['id_parent'])) ? $data['id_parent'] : null;
        $this->id_author     = (isset($data['id_author'])) ? $data['id_author'] : null;
        $this->id_lang     = (isset($data['id_lang'])) ? $data['id_lang'] : null;
        $this->lang     = (isset($data['lang'])) ? $data['lang'] : null;
        $this->date     = (isset($data['date'])) ? $data['date'] : null;
        $this->modified     = (isset($data['modified'])) ? $data['modified'] : null;
        $this->order     = (isset($data['order'])) ? $data['order'] : null;
        $this->status     = (isset($data['status'])) ? $data['status'] : null;
        $this->name     = (isset($data['name'])) ? $data['name'] : null;
        $this->title     = (isset($data['title'])) ? $data['title'] : null;
        $this->resum      = (isset($data['resum'])) ? $data['resum'] : null;
        $this->text     = (isset($data['text'])) ? $data['text'] : null;
        $this->type     = (isset($data['type'])) ? $data['type'] : null;
        $this->imatge        = (isset($data['imatge'])) ? $data['imatge'] : null;
        $this->parametres     = (isset($data['parametres'])) ? $data['parametres'] : null;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     *
     * @return void|\Zend\InputFilter\InputFilterAwareInterface
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            //Int
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_parent',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_author',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_lang',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'lang',
                'required' => false,

            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'order',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            //Data
            $inputFilter->add($factory->createInput(array(
                'name'     => 'date',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'modified',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));
            //text
            $inputFilter->add($factory->createInput(array(
                'name'     => 'status',
                'required' => false,

            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 200,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'lang',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 3,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'resum',
                'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'text',
                'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'type',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'imatge',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'parametres',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));



            $this->inputFilter = $inputFilter;        
        }

        return $this->inputFilter;
    }
}

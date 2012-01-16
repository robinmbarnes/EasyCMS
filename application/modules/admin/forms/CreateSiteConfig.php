<?php
class Admin_Form_CreateTemplate extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'create-template-form');
        $this->setAttrib('accept-charset', 'UTF-8');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $name = new Zend_Form_Element_Text('name');
        $name
            ->setLabel('Name')
            ->setDescription('The name of the website')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new Zend_Validate_Alnum(true),
                )
            )
        ;

        $url = new Zend_Form_Element_Text('url');
        $name
            ->setLabel('url')
            ->setAttrib('placeholder', 'http://www.yourwebsite.com'),
            ->setDescription('The url (web address) of the website')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                )
            )
        ;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setValue('Save site configuration')
            ->setLabel('');

        $this->addElements(array($name, $url, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
    }
}

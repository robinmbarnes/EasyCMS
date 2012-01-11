<?php
class Admin_Form_CreateFolder extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'create-folder-form');
        $this->setAttrib('accept-charset', 'UTF-8');
        
        $name = new Zend_Form_Element_Text('name');
        $name
            ->setLabel('Name')
            ->setAttrib('placeholder', 'New Folder')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new EasyCMS_Validate_Filepathname(),
                )
            )
        ;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setValue('Create folder')
            ->setLabel('');

        $this->addElements(array($name, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
    }
}

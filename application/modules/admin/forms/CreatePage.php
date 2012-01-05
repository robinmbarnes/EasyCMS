<?php
class Admin_Form_CreatePage extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'create-folder-form');
        $this->setAttrib('accept-charset', 'UTF-8');
        
        $name = new Zend_Form_Element_Text('name');
        $name
            ->setAttrib('placeholder', 'Folder name')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new Zend_Validate_Alnum(),
                )
            )
        ;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Create folder');

        /*$this->addElementPrefixPath('Std_Decorator','Std/Decorator/','decorator');
        $name->setDecorators(array('Composite'));*/

        $this->addElement($name);
        $this->addElement($submit);
    }
}

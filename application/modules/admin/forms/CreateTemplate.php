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
            ->setAttrib('placeholder', 'New Template')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new Zend_Validate_Alnum(),
                )
            )
        ;

        $description = new Zend_Form_Element_Textarea('description');
        $description
            ->setLabel('Description')
            ->setAttrib('placeholder', 'Enter a description...')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1)),
                )
            )
        ;

		$content = new Zend_Form_Element_File('content');
		$content
            ->setLabel('Template file')
		    ->setRequired(true)
            ->setDisableLoadDefaultDecorators(true)
        ;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setValue('Create template')
            ->setLabel('');

        $this->addElements(array($name, $description, $content, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
        $content->addDecorators(array('File'));
    }
}

<?php
class Admin_Form_CreatePage extends Zend_Form
{

    private $templates = array();

    public function __construct(array $templates)
    {
        $this->templates = $templates;
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'create-folder-form');
        $this->setAttrib('accept-charset', 'UTF-8');
        
        $name = new Zend_Form_Element_Text('name');
        $name
            ->setAttrib('placeholder', 'New webpage')
            ->setLabel('Webpage name')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new Zend_Validate_Alnum(),
                )
            )
        ;

        $template = new Zend_Form_Element_Select('template');
        $template
            ->setLabel('Select template')
            ->setRequired(true)
            ->addMultiOption('', 'Please select a template')
        ;

        foreach($this->templates as $t)
        {
            $template->addMultiOption($t->getId(), $t->getName());    
        }

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setLabel('')
            ->setValue('Create webpage')
        ;

        $this->addElements(array($name, $template, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
    }
}

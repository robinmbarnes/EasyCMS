<?php
class Admin_Form_CreateFile extends Zend_Form
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
            ->setAttrib('placeholder', 'New File')
            ->setDescription('The name of the file not including the extension')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new EasyCMS_Validate_Filepathname(),
                )
            )
        ;

		$file = new Zend_Form_Element_File('file');
		$file
            ->setLabel('Media file')
		    ->setRequired(true)
            ->setDisableLoadDefaultDecorators(true)
            ->SetDescription('The media file you wish to upload (e.g. image, video, ect)')
        ;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setValue('Create media file')
            ->setLabel('');

        $this->addElements(array($name, $file, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
        $file->addDecorators(array('File', array('ViewScript', array('viewScript' => 'FormElementFile.phtml', 'placement' => false))));
    }
}

<?php
class Admin_Form_EditUser extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'edit-user-form');
        $this->setAttrib('accept-charset', 'UTF-8');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $email = new Zend_Form_Element_Text('email');
        $email
            ->setLabel('Name')
            ->setAttrib('placeholder', 'user@domain.com')
            ->setDescription('This will also be the user\'s username')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new Zend_Validate_EmailAddress(),
                )
            )
        ;

		$current_password = new Zend_Form_Element_Password('current_password');
		$current_password
            ->setLabel('Current password')
		    ->setRequired(true)
        ;

		$new_password = new Zend_Form_Element_Password('new_password');
		$new_password
            ->setLabel('New password')
		    ->setRequired(true)
            ->SetDescription('Must be 5 - 10 characters long')
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 5, 'max' => 255)),
                )
            )
        ;

		$new_password_confirm = new Zend_Form_Element_Password('new_password_confirm');
		$new_password_confirm
            ->setLabel('Re enter password')
		    ->setRequired(true)
        ;


        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setValue('Create user')
            ->setLabel('')
        ;

        $this->addElements(array($email, $current_password, $new_password, $new_password_confirm, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
    }
}

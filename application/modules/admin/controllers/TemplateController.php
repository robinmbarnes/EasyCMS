<?php

class Admin_TemplateController extends EasyCMS_Controller_Action
{
    public function viewallAction()
    {
        $this->view->page_heading = 'View All Templates';
        $this->view->templates = $this->getDb()->getRepository('App_Model_Template')->findAll();
    }

    public function createAction()
    {
        $this->view->page_heading = 'Create New Template';
        $form = new Admin_Form_CreateTemplate();
        $this->view->form = $form;

        if(!$this->getRequest()->isPost())
        {
            return;
        }
        if($form->isValid($this->getRequest()->getPost()))
        {
            $template = new App_Model_Template();
            $template->setName($form->name->getValue());
            $template->setDescription($form->description->getValue());
            if(!$form->content->receive())
            {
                $form->content->addError('There was an error processing the file');
                return;
            }
            $template->setContent(file_get_contents($form->content->getFileName()));
            unlink($form->content->getFileName());
            try
            {
                $this->getDb()->persist($template);
                $this->getDb()->flush();
            }
            catch(PDOException $e)
            {
                $dbException = new App_Model_DBExceptionDecorator($e);
                if($dbException->isDuplicateKeyViolation())
                {
                    $form->name->addError('A template with this name already exists');
                    return;
                }
            }
            $this->addFlashMessageSuccess('Your new template has been created successfully');   
            $this->_redirect($this->getUrl(array(), 'admin_view_templates'));
        }
    }
}

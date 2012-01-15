<?php

class Admin_PageController extends EasyCMS_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function createAction()
    {
        $folder = $this->getDb()->getRepository('App_Model_Folder')->find($this->getRequest()->getParam('folder_id'));        
        if(!$folder)
        {
            $this->addFlashMessageError('Folder not found'); 
            $this->_redirect($this->getUrl(array(), 'admin_index'));
        }

        $templates = $this->getDb()->getRepository('App_Model_Template')->findAll();
        if(!$templates)
        {
            $this->addFlashError('There are currently no templates available. You must create at least one template before creating a webpage');
            $this->_redirect($this->getUrl(array('folder_id'=>$folder->getId()), 'admin_create_template'));
        }
        $this->view->templates = $templates;
        $this->view->page_heading = 'Create New Webpage';
        $form = new Admin_Form_CreatePage($templates);
        $this->view->form = $form;

        if(!$this->getRequest()->isPost())
        {
            return;
        }
        if($form->isValid($this->getRequest()->getPost()))
        {
            $template = $this->getDb()->getRepository('App_Model_Template')->find($form->template->getValue());
            if(!$template)
            {
                $this->form->template->addError('You must select a template');
            }
            $page = new App_Model_Page();
            $page->setName($form->name->getValue());
            $page->setFolder($folder);
            $page->setTemplate($template);
            $template_parser = new App_Model_TemplateParser($template);
            $page->setSections($template_parser->generateSections());
            try
            {
                $this->getDb()->persist($page);
                $this->getDb()->flush();
                $this->addFlashMessageSuccess('Your new webpage has been created successfully');   
                $this->_redirect($this->getUrl(array('folder_id'=>$folder->getId()), 'admin_view_folder'));               
            }
            catch(PDOException $e)
            {
                $dbException = new App_Model_DBExceptionDecorator($e);
                if($dbException->isDuplicateKeyViolation())
                {
                    $form->name->addError('A webpage with this name already exists in this folder');
                }
                else
                {
                    throw $e;
                }
            }
        }
    }
}

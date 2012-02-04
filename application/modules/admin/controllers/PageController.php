<?php

class Admin_PageController extends EasyCMS_Controller_Action
{

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

    public function deleteAction()
    {
        $page = $this->getDb()->getRepository('App_Model_Page')->find($this->getRequest()->getParam('page_id'));
        if(!$page)
        {
            $this->getResponse()->setRawHeader('HTTP/1.0 500 Internal Server Error');
            $this->_helper->json(array('error'=>true));
        }
        foreach($page->getSections() as $section)
        {
            $this->getDb()->remove($section);
        }
        $this->getDb()->remove($page);
        $this->getDb()->flush();     
        $this->_helper->json(array('success'=>true));
     }

    public function editAction()
    {
        $page = $this->getDb()->getRepository('App_Model_Page')->find($this->getRequest()->getParam('page_id'));
        if(!$page)
        {
            $this->addFlashMessageError('Page does not exist');
            $this->_redirect($this->getUrl(array(), 'admin_view_folder'));
        }
        $this->_helper->layout->disableLayout();
        $this->view->page = $page;
    }

    public function renderforeditAction()
    {
        //Will be viewed from within iFrame on edit page
        $this->_helper->layout->disableLayout();
        $page = $this->getDb()->getRepository('App_Model_Page')->find($this->getRequest()->getParam('page_id'));
        if(!$page)
        {
            die();
        }
        echo $page->renderForEdit();
        die();
    }

    public function savesectionsAction()
    {
        $page = $this->getDb()->getRepository('App_Model_Page')->find($this->getRequest()->getParam('page_id'));
        if(!$page)
        {
            $this->getResponse()->setRawHeader('HTTP/1.0 500 Internal Server Error');
            $this->_helper->json(array('error'=>true));
        }
        $sections = $this->getRequest()->getParam('sections');
        if(!is_array($sections))
        {
            $this->getResponse()->setRawHeader('HTTP/1.0 500 Internal Server Error');
            $this->_helper->json(array('error'=>true));
        }               
        foreach($sections as $section_name=>$section_content)
        {
            if($section = $page->getSectionByName($sectionName))
            {
                if($section_content != $section->getPlaceholder())
                {
                    $section->setContent($section_content);
                    $this->getDb()->persist($section);
                }
            }
        }
        $this->getDb()->flush();
        $this->_helper->json(array('success'=>true));
    }
}

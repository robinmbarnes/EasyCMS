<?php

class Admin_SetupController extends EasyCMS_Controller_Action
{
    public function siteconfigAction()
    {
        //There can only be one site config
        try
        {
            $this->getSiteConfig();
            $this->addFlashMessageError('Site configuration already set');   
            $this->_redirect($this->getUrl(array(), 'admin_index'));            
        }
        catch(App_Model_SiteConfigException $e)
        {
        }
        $this->view->page_heading = 'Setup - Site Configuration';
        $form = new Admin_Form_CreateSiteConfig();
        $this->view->form = $form;

        if(!$this->getRequest()->isPost())
        {
            return;
        }
        if($form->isValid($this->getRequest()->getPost()))
        {
            $config = new App_Model_SiteConfig();
            $config->setName($form->name->getValue());
            $config->setUrl($form->url->getValue());
            $this->getDb()->persist($config);
            $this->getDb()->flush();
            $this->addFlashMessageSuccess('Site configuration has been saved successfully');   
            $this->_redirect($this->getUrl(array(), 'admin_index'));               
        }
    }
}

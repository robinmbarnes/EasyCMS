<?php

abstract class EasyCMS_Controller_Action extends Zend_Controller_Action
{

    private static $site_config = null; 
    protected $session = null;   

    public final function init()
    {
        //If we can't load the site config, we presume that this is first run,
        //and launch setup
        try
        {
            $this->getSiteConfig();
        }
        catch(App_Model_SiteConfigException $e)
        {
            
        }
        $this->session = new Zend_Session_Namespace('easycmssession');
        //Require login
        if($this->getRequest()->getModuleName() == 'admin' 
            && $this->getRequest()->getActionName() != 'login'
            && !$this->getUser()
        )
        {
            $this->_redirect($this->getUrl(array(), 'admin_login_user'));
        }
        $this->view->user = $this->getUser();
        $this->preRun();
    }

    protected function preRun()
    {
    }

    protected function getDb()
    {
        return Zend_Registry::get('**application_db_connection**');
    }

    public function getSiteConfig()
    {
        if(!$site_config)
        {
            $list = $this->getDb()->getRepository('App_Model_SiteConfig')->findAll();
            $site_config = reset($list);
            if(!$site_config)
            {
                throw new App_Model_SiteConfigException();
            }
            $this->site_config = $site_config;
        }
        return $this->site_config;
    }

    public function getUser()
    {
        if($this->session->user)
        {
            return $this->session->user;
        }
        return null;
    }

    public function setUser(App_Model_User $user=null)
    {
        if($user == null)
        {
            Zend_Session::destroy();//Logout
        }
        else
        {
            $this->session->user = $user;
        }
        
    }

    protected function getUrl(array $params, $route_name)
    {
        return $this->getFrontController()->getRouter()->assemble($params, $route_name);
    }

    protected function addFlashMessageError($msg)
    {
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage(new EasyCMS_Util_FlashMessage($msg, EasyCMS_Util_FlashMessage::TYPE_ERROR)); 
    }

    protected function addFlashMessageSuccess($msg)
    {
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage(new EasyCMS_Util_FlashMessage($msg, EasyCMS_Util_FlashMessage::TYPE_SUCCESS)); 
    }

    protected function addFlashMessageNotice($msg)
    {
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage(new EasyCMS_Util_FlashMessage($msg, EasyCMS_Util_FlashMessage::TYPE_NOTICE)); 
    }

    protected function addFlashMessageGreeting($msg)
    {
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage(new EasyCMS_Util_FlashMessage($msg, EasyCMS_Util_FlashMessage::TYPE_GREETING)); 
    }
}

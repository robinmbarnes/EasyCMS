<?php

class EasyCMS_Controller_Action extends Zend_Controller_Action
{
    protected function getDb()
    {
        return Zend_Registry::get('**application_db_connection**');
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

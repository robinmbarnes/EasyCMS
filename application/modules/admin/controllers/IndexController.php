<?php

class Admin_IndexController extends EasyCMS_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()//AKA Dashboard
    {
        $folder = $this->getDb()->find('App_Model_Folder', 14);;
    }

    public function viewFolderAction()//List contents (files and folders) of a folder
    {
        if(!$folder_id = $this->getRequest()->getParam('folder_id'))
        {
            $folder_id = null;
        }
        $folder = $this->getDb()->getRepository('App_Model_Folder')->findOneBy(array('parent_id' => $folder_id));
        
        $this->view->folder = $folder;
    }

}


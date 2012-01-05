<?php

class IndexController extends EasyCMS_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $folder = $this->getDb()->find('App_Model_Folder', 14);
    }


}


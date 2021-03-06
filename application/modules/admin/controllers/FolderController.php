<?php

class Admin_FolderController extends EasyCMS_Controller_Action
{

    public function viewAction()//List contents (files and folders) of a folder
    {
        $folder = $this->getFolderFromParams();
        if(!$folder)
        {
            return $this->folderNotFound();
        }
        $this->view->page_heading = 'Contents of ' . $folder->getName();
        $this->view->folder = $folder;
    }

    public function createAction() //Create a folder
    {
        $folder = $this->getFolderFromParams();
        if(!$folder)
        {
            return $this->folderNotFound();
        }
        $this->view->page_heading = 'Create A New Folder In ' . $folder->getName();
        $form = new Admin_Form_CreateFolder();
        $this->view->form = $form;
        if(!$this->getRequest()->isPost())
        {
            return;
        }
        if($form->isValid($this->getRequest()->getPost()))
        {
            $new_folder = new App_Model_Folder();
            $new_folder->setName($form->name->getValue());
            $new_folder->setParent($folder);
            try
            {
                $this->getDb()->persist($new_folder);
                $this->getDb()->flush();
            }
            catch(PDOException $e)
            {
                $dbException = new App_Model_DBExceptionDecorator($e);
                if($dbException->isDuplicateKeyViolation())
                {
                    $form->name->addError('A folder with this name already exists within the parent folder');
                    return;
                }
            }
            $this->addFlashMessageSuccess('Your new folder has been created successfully');   
            $this->_redirect($this->getUrl(array('folder_id'=>$folder->getId()), 'admin_view_folder'));
        }
    } 

    public function deleteAction()
    {
        $folder = $this->getFolderFromParams();
        if(!$folder)
        {
            return $this->folderNotFound();
        }
        if($folder->isRoot())
        {
            $this->addFlashMessageNotice('You cannot delete the root folder');
            $this->_redirect($this->getUrl(array('folder_id'=>$folder->getId()), 'admin_view_folder'));
        }
        $view->folder = $folder;
        if(!$this->getRequest()->isPost())
        {
            $this->view->page_heading = 'Delete folder ' . $folder->getName();
            $this->view->items = $folder->getTotalChildCount($this->getDb());
            return;
        }
        if($this->getRequest()->getParam('cancel'))
        {
            $this->_redirect($this->getUrl(array('folder_id'=>$folder->getParent()->getId()), 'admin_view_folder'));
            return;
        }
        if($this->getRequest()->getParam('confirm'))
        {
            $parent = $folder->getParent();
            $files_to_delete = $folder->getAllChildFilePaths(Zend_Registry::get('media_path'), $this->getDb());
            $this->getDb()->remove($folder);
            $this->getDb()->flush();
            foreach($files_to_delete as $path)
            {
                @unlink($path);
            }
            $this->addFlashMessageSuccess('Folder deleted');
            $this->_redirect($this->getUrl(array('folder_id'=>$parent->getId()), 'admin_view_folder'));
        }
    }   

    private function folderNotFound()
    {
        $this->addFlashMessageError('Folder not found'); 
        $this->_redirect($this->getUrl(array(), 'admin_index'));
        return;
    }

    private function getFolderFromParams()
    {
        if(!$folder_id = $this->getRequest()->getParam('folder_id'))
        {
            $folder_id = 1;
        }
        return $this->getDb()->getRepository('App_Model_Folder')->find($folder_id);
    }
}

<?php

class Admin_FileController extends EasyCMS_Controller_Action
{
    public function createAction()
    {
        $folder = $this->getDb()->getRepository('App_Model_Folder')->find($this->getRequest()->getParam('folder_id'));        
        if(!$folder)
        {
            $this->addFlashMessageError('Folder not found'); 
            $this->_redirect($this->getUrl(array(), 'admin_index'));
        }

        $this->view->page_heading = 'Create New Media File';
        $form = new Admin_Form_CreateFile();
        $this->view->form = $form;

        if(!$this->getRequest()->isPost())
        {
            return;
        }
        if($form->isValid($this->getRequest()->getPost()))
        {
            $file = new App_Model_File();
            $file->setName($form->name->getValue());
            $file->setFolder($folder);
            if(!$form->file->receive())
            {
                $form->file->addError('There was an error processing the file');
                return;
            }
            $path_parts = pathinfo($form->file->getFileName());
            $file->setExtension($path_parts['extension']);
            try
            {
                $this->getDb()->persist($file);
                $this->getDb()->flush();
                if(!@rename($form->file->getFileName(), $file->getFullPath(Zend_Registry::get('media_path'))))
                {
                    throw new App_Model_FileMoveException();
                }
                $this->addFlashMessageSuccess('Your new media has been created successfully');   
                $this->_redirect($this->getUrl(array('folder_id'=>$folder->getId()), 'admin_view_folder'));               
            }
            catch(PDOException $e)
            {
                $dbException = new App_Model_DBExceptionDecorator($e);
                if($dbException->isDuplicateKeyViolation())
                {
                    $form->name->addError('A file with this name already exists in this folder');
                }
                else
                {
                    throw $e;
                }
            }
            catch(App_Model_FileMoveException $e)
            {
                $this->getDb()->remove($file);
                $this->getDb()->flush();
                $form->file->addError('There was an error processing the file');
            }
        }
    }

    public function deleteAction()
    {
        $file = $this->getDb()->getRepository('App_Model_File')->find($this->getRequest()->getParam('file_id'));
        if(!$file)
        {
            $this->getResponse()->setRawHeader('HTTP/1.0 500 Internal Server Error');
            $this->_helper->json(array('error'=>true));
        }
        $this->getDb()->remove($file);
        $this->getDb()->flush();     
        $this->_helper->json(array('success'=>true));
     }
}

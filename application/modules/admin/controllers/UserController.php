<?php

class Admin_UserController extends EasyCMS_Controller_Action
{
    public function viewallAction()
    {
        $this->view->page_heading = 'Users';
        $users = $this->getDb()->getRepository('App_Model_User')->findAll();
        $this->view->users = $users;
    }

    public function createAction()
    {
        if(!$this->getUser()->getIsSuperAdmin())
        {
            $this->addFlashMessageNotice('Only super admins may create new users');
            $this->_redirect($this->getUrl(array(), 'admin_view_users'));
        }
        $this->view->page_heading = 'Create New User';
        $form = new Admin_Form_CreateUser();
        $this->view->form = $form;

        if(!$this->getRequest()->isPost())
        {
            return;
        }
        $is_form_valid = $form->isValid($this->getRequest()->getPost());
        if($form->password->getValue() != $form->password_confirm->getValue())
        {
            $form->password_confirm->addError('This does not match the other password given');
            $is_form_valid = false;
        }
        if($is_form_valid)
        {
            $user = new App_Model_User();
            $user->setEmail($form->email->getValue());
            $user->setPassword($form->password->getValue());
            $user->setIsSuperAdmin(false);
            try
            {
                $this->getDb()->persist($user);
                $this->getDb()->flush();
                $this->addFlashMessageSuccess('New user has been created successfully');   
                $this->_redirect($this->getUrl(array(), 'admin_view_users'));               
            }
            catch(PDOException $e)
            {
                $dbException = new App_Model_DBExceptionDecorator($e);
                if($dbException->isDuplicateKeyViolation())
                {
                    $form->email->addError('A user with that email address already exists');
                }
                else
                {
                    throw $e;
                }
            }
        }
    }

    public function editAction()
    {
        $this->view->page_heading = 'Edit User';
        $form = new Admin_Form_EditUser();
        $this->view->form = $form;

        $user = $this->getDb()->getRepository('App_Model_User')->find($this->getRequest()->getParam('user_id'));
        if(!$user)
        {
            $this->addFlashMessageError('User does not exist');
            $this->_redirect($this->getUrl(array(), 'admin_view_users'));
        }

        $form->email->setValue($user->getEmail());

        if(!$this->getRequest()->isPost())
        {
            return;
        }

        $is_form_valid = $form->isValid($this->getRequest()->getPost());
        if($form->new_password->getValue() != $form->new_password_confirm->getValue())
        {
            $form->new_password_confirm->addError('This does not match the other password given');
            $is_form_valid = false;
        }
        if(!$user->doesPasswordEqual($form->current_password->getValue()))
        {
            $form->current_password->addError('Incorrect password');
            $is_form_valid = false;
        }        

        if($is_form_valid)
        {;
            $user->setEmail($form->email->getValue());
            $user->setPassword($form->new_password->getValue());
            $user->setIsSuperAdmin(false);
            try
            {
                $this->getDb()->persist($user);
                $this->getDb()->flush();
                $this->addFlashMessageSuccess('User has been updated successfully');   
                $this->_redirect($this->getUrl(array(), 'admin_view_users'));               
            }
            catch(PDOException $e)
            {
                $dbException = new App_Model_DBExceptionDecorator($e);
                if($dbException->isDuplicateKeyViolation())
                {
                    $form->email->addError('A user with that email address already exists');
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
        $user = $this->getDb()->getRepository('App_Model_User')->find($this->getRequest()->getParam('user_id'));
        if(!$user || !$this->getUser()->getIsSuperAdmin())
        {
            $this->getResponse()->setRawHeader('HTTP/1.0 500 Internal Server Error');
            $this->_helper->json(array('error'=>true));
        }
        $this->getDb()->remove($user);
        $this->getDb()->flush();     
        $this->_helper->json(array('success'=>true));
     }

    public function loginAction()
    {
        $this->view->error = false;
        $this->view->username = '';
        if($this->getRequest()->isPost())
        {
            $user = $this->getDb()->getRepository('App_Model_User')->findOneBy(
                array('email' => $this->getRequest()->getParam('username'))
            );
            if($user && $user->doesPasswordEqual($this->getRequest()->getParam('password')))
            {
                $this->setUser($user);
                $this->_redirect($this->getUrl(array(), 'admin_index')); 
            }
            else
            {
                $this->view->username = $this->getRequest()->getParam('username');
                $this->view->error = true;
            }
        }  
        $this->_helper->layout->disableLayout();
    }

    public function logoutAction()
    {
        $this->setUser(null);
        $this->_redirect($this->getUrl(array(), 'admin_login_user'));
    }

}

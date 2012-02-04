<?php

class App_Model_Routes
{
    public static function addAdminRoutes(Zend_Controller_Router_Rewrite $router)
    {
        //Dashboard        
        $route = new Zend_Controller_Router_Route('/admin', array('module'=>'admin', 'controller' => 'index', 'action' => 'index'));
        $router->addRoute('admin_index', $route);        

        //Folder
        $route = new Zend_Controller_Router_Route('/admin/folder/:folder_id', array('module'=>'admin', 'controller' => 'folder', 'action' => 'view'));
        $router->addRoute('admin_view_folder', $route);
        $route = new Zend_Controller_Router_Route('/admin/folder/', array('module'=>'admin', 'controller' => 'folder', 'action' => 'view'));
        $router->addRoute('admin_view_root_folder', $route);
        $route = new Zend_Controller_Router_Route('/admin/folder/:folder_id/create', array('module'=>'admin', 'controller' => 'folder', 'action' => 'create'));
        $router->addRoute('admin_create_folder', $route);
        $route = new Zend_Controller_Router_Route('/admin/folder/:folder_id/delete', array('module'=>'admin', 'controller' => 'folder', 'action' => 'delete'));
        $router->addRoute('admin_delete_folder', $route);

        //Template
        $route = new Zend_Controller_Router_Route('/admin/template', array('module'=>'admin', 'controller' => 'template', 'action' => 'viewAll'));
        $router->addRoute('admin_view_templates', $route);        
        $route = new Zend_Controller_Router_Route('/admin/template/create', array('module'=>'admin', 'controller' => 'template', 'action' => 'create'));
        $router->addRoute('admin_create_template', $route); 
        $route = new Zend_Controller_Router_Route('/admin/template/:template_id/delete', array('module'=>'admin', 'controller' => 'template', 'action' => 'delete'));
        $router->addRoute('admin_delete_template', $route); 

        //File
        $route = new Zend_Controller_Router_Route('/admin/foler/:folder_id/file/create', array('module'=>'admin', 'controller' => 'file', 'action' => 'create'));
        $router->addRoute('admin_create_file', $route);                       
        $route = new Zend_Controller_Router_Route('/admin/file/:file_id/delete', array('module'=>'admin', 'controller' => 'file', 'action' => 'delete'));
        $router->addRoute('admin_delete_file', $route); 

        //Page
        $route = new Zend_Controller_Router_Route('/admin/folder/:folder_id/page/create', array('module'=>'admin', 'controller' => 'page', 'action' => 'create'));
        $router->addRoute('admin_create_page', $route);                                             
        $route = new Zend_Controller_Router_Route('/admin/page/:page_id/delete', array('module'=>'admin', 'controller' => 'page', 'action' => 'delete'));
        $router->addRoute('admin_delete_page', $route);                                             
        $route = new Zend_Controller_Router_Route('/admin/page/:page_id/edit', array('module'=>'admin', 'controller' => 'page', 'action' => 'edit'));
        $router->addRoute('admin_edit_page', $route);                                             
        $route = new Zend_Controller_Router_Route('/admin/page/:page_id/render-for-edit', array('module'=>'admin', 'controller' => 'page', 'action' => 'renderForEdit'));
        $router->addRoute('admin_render_for_edit_page', $route);                                             
        $route = new Zend_Controller_Router_Route('/admin/page/:page_id/save-sections', array('module'=>'admin', 'controller' => 'page', 'action' => 'saveSections'));
        $router->addRoute('admin_save_page_sections', $route);                                             

        //Setup
        $route = new Zend_Controller_Router_Route('/setup/site-config', array('module'=>'admin', 'controller' => 'setup', 'action' => 'siteConfig'));
        $router->addRoute('admin_setup_site_config', $route);        

        //User
        $route = new Zend_Controller_Router_Route('/admin/user', array('module'=>'admin', 'controller' => 'user', 'action' => 'viewAll'));
        $router->addRoute('admin_view_users', $route);                       
        $route = new Zend_Controller_Router_Route('/admin/user/create', array('module'=>'admin', 'controller' => 'user', 'action' => 'create'));
        $router->addRoute('admin_create_user', $route);                       
        $route = new Zend_Controller_Router_Route('/admin/user/edit/:user_id', array('module'=>'admin', 'controller' => 'user', 'action' => 'edit'));
        $router->addRoute('admin_edit_user', $route);                       
        $route = new Zend_Controller_Router_Route('/admin/user/:user_id/delete', array('module'=>'admin', 'controller' => 'user', 'action' => 'delete'));
        $router->addRoute('admin_delete_user', $route);      
        $route = new Zend_Controller_Router_Route('/admin/user/login', array('module'=>'admin', 'controller' => 'user', 'action' => 'login'));
        $router->addRoute('admin_login_user', $route);      
        $route = new Zend_Controller_Router_Route('/admin/user/logout', array('module'=>'admin', 'controller' => 'user', 'action' => 'logout'));
        $router->addRoute('admin_logout_user', $route);      
    }
}

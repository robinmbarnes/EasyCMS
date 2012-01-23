<?php

require APPLICATION_PATH . '/doctrine-orm/Doctrine/ORM/Tools/Setup.php';

use Doctrine\ORM\EntityManager, Doctrine\ORM\Configuration;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function run()
    {
        //Setup modules
        $front = $this->getResource('FrontController');
        $front->addModuleDirectory(APPLICATION_PATH . '/modules');

        //Autoload Library classes
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('EasyCMS_');

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
        'basePath'      => APPLICATION_PATH . '/modules/default',
        'namespace'     => 'App',
        'resourceTypes' => array(
            'form' => array(
                'path'      => 'forms/',
                'namespace' => 'Form',
            ),
            'model' => array(
                'path'      => 'models/',
                'namespace' => 'Model',
            ),
        ),
        ));

        $admin_resourceLoader = new Zend_Loader_Autoloader_Resource(array(
        'basePath'      => APPLICATION_PATH . '/modules/admin',
        'namespace'     => 'Admin',
        'resourceTypes' => array(
            'form' => array(
                'path'      => 'forms/',
                'namespace' => 'Form',
            ),
        ),
        ));

        //Load Doctrine
        $db_config = $this->getApplication()->getOption('database');
        $db_params = $db_config['params'];
        $this->loadDoctrine($db_params['database_name'],$db_params['host'],$db_params['username'],$db_params['password']);

        //Sort out view
        $this->initView();

        $this->setupRoutes($front);

        Zend_Registry::set('media_path', $this->getApplication()->getOption('media_path'));

        parent::run();
    }

    private function loadDoctrine($dbname, $host, $username, $password)
    {
        $lib = APPLICATION_PATH . "/doctrine-orm";
        Doctrine\ORM\Tools\Setup::registerAutoloadDirectory($lib);

        if (APPLICATION_ENV == "production") 
        {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        } 
        else
        {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        }

        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '/modules/default/models');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(APPLICATION_PATH . '/Proxies');
        $config->setProxyNamespace('EasyCMS\Proxies');

        if ($applicationMode == "production") 
        {
            $config->setAutoGenerateProxyClasses(false);
        } 
        else 
        {
            $config->setAutoGenerateProxyClasses(true);
        }

        $connectionOptions = array(
            'driver' => 'pdo_mysql',
            'dbname' => $dbname,
            'user' => $username,
            'password' => $password,
            'host' => $host,
        );

        $em = EntityManager::create($connectionOptions, $config);
        Zend_Registry::set('**application_db_connection**', $em);
    }

    private function setupRoutes(Zend_Controller_Front $controller)
    {
        $router = $controller->getRouter();
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
    }
    
    private function initView()
    {
        $view = new Zend_View();
        $view->addHelperPath(APPLICATION_PATH . '/../library/EasyCMS/View/Helper', 'EasyCMS_View_Helper');
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

    }
}


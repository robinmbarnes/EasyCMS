<?php

require APPLICATION_PATH . '/doctrine-orm/Doctrine/ORM/Tools/Setup.php';

use Doctrine\ORM\EntityManager, Doctrine\ORM\Configuration;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function run()
    {
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

        //Setup modules
        $front = $this->getResource('FrontController');
        $front->addModuleDirectory(APPLICATION_PATH . '/modules');

        $this->setupRoutes($front);

        //Load Doctrine
        $db_config = $this->getApplication()->getOption('database');
        $db_params = $db_config['params'];
        $this->loadDoctrine($db_params['database_name'],$db_params['host'],$db_params['username'],$db_params['password']);

        //Sort out view
        $this->initView();


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
        App_Model_Routes::AddAdminRoutes($router);
     }
    
    private function initView()
    {
        $view = new Zend_View();
        $view->addHelperPath(APPLICATION_PATH . '/../library/EasyCMS/View/Helper', 'EasyCMS_View_Helper');
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

    }
}


<?php

 /**
  * Services are globally registered in this file
  *
  * @var \Phalcon\Config $config
  */
 use Phalcon\Di\FactoryDefault;
 use Phalcon\Mvc\View;
 use Phalcon\Mvc\Url as UrlResolver;
 use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
 use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
 use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
 use Phalcon\Session\Adapter\Files as SessionAdapter;
 use Phalcon\Mvc\Dispatcher;
 use Phalcon\Flash\Session as FlashSession;
 use Phalcon\Events\Manager as EventsManager;

/**
  * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
  */
 $di = new FactoryDefault();


 $di->set('router', function() {
   require 'routes.php';
   return $router;
 })
 ;

 /**
  * We register the events manager
  */
 $di->set('dispatcher', function () use ($di) {
   $eventsManager = new EventsManager;
   /**
    * Check if the user is allowed to access certain action using the SecurityPlugin
    */
   $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);
   /**
    * Handle exceptions and not-found exceptions using NotFoundPlugin
    */
   $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);
   $dispatcher = new Dispatcher;
   $dispatcher->setEventsManager($eventsManager);
   return $dispatcher;
 });
 /**
  * The URL component is used to generate all kind of urls in the application
  */
 $di->set('url', function () use ($config) {
   $url = new UrlResolver();
   $url->setBaseUri($config->application->baseUri);
   return $url;
 }, true);

 /**
  * Setting up the view component
  */
 $di->setShared('view', function () use ($config) {
   $view = new View();
   $view->setViewsDir($config->application->viewsDir);
   $view->registerEngines(array(
       '.volt' => function ($view, $di) use ($config) {
         $volt = new VoltEngine($view, $di);
         $volt->setOptions(array(
	 'compiledPath' => $config->application->cacheDir,
	 'compiledSeparator' => '_'
         ));
         return $volt;
       },
	   '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
           ));
           return $view;
         });

         /**
          * Database connection is created based in the parameters defined in the configuration file
          */
         $di->set('db', function () use ($config) {
           return new DbAdapter($config->database->toArray());
         });

         /**
          * If the configuration specify the use of metadata adapter use it or use memory otherwise
          */
         $di->set('modelsMetadata', function () {
           return new MetaDataAdapter();
         });

         /**
          * Start the session the first time some component request the session service
          */
         $di->setShared('session', function () {
           $session = new SessionAdapter();
           $session->start();
           return $session;
         });


         /**
          * Register the flash service with custom CSS classes
          */
         $di->set('flash', function () {
           return new FlashSession(array(
	   'error' => 'alert alert-danger',
	   'success' => 'alert alert-success',
	   'notice' => 'alert alert-info',
           ));
         });


         
<?php

 use Phalcon\Mvc\Router;

$router = new Router();

 //Remove trailing slashes automatically
 $router->removeExtraSlashes(true);

 //main route
 $router->add("/", array(
     'controller' => 'index',
     'action' => 'index'
 ));


 //Get one element. Ex: /user/2
 $router->addGet('/:controller/:int', array(
     'controller' => 1,
     'action' => "get",
     'id' => 2,
 ));

 //Get all elements. Ex: /user
 $router->addGet('/:controller', array(
     'controller' => 1,
     'action' => "list"
 ));

 //Create a new element. Ex: /user
 $router->addPost('/:controller', array(
     'controller' => 1,
     'action' => "create"
 ));

 //Update a new element. Ex: /user
 $router->addPut('/:controller/:int', array(
     'controller' => 1,
     'action' => "update",
     'id' => 2
 ));

 //DELETE a new element. Ex: /user
 $router->addDelete('/:controller/:int', array(
     'controller' => 'user',
     'action' => "delete",
     'id' => 2
 ));



 
 

 $router->add('/:controller/index', array(
     'controller' => 1,
     'action' => "index"
 ));


 // CRUD and Session
 $router->add('/:controller/signup', array(
     'controller' => 1,
     'action' => "signup"
 ));

 $router->add('/:controller/login', array(
     'controller' => 1,
     'action' => "login"
 ));

 $router->add('/:controller/logout', array(
     'controller' => 1,
     'action' => "logout"
 ));

 
 //CRUD 
 $router->add('/:controller/search/:int', array(
     'controller' => 1,
     'action' => "search"
 ));

 
 $router->add('/:controller/edit/:int', array(
     'controller' => 1,
     'action' => "edit",
     'id'=>'2'
 ));
 
 

//not founded route
 $router->notFound(array(
     'controller' => 'error',
     'action' => 'page404'
 ));



 $router->setDefaults(array(
     'controller' => 'index',
     'action' => 'index'
 ));

 return $router;
 
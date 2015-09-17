<?php

 use Phalcon\Mvc\Controller;
 use Phalcon\Http\Request;
 use Phalcon\Http\Response;
 use Phalcon\DI;
 use Phalcon\Mvc\Dispatcher;

 class ControllerBase extends Controller {

   protected $modelName;
   protected $controllerName;
   protected $response;
   protected $request;
   protected $id;

   public function initialize() {
     $this->controllerName = $this->dispatcher->getControllerName(); //controller
     $this->modelName = $this->controllerName; //model
     $this->response = new Response();
     $this->request = new Request();
     $this->id = $this->dispatcher->getParam("id");

     $this->assets
	 ->addCss('css/my.css');

     $this->assets
	 ->addJs('js/rest-calls.js');
   }

 }
 
<?php

 use Phalcon\Mvc\Model\Criteria;
 use Phalcon\Paginator\Adapter\Model as Paginator;

 class UserController extends ControllerBase {

   public function initialize() {
     parent::initialize();
   }

   public function indexAction() {
     $this->persistent->parameters = null;
     $this->view->setTemplateBefore('private');
     $user = User::find();
     if (count($user) == 0) {
       $this->flash->notice("The search did not find any user");
       return $this->dispatcher->forward(array(
	       "controller" => "user",
	       "action" => "index"
       ));
     }
     $paginator = new Paginator(array(
         "data" => $user,
         "limit" => 20,
         "page" => $numberPage
     ));
     $this->view->page = $paginator->getPaginate();
     $auth = $this->session->get('auth');
     if ($auth[access_group_id] == 1) {
       $this->flash->success('Access Group = ' . $auth[access_group_id] . ' - READ ONLY');
     } else if ($auth[access_group_id] == 2) {
       $this->flash->success('Access Group = ' . $auth[access_group_id] . ' - ALLOW ALL');
     }
   }

   // Method Http accept: GET returns JSON data
   // curl -i -X GET http://localhost/exam/public/user
   public function listAction() {
     $this->view->disable();
     $modelName = $this->modelName;
     foreach ($modelName::find() as $user) {
       $data[] = array(
           'id' => $user->id,
           'username' => $user->username,
           'email' => $user->email,
           'password' => $user->password,
           'access_group_id' => $user->access_group_id,
       );
     }
     $this->response->setContentType('application/json', 'UTF-8');
     $this->response->setJsonContent($data);
     return $this->response;
   }

   //Method Http accept: GET BY id - working
   // curl -i -X GET http://localhost/exam/public/user/1
   public function getAction() {
     $user = User::findFirst($this->id);
     // Create a response
     if ($user === false) {
       $this->response->setJsonContent(array('status' => 'NOT-FOUND', 'id' => $this->id));
     } else {
       $this->response->setContentType('application/json', 'UTF-8');
       $this->response->setJsonContent(
	   array(
	       'status' => 'FOUND',
	       'data' => array(
	           'id' => $user->id,
	           'username' => $user->username,
	           'email' => $user->email,
	           'password' => $user->password,
	           'access_group_id' => $user->access_group_id,
	       )
	   )
       );
     }
     return $this->response;
   }

   // Method Http accept: POST (insert) - working
   public function createAction() {
     if ($this->request->isPost()) {
       if ($this->request->isAjax()) {
         $user = $this->request->getJsonRawBody();
         $phql = "INSERT INTO User (username,email,password,access_group_id) VALUES (:username:, :email:, :password:,:access_group_id:)";
         $status = $this->modelsManager->executeQuery($phql, array(
	 'username' => $user->username,
	 'email' => $user->email,
	 'password' => sha1($user->password),
	 'access_group_id' => 1
         ));
         // Check if the insertion was successful
         if ($status->success() == true) {
           // Change the HTTP status
           $this->response->setStatusCode(201, "Created");
           $user->id = $status->getModel()->id;
           $this->response->setJsonContent(
	       array(
	           'status' => 'OK',
	       )
           );
         } else {
           // Change the HTTP status
           $this->response->setStatusCode(409, "Conflict");

           $this->response->setJsonContent(
	       array(
	           'status' => 'ERROR',
	       )
           );
         }
       }
     }
     $this->response;
   }

   // Method Http accept: PUT (update)  - working 
   public function updateAction() {
     $user = $this->request->getJsonRawBody();
     $phql = "UPDATE User SET username = :username:, email = :email:, password = :password: WHERE id = :id:";
     $status = $this->modelsManager->executeQuery($phql, array(
         'id' => $this->id,
         'username' => $user->username,
         'email' => $user->email,
         'password' => sha1($user->password) // encrypting 
     ));
     // Check if the insertion was successful
     if ($status->success() == true) {
       $this->response->setJsonContent(
	   array(
	       'status' => 'OK', 'message' => 'User updated !!'
	   )
       );
     } else {
       // Change the HTTP status
       $this->response->setStatusCode(409, "Conflict");
       $errors = array();
       foreach ($status->getMessages() as $message) {
         $errors[] = $message->getMessage();
       }
       $this->response->setJsonContent(
	   array(
	       'status' => 'ERROR',
	       'messages' => $errors
	   )
       );
     }
     return $this->response;
   }

   // Method Http accept: DELETE  - working 
   public function deleteAction() {
     $user = User::findFirst($this->id);
     if ($user != false) {
       if ($user->delete() == true) {
         $this->response->setJsonContent(array('status' => "OK", 'message' => 'deleted'));
       } else {
         $this->response->setStatusCode(409, "Conflict");
         $errors = array();
         foreach ($model->getMessages() as $message) {
           $errors[] = $message->getMessage();
         }
         $this->response->setJsonContent(array('status' => "ERROR", 'messages' => $errors));
       }
     } else {
       $this->response->setStatusCode(409, "Conflict");
       $this->response->setJsonContent(array('status' => "ERROR", 'messages' => array("this element doesnt not exist")));
     }
     return $this->response;
   }

   //generated for view
   public function editAction($id) {
     $this->view->setTemplateBefore('private');
     if (!$this->request->isPost()) {
       $user = User::findFirst($id);
       if (!$user) {
         $this->flash->error("user was not found");
         return $this->dispatcher->forward(array(
	         "controller" => "user",
	         "action" => "index"
         ));
       }
       $this->view->id = $user->id;
       $this->tag->setDefault("id", $user->id);
       $this->tag->setDefault("username", $user->username);
       $this->tag->setDefault("password", sha1($user->password));
       $this->tag->setDefault("email", $user->email);
       $this->tag->setDefault("access_group_id", $user->access_group_id);
     }
   }

   //generated for view
   public function searchAction() {
     $this->view->setTemplateBefore('private');
     $numberPage = 1;
     if ($this->request->isPost()) {
       $query = Criteria::fromInput($this->di, "User", $_POST);
       $this->persistent->parameters = $query->getParams();
     } else {
       $numberPage = $this->request->getQuery("page", "int");
     }

     $parameters = $this->persistent->parameters;
     if (!is_array($parameters)) {
       $parameters = array();
     }
     $parameters["order"] = "id";

     $user = User::find($parameters);
     if (count($user) == 0) {
       $this->flash->notice("The search did not find any user");

       return $this->dispatcher->forward(array(
	       "controller" => "user",
	       "action" => "index"
       ));
     }

     $paginator = new Paginator(array(
         "data" => $user,
         "limit" => 20,
         "page" => $numberPage
     ));

     $this->view->page = $paginator->getPaginate();
   }

   

 }
 
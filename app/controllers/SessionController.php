<?php

 class SessionController extends ControllerBase {

   private function _registerSession($user) {
     $this->session->set('auth', array(
         'id' => $user->id,
         'email' => $user->email,
         'access_group_id' => $user->access_group_id
     ));
   }

   /**
    * This action authenticate and logs a user into the application
    *
    */
   public function initialize() {
     parent::initialize();
   }

   /**
    * Allow a user to signup to the system
    */
   public function signupAction() {
     $this->view->setTemplateBefore('public');

     if ($this->request->isPost()) {
       $user = new User();
       $pass = $this->request->getPost('password');
       $repeatPassword = $this->request->getPost('repeatPassword');
       if ($pass != $repeatPassword) {
         $this->flash->error('Passwords are diferent');
         return $this->response->redirect("index");
       }
       $enctrypted = sha1($pass);
       $user->assign(array(
           'username' => $this->request->getPost('username'),
           'password' => $enctrypted,
           'email' => $this->request->getPost('email'),
           'access_group_id' => 1
       ));
       $success = $user->save();
       if ($success) {
         $this->_registerSession($user);
         $this->flash->success('Thanks for registering!' . $user->username);
         return $this->response->redirect('user/index');
       } else {
         $this->flash->error('You Cant ');
         return $this->response->redirect('session/signup');
       }
     }
   }

   /**
    * This action authenticate and logs an user into the application
    *
    */
   public function loginAction() {
     $this->view->setTemplateBefore('public');
     if ($this->request->isPost()) {
       $email = $this->request->getPost('email');
       $password = $this->request->getPost('password');
       $user = User::findFirst(array(
	       "(email = :email: OR username = :email:) AND password = :password:",
	       'bind' => array('email' => $email, 'password' => sha1($password))
       ));
       if ($user != false) {
         $this->_registerSession($user);
         $this->flash->success('Welcome ' . $user->username );
         return $this->response->redirect('user/index');
       }
       
       $this->flash->error('Wrong email/password' . $user);
       return $this->response->redirect('session/login');
     }
   }

   /**
    * Closes the session
    */
   public function logoutAction() {
     //  $this->auth->remove();
     $this->session->destroy();
     return $this->response->redirect('index/index');
   }

 }
 
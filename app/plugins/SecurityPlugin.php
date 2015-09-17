<?php

 use Phalcon\Acl;
 use Phalcon\Acl\Role;
 use Phalcon\Acl\Resource;
 use Phalcon\Events\Event;
 use Phalcon\Mvc\User\Plugin;
 use Phalcon\Mvc\Dispatcher;
 use Phalcon\Acl\Adapter\Memory as AclList;

 /**
  * SecurityPlugin
  *
  * This is the security plugin which controls that users only have access to the modules they're assigned to
  */
 class SecurityPlugin extends Plugin {

   /**
    * Returns an existing or new access control list
    *
    * @returns AclList
    */
   public function getAcl() {

     //throw new \Exception("something");

     if (!isset($this->persistent->acl)) {

       $acl = new AclList();
       $acl->setDefaultAction(Acl::DENY);
       //Register roles
       $roles = array(
           'guests' => new Role('Guests'),
           'users' => new Role('Users'),
           'admins' => new Role('Admins')
       );
       foreach ($roles as $role) {
         $acl->addRole($role);
       }

       //Public area resources  - READ ONLY
       $publicResources = array(
           'index' => array('index'),
           'user' => array('list', 'get', 'details', 'search'),
           'errors' => array('show401', 'show404', 'show500'),
           'session' => array('signup', 'login', 'logout')
       );
       foreach ($publicResources as $resource => $actions) {
         $acl->addResource(new Resource($resource), $actions);
       }

       //Grant access to public areas to both users and guests
       foreach ($roles as $role) {
         foreach ($publicResources as $resource => $actions) {
           foreach ($actions as $action) {
	 $acl->allow($role->getName(), $resource, $action);
           }
         }
       }

       //User area resources  -- READ ONLY
       $userResourses = array(
           'user' => array('index', 'search')
       );
       foreach ($userResourses as $resource => $actions) {
         $acl->addResource(new Resource($resource), $actions);
       }

       //Grant acess to private area to role Users
       foreach ($userResourses as $resource => $actions) {
         foreach ($actions as $action) {
           $acl->allow('Users', $resource, $action);
         }
       }


       //Admins Resourses    -- ALLOW ALLs
       $adminResourses = array(
           'user' => array('index', 'edit', 'delete', 'update', 'create', 'search', 'save', 'remove'),
       );
       foreach ($adminResourses as $resource => $actions) {
         $acl->addResource(new Resource($resource), $actions);
       }
       //Grant access to private area to role Admins
       foreach ($adminResourses as $resource => $actions) {
         foreach ($actions as $action) {
           $acl->allow('Admins', $resource, $action);
         }
       }
       //The acl is stored in session, APC would be useful here too
       $this->persistent->acl = $acl;
     }

     return $this->persistent->acl;
   }

   /**
    * This action is executed before execute any action in the application
    *
    * @param Event $event
    * @param Dispatcher $dispatcher
    */
   public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
     $auth = $this->session->get('auth');
     if (!$auth) {
       $role = 'Guests';
     } else if ($auth[access_group_id] == 2) {
       $role = 'Admins';
     } else {
       $role = 'Users';
     }
     $controller = $dispatcher->getControllerName();
     $action = $dispatcher->getActionName();
     $acl = $this->getAcl();

     $allowed = $acl->isAllowed($role, $controller, $action);
     if ($allowed != Acl::ALLOW) {
       if ($auth) {
         $this->flash->error('You have no permission for this');
         $dispatcher->forward(array('controller' => 'user', 'action' => 'index'
         ));
       } else {
         $dispatcher->forward(array('controller' => 'errors', 'action' => 'show401'));
         $this->session->destroy();
         return false;
       }
     }
   }

 }
 
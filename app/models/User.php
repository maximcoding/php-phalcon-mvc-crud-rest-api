<?php

 use Phalcon\Mvc\Model\Validator\Email as Email,
     Phalcon\Mvc\Model\Validator\Uniqueness as Uniq;

 class User extends \Phalcon\Mvc\Model {

   /**
    *
    * @var integer
    */
   protected $id;

   /**
    *
    * @var string
    */
   protected $username;

   /**
    *
    * @var string
    */
   protected $password;

   /**
    *
    * @var string
    */
   protected $email;


   /**
    *
    * @var integer
    */
   protected $access_group_id;

   /**
    * Method to set the value of field id
    *
    * @param integer $id
    * @return $this
    */
   public function setId($id) {
     $this->id = $id;

     return $this;
   }

   /**
    * Method to set the value of field username
    *
    * @param string $username
    * @return $this
    */
   public function setUsername($username) {
     $this->username = $username;

     return $this;
   }

   /**
    * Method to set the value of field password
    *
    * @param string $password
    * @return $this
    */
   public function setPassword($password) {
     $this->password = $password;

     return $this;
   }

   /**
    * Method to set the value of field email
    *
    * @param string $email
    * @return $this
    */
   public function setEmail($email) {
     $this->email = $email;

     return $this;
   }

 
   /**
    * Method to set the value of field access_group_id
    *
    * @param integer $access_group_id
    * @return $this
    */
   public function setAccessGroupId($access_group_id) {
     $this->access_group_id = $access_group_id;

     return $this;
   }

   /**
    * Returns the value of field id
    *
    * @return integer
    */
   public function getId() {
     return $this->id;
   }

   /**
    * Returns the value of field username
    *
    * @return string
    */
   public function getUsername() {
     return $this->username;
   }

   /**
    * Returns the value of field password
    *
    * @return string
    */
   public function getPassword() {
     return $this->password;
   }

   /**
    * Returns the value of field email
    *
    * @return string
    */
   public function getEmail() {
     return $this->email;
   }


   /**
    * Returns the value of field access_group_id
    *
    * @return integer
    */
   public function getAccessGroupId() {
     return $this->access_group_id;
   }

   /**
    * Validations and business logic
    *
    * @return boolean
    */
   public function validation() {
     $this->validate(
	 new Email(
	 array(
         'field' => 'email',
         'required' => true,
	 )
	 )
     );
     $this->validate(
	 new Uniq(
	 [
         'field' => 'username',
         'message' => 'Sorry, That username is already taken',
	 ]
	 )
     );
     if ($this->validationHasFailed() == true) {
       return false;
     }
     return true;
   }

   /**
    * Initialize method for model.
    */
   public function initialize() {
     $this->belongsTo('access_group_id', 'AccessGroup', 'id', array('alias' => 'AccessGroup'));
   }

   /**
    * Returns table name mapped in the model.
    *
    * @return string
    */
   public function getSource() {
     return 'user';
   }

   /**
    * Allows to query a set of records that match the specified conditions
    *
    * @param mixed $parameters
    * @return User[]
    */
   public static function find($parameters = null) {
     return parent::find($parameters);
   }

   /**
    * Allows to query the first record that match the specified conditions
    *
    * @param mixed $parameters
    * @return User
    */
   public static function findFirst($parameters = null) {
     return parent::findFirst($parameters);
   }

 }
 
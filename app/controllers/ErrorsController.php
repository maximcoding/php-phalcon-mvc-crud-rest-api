<?php

 class ErrorsController extends ControllerBase {

   public function initialize() {
     echo '<div class="alert-danger"> Oops !</div>';
     parent::initialize();
   }

   public function show404Action() {
     $this->view->setTemplateBefore('public');
   }

   public function show401Action() {
     $this->view->setTemplateBefore('public');
   }

   public function show500Action() {
          $this->view->setTemplateBefore('public');
   }

 }
 
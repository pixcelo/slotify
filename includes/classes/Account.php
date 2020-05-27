<?php
  class Account {

    public function __construct() {
      
    }

    public function register() {
        $this->validateUsername($username);
        $this->validateFirstname($firstName);
        $this->validateLastname($lastName);
        $this->validateEmails($email, $email2);
        $this->validatePasswords($password, $password2);
    }

    private function validateUsername($un) {
      
    }
    
    private function validateFirstname($fn) {
    
    }
    
    
    private function validateLastname($ln) {
    
    }
    
    
    private function validateEmails($em, $em2) {
    
    }
    
    private function validatePasswords($pw, $pw2) {
    
    }

  }
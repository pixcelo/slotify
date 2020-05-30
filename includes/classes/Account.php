<?php
  class Account {

    private $con;
    private $errorArray;

    public function __construct($con) {
      $this->con = $con;
      $this->errorArray = array();
    }

    // アカウントのバリデーション
    public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
        $this->validateUsername($un);
        $this->validateFirstname($fn);
        $this->validateLastname($ln);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if (empty($this->errorArray) == true) {
            // insert to DB
            return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
        } else {
            return false;
        }
    }

    public function getError($error) {
      if (!in_array($error, $this->errorArray)) {
          $error = "";
      }
      return "<span class='errorMessage'>$error</span>";
      // echo "test";
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw) {
        $encryptedPw = md5($pw);
        $profilePic = "assets/images/profile-pics/head_emerald.png";
        $date = date("Y-m-d");
        $result = mysqli_query($this->con, "INSERT INTO users VALUES (NULL, '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

        return $result;
    }

    private function validateUsername($un) {
      if (mb_strlen($un) > 25 || mb_strlen($un) < 5) {
          array_push($this->errorArray, Constants::$userNameCharacters);
          return;
      }

    }
    
    private function validateFirstname($fn) {
      if (mb_strlen($fn) > 25 || mb_strlen($fn) < 2) {
        array_push($this->errorArray, Constants::$firstNameCharacters);
        return;
    }
    
    }
    
    private function validateLastname($ln) {
      if (mb_strlen($ln) > 25 || mb_strlen($ln) < 2) {
        array_push($this->errorArray, Constants::$lastNameCharacters);
        return;
    }
    
    }

    private function validateEmails($em, $em2) {
      if ($em != $em2) {
          array_push($this->errorArray, Constants::$emailsDoNotMatch);
          return;
      }

      if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
          array_push($this->errorArray, Constants::$emailInvalid);
          return;
      }
    }
    
    private function validatePasswords($pw, $pw2) {
      if ($pw != $pw2) {
          array_push($this->errorArray, Constants::$passwordsDoNomatch);
          return;
      }

      // 正規表現チェック ^はnotの意味
      if (preg_match('/[^A-Za-z0-9]/', $pw)) {
          array_push($this->errorArray, Constants::$passwordsNotAlphanumeric);
          return;
      }

      if (strlen($pw) > 30 || strlen($pw) < 5) {
          array_push($this->errorArray, Constants::$passwordsCharacters);
          return;
      }
    }

  }
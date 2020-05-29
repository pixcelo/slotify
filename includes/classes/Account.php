<?php
  class Account {

    private $errorArray;

    public function __construct() {
      $this->errorArray = array();
    }

    // アカウントのバリデーション
    public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
        $this->validateUsername($un);
        $this->validateFirstname($fn);
        $this->validateLastname($ln);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if (empty($this->errorArray)) {
            // insert to DB
            return true;
        } else {
            return false;
        }
    }

    private function validateUsername($un) {
      if (mb_strlen($un) > 25 || mb_strlen($un) < 5) {
          array_push($this->errorArray, "ユーザーネームは5〜25文字の間で設定してください。");
          return;
      }

    }
    
    private function validateFirstname($fn) {
      if (mb_strlen($fn) > 25 || mb_strlen($fn) < 2) {
        array_push($this->errorArray, "名前は2〜5文字の間で設定してください。");
        return;
    }
    
    }
    
    private function validateLastname($ln) {
      if (mb_strlen($ln) > 25 || mb_strlen($ln) < 2) {
        array_push($this->errorArray, "名字は2〜5文字の間で設定してください。");
        return;
    }
    
    }

    private function validateEmails($em, $em2) {
      if ($em !== $em2) {
          array_push($this->errorArray, "メールアドレスが一致していません。");
          return;
      }

      if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
          array_push($this->errorArray, "メールアドレスの形式が間違っています。");
          return;
      }
    }
    
    private function validatePasswords($pw, $pw2) {
      if ($pw !== $pw2) {
          array_push($this->errorArray, "パスワードが一致していません。");
          return;
      }

      // 正規表現チェック ^はnotの意味
      if (preg_match('/[^A-Za-z0-9]/', $pw)) {
          array_push($this->errorArray, "パスワードは文字や数字で入力してください。");
          return;
      }

      if (strlen($pw) > 30 || strlen($pw) < 5) {
          array_push($this->errorArray, "パスワードは5〜30文字の間で設定してください。");
          return;
      }
    }

  }
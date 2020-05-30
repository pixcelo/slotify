<?php

function sanitizeFormString($inputText) {
  $inputText = strip_tags($inputText);
  $inputText = str_replace(" ", "", $inputText);
  return $inputText;
}

function sanitizeFormPassword($inputText) {
  $inputText = strip_tags($inputText);
  return $inputText;
}

if (isset($_POST['registerButton'])) {
  // 登録ボタンを押したとき
  $username = sanitizeFormString($_POST['username']);
  $firstName = sanitizeFormString($_POST['firstName']);
  $lastName = sanitizeFormString($_POST['lastName']);
  $email = sanitizeFormString($_POST['email']);
  $email2 = sanitizeFormString($_POST['email2']);
  $password = sanitizeFormPassword($_POST['password']);
  $password2 = sanitizeFormPassword($_POST['password2']);

  // register.phpでincludeした状態でnew（インスタンス化）しているのでAcountクラスのメソッドが使える
  $wasSuccessful  = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

  if ($wasSuccessful) {
      $_SESSION['userLoggedIn'] = $username;
      header('Location: index.php');
  }

}

?>
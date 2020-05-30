<?php

  if (isset($_POST['loginButton'])) {
      // ログインボタンを押したとき
      $username = $_POST['loginUsername'];
      $password = $_POST['loginPassword'];

      $result = $account->login($username, $password);

      if ($result) {
        // ログインが成功したら
        $_SESSION['userLoggedIn'] = $username;
        header('Location: index.php');
      }
  }
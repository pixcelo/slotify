<?php 
include('includes/config.php');



// ログイン処理を実行してindex.phpに訪問した場合のみ$_SESSION['userLoggedIn']が渡ってくる
if (isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
    // var_dump($userLoggedIn); // ユーザーネームが代入されている
} else {
  header('Location: register.php');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>welcome to Slotify!</title>
</head>
<body>
  hello!
</body>
</html>
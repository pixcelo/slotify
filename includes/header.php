<?php 
include('includes/config.php');
include('includes/classes/Artist.php');
include('includes/classes/Album.php'); // クラス内でArtistクラスをnewしているのでArtistクラスの下でincludeする
include('includes/classes/Song.php'); // クラス内でArtistクラスとAlbumクラスをnewしているので一番下に配置

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
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/script.js"></script>
</head>
<body>



  <div id="mainContainer">
      <div id="topContainer">

        <?php include("includes/navBarContainer.php"); ?>

        <div id="mainViewContainer">
            <div id="mainContent">



            </div>
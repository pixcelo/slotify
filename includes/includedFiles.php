<?php

// Ajaxでページ遷移したかチェック

if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    // Ajaxで遷移したらヘッダーとフッター以外を入れ替える
    include('includes/config.php');
    include('includes/classes/User.php');
    include('includes/classes/Artist.php');
    include('includes/classes/Album.php');
    include('includes/classes/Song.php');

    if (isset($_GET['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_GET['userLoggedIn']);
    } else {
        echo "usernameの変数が通っていません。JSのopenPageメソッドを確認してください";
        exit();
    }

} else {
    include("includes/header.php");
    include("includes/footer.php");

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}

?>
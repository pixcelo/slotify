<?php

// Ajaxでページ遷移したかチェック
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    echo "CAME FROM ajax";
} else {
    include("includes/header.php");
    include("includes/footer.php");

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
}

?>
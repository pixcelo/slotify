<?php
include("../../config.php");

if (isset($_POST['name']) && isset($_POST['username'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $date  = date("Y-m-d");

    $Query = mysqli_query($con, "INSERT INTO playlists VALUES(NULL, '$name', '$username', '$date')");
    
} else {

    echo "名前もしくはユーザーネームが不正です。";
    
}
<?php
include("includes/header.php"); 
include("includes/classes/Artist.php"); 

if(isset($_GET['id'])) {
    $albumId =  $_GET['id'];
}
else {
    header("Location: index.php");
}

$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE id='$albumId'");
$album = mysqli_fetch_array($albumQuery);

// classes/Artist.phpクラスをインスタンス化
$artist = new Artist($con, $album['artist']);

echo $album['title'] . "<br>";

// Artist.phpクラスのメソッド
echo $artist->getName();

?>




<?php include("includes/footer.php"); ?>
<?php include("includes/header.php"); 

if(isset($_GET['id'])) {
    $albumId =  $_GET['id'];
}
else {
    header("Location: index.php");
}

// アルバムのデータを参照
$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE id='$albumId'");
$album = mysqli_fetch_array($albumQuery);

// アーティストのデータを参照
$artistId = $album['artist'];
$artistQuery = mysqli_query($con, "SELECT * FROM artists WHERE id='$artistId'");
$artist = mysqli_fetch_array($artistQuery);

echo $album['title'] . "<br>";
echo $artist['name'];

?>




<?php include("includes/footer.php"); ?>
<?php
include("includes/includedFiles.php");
?>

<div class="playlistsContainer">
    <div class="gridViewContainer">
      <h2>PLAYLISTS</h2>

      <div class="buttonItems">
        <button class="button green" onclick="createPlaylist()">NEW PLAYLIST</button>
      </div>

      <?php
          $username = $userLoggedIn->getUsername();
          // var_dump($username);

          $playlistQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$username'");

          if (mysqli_num_rows($playlistQuery) == 0) {
            echo "<span class='noResults'>プレイリストがありません。</span>";
          }

          while($row = mysqli_fetch_array($playlistQuery)) {

            echo "<div class='gridViewItem'>

                      <div class='playlistImage'>
                        <img src='assets/images/icons/playlist.png'>
                      </div>

                      <div class='gridViewInfo'>"
                          . $row['name'] .
                      "</div>

                  </div>";

          }
      ?>



    </div>
</div>
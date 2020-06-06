<?php
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();

while($row = mysqli_fetch_array($songQuery)) {
    array_push($resultArray, $row['id']);
}

// 配列にidを格納してJSON形式にエンコード
$jsonArray = json_encode($resultArray);
?>

<script>

  $(document).ready(function() {
      currentPlaylist = <?php echo $jsonArray; ?>;
      audioElement = new Audio(); // from script.js
      setTrack(currentPlaylist[0], currentPlaylist, false);
  });

  // arguments(songId, song, true or false) trueで音楽再生
  function setTrack(trackId, newPlaylist, play) {
    
    // ajax getSongJson.phpの内容(JSONデータ)が引数dataに入る
    $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

        // console.log(data);
        let track = JSON.parse(data);
        // console.log(track);
        
        // JSONデータのtitleをtrackNameに表示
        $(".trackName span").text(track.title);

        // JSONデータからアーティストネームを取得
        $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
            let artist = JSON.parse(data);
            $(".artistName span").text(artist.name);
        });

        // JSONデータからアルバムのアートワークを取得
        $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
            let album = JSON.parse(data);
            $(".albumLink img").attr("src", album.artworkPath);
        });

        audioElement.setTrack(track);
        playSong();
    });

      if (play) {
        audioElement.play();
      }

  }

  function playSong() {
      // console.log(audioElement );
      if (audioElement.audio.currentTime == 0) {
          $.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
      }

      $(".controlButton.play").hide();
      $(".controlButton.pause").show();
      audioElement.play();
  }

  function pauseSong() {
      $(".controlButton.play").show();
      $(".controlButton.pause").hide();
      audioElement.pause();
  }

</script>


<div id="nowPlayingBarContainer">
  <div id="nowPlayingBar">

    <div id="nowPlayingLeft">
      <div class="content">
        <span class="albumLink">
            <img src="" alt="" class="albumArtwork">
        </span>

        <div class="trackInfo">
          <span class="trackName">
            <span></span>
          </span>

          <span class="artistName">
            <span></span>
          </span>
        </div>

      </div>


    </div>

    <div id="nowPlayingCenter">

        <div class="content playerControls">

          <div class="buttons">


              <button class="controlButton shuffle" title="Shuffle button">
                <img src="assets/images/icons/shuffle.png" alt="Shuffle">
              </button>

              <button class="controlButton previous" title="Previous button">
                <img src="assets/images/icons/previous.png" alt="Previous">
              </button>

              <button class="controlButton play" title="Play button" onclick="playSong()">
                <img src="assets/images/icons/play.png" alt="Play">
              </button>

              <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                <img src="assets/images/icons/pause.png" alt="Pause">
              </button>

              <button class="controlButton next" title="Shuffle button">
                <img src="assets/images/icons/next.png" alt="Shuffle">
              </button>

              <button class="controlButton repeat" title="Repeat button">
                <img src="assets/images/icons/repeat.png" alt="Repeat">
              </button>

          </div>

          <div class="playbackBar">
              
            <span class="progressTime current">0.00</span>
            <div class="progressBar">
                <div class="progressBarBg">
                    <div class="progress"></div>
                </div>
            </div>
            <span class="progressTime remaining">0.00</span>

          </div>

        </div>

    </div>

    <div id="nowPlayingRight">
        <div class="volumeBar">

          <button class="controlButton volume" title="Volume Button">
              <img src="assets/images/icons/volume.png" alt="Volume">
          </button>

          <div class="progressBar">
                <div class="progressBarBg">
                    <div class="progress"></div>
                </div>
          </div>
        </div>
    </div>

  </div>
</div>
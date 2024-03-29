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

  // プログレスバーのマウス操作
  $(document).ready(function() {
      var newPlaylist = <?php echo $jsonArray; ?>;
      audioElement = new Audio(); // from script.js
      setTrack(newPlaylist[0], newPlaylist, false);
      updateVolumeProgressBar(audioElement.audio);

      $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
          e.preventDefault();
      });

      $(".playbackBar .progressBar").mousedown(function() {
          mouseDown = true;
      });

      $(".playbackBar .progressBar").mousemove(function(e) {
          if(mouseDown) {
              timeFromOffset(e, this);
          }
      });

      $(".playbackBar .progressBar").mouseup(function(e) {
          timeFromOffset(e, this);
      });

      // ボリューム調整
      $(".volumeBar .progressBar").mousedown(function() {
          mouseDown = true;
      });

      $(".volumeBar .progressBar").mousemove(function(e) {
          if(mouseDown) {

              var percentage = e.offsetX / $(this).width();

              if(percentage >= 0 && percentage <= 1) {
                  audioElement.audio.volume = percentage;
              }
          }
      });

      $(".volumeBar .progressBar").mouseup(function(e) {
          var percentage = e.offsetX / $(this).width();

          if(percentage >= 0 && percentage <= 1) {
              audioElement.audio.volume = percentage;
          }
      });

      $(document).mouseup(function() {
          mouseDown = false;
      });

  });

  function timeFromOffset(mouse, progressBar) {
      var percentage = mouse.offsetX / $(progressBar).width() * 100;
      var seconds = audioElement.audio.duration * (percentage / 100);
      audioElement.setTime(seconds);
  }

  // 前の曲を再生（曲の頭に戻る）
  function prevSong() {
      // 再生時間が3秒より大きい、または一番最初の曲なら
      if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
          audioElement.setTime(0);
      } else {
          currentIndex = currentIndex - 1;
          setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
      }
  }

  // 次の曲を再生
  function nextSong() {

      if(repeat) {
          audioElement.setTime(0);
          playSong();
          return;
      }

      // インデックスが最後なら先頭（0）に戻す
      if(currentIndex == currentPlaylist.length - 1) {
          currentIndex = 0;
      } else {
          currentIndex++;
      }

      // シャッフルがtrueならインデックス変更
      var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
      setTrack(trackToPlay, currentPlaylist, true);
  }

  // リピート再生のオン・オフ
  function setRepeat() {
      repeat = !repeat; // デフォルトはfalse
      var imageName = repeat ? "repeat-active.png" : "repeat.png";
      $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
  }

  // ミュート機能
  function setMute() {
      audioElement.audio.muted = !audioElement.audio.muted;
      var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
      $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
  }

  // シャッフル機能
  function setShuffle() {
      shuffle = !shuffle; // デフォルトはfalse
      var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
      $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

      if(shuffle) {
          // プレイリストをランダムに並び替え
          shuffleArray(shufflePlaylist);
          // indexOfは検索を始め、指定された値が最初に現れたインデックスを返す
          currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
      } else {
          currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
      }
  }

  /**
  * Shuffles array in place.
  * @param {Array} a items An array containing the items.
  */
  function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
  }

  // arguments(songId, song, true or false) trueで音楽再生
  function setTrack(trackId, newPlaylist, play) {

    if(newPlaylist != currentPlaylist) {
        currentPlaylist = newPlaylist;
        shufflePlaylist = currentPlaylist.slice();
        shuffleArray(shufflePlaylist);
    }

    if(shuffle) {
        currentIndex = shufflePlaylist.indexOf(trackId);
    } else {
        currentIndex = currentPlaylist.indexOf(trackId);
    }
    pauseSong();
    
    // ajax getSongJson.phpの内容(JSONデータ)が引数dataに入る
    $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

        var track = JSON.parse(data);
        
        // JSONデータのtitleをtrackNameに表示
        $(".trackName span").text(track.title);

        // JSONデータからアーティストネームを取得
        $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
            var artist = JSON.parse(data);
            $(".trackInfo .artistName span").text(artist.name);
            $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
        });

        // JSONデータからアルバムのアートワークを取得
        $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
            var album = JSON.parse(data);
            $(".content .albumLink img").attr("src", album.artworkPath);
            $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
            $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
        });

        audioElement.setTrack(track);

        if(play) {
            playSong();
        }
    });

  }

  // 曲の再生、再生ボタンの変更
  function playSong() {

      if(audioElement.audio.currentTime == 0) {
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
            <img role="link" tabindex="0" src="" class="albumArtwork">
        </span>

        <div class="trackInfo">
          <span class="trackName">
            <span role="link" tabindex="0"></span>
          </span>

          <span class="artistName">
            <span role="link" tabindex="0"></span>
          </span>


        </div>

      </div>
    </div>

    <div id="nowPlayingCenter">

        <div class="content playerControls">

          <div class="buttons">


              <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                <img src="assets/images/icons/shuffle.png" alt="Shuffle">
              </button>

              <button class="controlButton previous" title="Previous button" onclick="prevSong()">
                <img src="assets/images/icons/previous.png" alt="Previous">
              </button>

              <button class="controlButton play" title="Play button" onclick="playSong()">
                <img src="assets/images/icons/play.png" alt="Play">
              </button>

              <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                <img src="assets/images/icons/pause.png" alt="Pause">
              </button>

              <button class="controlButton next" title="Next button" onclick="nextSong()">
                <img src="assets/images/icons/next.png" alt="Next">
              </button>

              <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
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

          <button class="controlButton volume" title="Volume button" onclick="setMute()">
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
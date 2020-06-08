let currentPlaylist = [];
let shufflePlaylist = [];
let tempPlaylist = [];
let audioElement;
let mouseDown = false;
let currentIndex = 0;
let repeat = false;
let shuffle = false;
let userLoggedIn;

function openPage(url) {

    // indexOfは検索して見つからない場合は-1を返す
    if (url.indexOf("?") == -1) {
        url = url + "?";
    }
    // URLをユーザー名にチェンジ（通常はalbum.phpが表示）
    let encodeUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodeUrl);
}

// duration（再生時間）のフォーマット
function formatTime(seconds) {
    let time = Math.round(seconds);
    let minutes = Math.floor(time / 60);
    let secondsFormatted = time - (minutes * 60);

    let extraZero;

    if (secondsFormatted < 10) {
      extraZero = "0";
    } else {
      extraZero = "";
    }

    return minutes + ":" + extraZero + secondsFormatted;
}

// 再生時間に応じてプログレスバーを変化する
function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    let progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");
}

// ボリュームに応じてプログレスバーを変化する
function updateVolumeProgressBar(audio) {
    let volume = audio.volume * 100;
    $(".volumeBar .progress").css("width", volume + "%");
}

function Audio() {

  this.currentlyPlaying;
  this.audio = document.createElement('audio');

  // 曲が終わったら次の曲を再生
  this.audio.addEventListener("ended", function() {
      nextSong();
  });

  this.audio.addEventListener("canplay", function() {
      let duration = formatTime(this.duration);
      $(".progressTime.remaining").text(duration);
  });

  this.audio.addEventListener("timeupdate", function() {
      if (this.duration) {
          updateTimeProgressBar(this);
      }
  });

  this.audio.addEventListener("volumechange", function() {
      updateVolumeProgressBar(this);
  });

  this.setTrack = function(track) {
      this.currentlyPlaying = track;
      this.audio.src = track.path;
  }

  this.play = function() {
      this.audio.play();
  }

  this.pause = function() {
      this.audio.pause();
  }

  this.setTime = function(seconds) {
    this.audio.currentTime = seconds;
  }

}
let currentPlaylist = [];
let shufflePlaylist = [];
let tempPlaylist = [];
let audioElement;
let mouseDown = false;
let currentIndex = 0;
let repeat = false;
let shuffle = false;
var userLoggedIn; // varは変数の重複を許す
let timer;

function openPage(url) {

    // ページ遷移後はtimerを切る
    if (timer != null) {
        clearTimeout(timer);
    }

    // indexOfは検索して見つからない場合は-1を返す
    if (url.indexOf("?") == -1) {
        url = url + "?";
    }
    // URLをユーザー名にチェンジ（通常はalbum.phpが表示）
    let encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    console.log(encodedUrl);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url); // ブラウザ履歴に指定のurl追加
}

function createPlaylist() {
    // prompt:ユーザにテキストを入力することを促すメッセージを持つダイアログを表示
    let popup = prompt("プレイリスト名を入力してください");

    if (alert != null) {
        
        // $.postは$.ajaxの略記
        $.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn })
        .done(function(error) {
            
            if (error != "") {
                alert(error);
                return;
            }
            // doneは後に実行したい処理を書く
            openPage("yourMusic.php");
        });
    }
}

function deletePlaylist(playlistId) {
    let prompt = confirm("本当にプレイリストを削除しますか?");

    if (prompt) {

        $.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
        .done(function(error) {
            
            if (error != "") {
                alert(error);
                return;
            }
            // doneは後に実行したい処理を書く
            openPage("yourMusic.php");
        });

    }
}

// duration（再生時間）のフォーマット
function formatTime(seconds) {
    let time = Math.round(seconds);
    let minutes = Math.floor(time / 60);
    let secondsFormatted = time - (minutes * 60);

    let extraZero = (secondsFormatted < 10) ? "0" : "";

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

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
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
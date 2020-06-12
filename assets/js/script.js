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

// クラスを持っていない場所をクリックしたらオプションを非表示にする
$(document).click(function(click) {
    let target = $(click.target);
    
    if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenus();
    }
});

// 画面をスクロールしたときにオプションを非表示にする
$(window).scroll(function() {
    hideOptionsMenus();
});

// オプションのセレクトボタンが変更されたら
$(document).on("change", "select.playlist", function() {
    // このthisは、change要素=>optionを指す
    var playlistId = $(this).val();
    var songId = $(this).prev(".songId").val(); //prev 一つ前の要素を抽出する

    console.log("playlistId:" + playlistId);
    console.log("songId:" + songId);
});

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

function hideOptionsMenus() {
    let menu = $(".optionsMenu");
    if (menu.css("display") != "none") {
        menu.css("display", "none");
    }
}


// 曲の横にオプション nav class="optionsMenu"を表示する
function showOptionsMenu(button) {
    var songId = $(button).prevAll(".songId").val();
    let menu = $(".optionsMenu");
    let menuWidth = menu.width();
    menu.find(".songId").val(songId);

    let scrollTop = $(window).scrollTop(); // distance from top of window to top of ducument
    let elementOffset = $(button).offset().top; // distance from top of document

    let top = elementOffset - scrollTop;
    let left = $(button).position().left;

    menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline"});

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
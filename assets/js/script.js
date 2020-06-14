// varは変数の重複を許す
var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

// クラスを持っていない場所をクリックしたらオプションを非表示にする
$(document).click(function(click) {
    var target = $(click.target);
    
    if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenu();
    }
});

// 画面をスクロールしたときにオプションを非表示にする
$(window).scroll(function() {
    hideOptionsMenu();
});

// オプションのセレクトボタンが変更されたら
$(document).on("change", "select.playlist", function() {
    // このthisは、change要素=>optionを指す
    var select = $(this);

    var playlistId = select.val();
    var songId = select.prev(".songId").val(); //prev 一つ前の要素を抽出する

    $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId})
    .done(function(error) {

        if(error != "") {
            alert(error);
            return;
        }
        
        hideOptionsMenu();
        select.val("");
    });

});

function updateEmail(emailClass) {
    var emailValue = $("." + emailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn})
    .done(function(response) {
        $("." + emailClass).nextAll(".message").text(response);
    });
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    var oldPassword = $("." + oldPasswordClass).val();
    var newPassword1 = $("." + newPasswordClass1).val();
    var newPassword2 = $("." + newPasswordClass2).val();

    $.post("includes/handlers/ajax/updatePassword.php", 
    { oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn})
    .done(function(response) {
        $("." + oldPasswordClass).nextAll(".message").text(response);
    });
}

function logout() {
    $.post("includes/handlers/ajax/logout.php", function() {
        location.reload();
    });
}

function openPage(url) {

    // ページ遷移後はtimerを切る
    if(timer != null) {
        clearTimeout(timer);
    }

    // indexOfは検索して見つからない場合は-1を返す
    if(url.indexOf("?") == -1) {
        url = url + "?";
    }
    // URLをユーザー名にチェンジ（通常はalbum.phpが表示）
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url); // ブラウザ履歴に指定のurl追加
}

function removeFromPlaylist(button, playlistId) {
    var songId = $(button).prevAll(".songId").val();

    $.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function(error) {
        
        if(error != "") {
            alert(error);
            return;
        }
        // ajaxがreturnしたときに処理を実行
        openPage("playlist.php?id=" + playlistId);
    });

}

function createPlaylist() {
    // prompt:ユーザにテキストを入力することを促すメッセージを持つダイアログを表示
    var popup = prompt("プレイリスト名を入力してください");

    if(popup != null) {
        
        // $.postは$.ajaxの略記
        $.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn })
        .done(function(error) {
            
            if(error != "") {
                alert(error);
                return;
            }
            // doneは後に実行したい処理を書く
            openPage("yourMusic.php");
        });
    }
}

function deletePlaylist(playlistId) {
    var prompt = confirm("本当にプレイリストを削除しますか?");

    if(prompt) {

        $.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
        .done(function(error) {
            
            if(error != "") {
                alert(error);
                return;
            }
            // doneは後に実行したい処理を書く
            openPage("yourMusic.php");
        });

    }
}

function hideOptionsMenu() {
    var menu = $(".optionsMenu");
    if(menu.css("display") != "none") {
        menu.css("display", "none");
    }
}

// 曲の横にオプション nav class="optionsMenu"を表示する
function showOptionsMenu(button) {
    var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    menu.find(".songId").val(songId);

    var scrollTop = $(window).scrollTop(); //Distance from top of window to top of document
    var elementOffset = $(button).offset().top; //Distance from top of document

    var top = elementOffset - scrollTop;
    var left = $(button).position().left;

    menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });

}

// duration（再生時間）のフォーマット
function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60); //Rounds down
    var seconds = time - (minutes * 60);

    var extraZero = (seconds < 10) ? "0" : "";

    return minutes + ":" + extraZero + seconds;
}

// 再生時間に応じてプログレスバーを変化する
function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    var progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");
}

// ボリュームに応じてプログレスバーを変化する
function updateVolumeProgressBar(audio) {
    var volume = audio.volume * 100;
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
      //'this' refers to the object that the event was called on
      var duration = formatTime(this.duration);
      $(".progressTime.remaining").text(duration);
  });

  this.audio.addEventListener("timeupdate", function(){
      if(this.duration) {
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
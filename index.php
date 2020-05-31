<?php 
include('includes/config.php');


// ログイン処理を実行してindex.phpに訪問した場合のみ$_SESSION['userLoggedIn']が渡ってくる
if (isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
    // var_dump($userLoggedIn); // ユーザーネームが代入されている
} else {
  header('Location: register.php');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>welcome to Slotify!</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="nowPlayingBarContainer">
        <div id="nowPlayingBar">

          <div id="nowPlayingLeft">
            
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

                    <button class="controlButton play" title="Play button">
                      <img src="assets/images/icons/play.png" alt="Play">
                    </button>

                    <button class="controlButton Pause" title="Pause button" style="display: none;">
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

          </div>

        </div>
    </div>
</body>
</html>
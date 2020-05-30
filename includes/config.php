<?php
  // 出力のバッファリングを有効にする（計算した結果をすぐに表示せずに貯めておく）
  ob_start();

  $timezone = date_default_timezone_set('Asia/Tokyo');

  // 引数は（ホスト名, ユーザー名, パスワード, データベース名）
  $con = mysqli_connect('localhost', 'root', 'root', 'slotify');

  if (mysqli_connect_error()) {
    echo "Failed to connect: ". mysqli_connect_error();
  }
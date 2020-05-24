<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>slotify</title>
</head>
<body>

  <div id="inputContainer">
    <form id="loginForm" action="register.php" method="POST">
      <h2>ログイン</h2>
      <p>
      <label for="loginUsername">氏名</label>
        <input id="loginUsername" name="loginUsername" type="text" placeholder="名前を入力してください" required>
      </p>
      <p>
        <label for="loginPassword">パスワード</label>
        <input id="loginPassword" name="loginPassword" type="password" required>
      </p>

      <button type="submit" name="loginButton">ログイン</button>
    </form>
  </div>
  
</body>
</html>
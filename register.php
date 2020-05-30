<?php
  ini_set('display_errors',1);
  include('includes/config.php');
  include('includes/classes/Account.php');
  include('includes/classes/Constants.php');

  $account = new Account($con);

  include('includes/handlers/register-handler.php');
  include('includes/handlers/login-handler.php');

  // inputのvalueにPOSTで渡った値を表示
  function getInputValue($name) {
      if (isset($_POST[$name])) {
        echo $_POST[$name];
      }
  }
?>

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
      <label for="loginUsername">ユーザーネーム</label>
        <input id="loginUsername" name="loginUsername" type="text" placeholder="ユーザーネーム" required>
      </p>
      <p>
        <label for="loginPassword">パスワード</label>
        <input id="loginPassword" name="loginPassword" type="password" placeholder="パスワード" required>
      </p>

      <button type="submit" name="loginButton">ログイン</button>
    </form>

    <form id="registerForm" action="register.php" method="POST">
      <h2>アカウント新規作成</h2>
      <p>
      <!-- エラーがあればAccountクラスのエラーメッセージを表示する -->
      <?php echo $account->getError(Constants::$userNameCharacters); ?>
      <label for="username">ユーザーネーム</label>
        <input id="username" name="username" type="text" placeholder="たっくん" value="<?php getInputValue('username') ?>" required>
      </p>
      <p>
      <?php echo $account->getError(Constants::$firstNameCharacters); ?>
      <label for="firstName">名前</label>
        <input id="firstName" name="firstName" type="text" placeholder="太郎" value="<?php getInputValue('firstName') ?>" required>
      </p>
      <p>
      <?php echo $account->getError(Constants::$lastNameCharacters); ?>
      <label for="lastName">性別</label>
        <input id="lastName" name="lastName" type="text" placeholder="田中" value="<?php getInputValue('lastName') ?>" required>
      </p>
      <p>
      <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
      <?php echo $account->getError(Constants::$emailInvalid); ?>
      <label for="email">メールアドレス</label>
        <input id="email" name="email" type="email" placeholder="test@test.com" value="<?php getInputValue('email') ?>" required>
      </p>
      <p>
      <label for="email2">メールアドレス確認用</label>
        <input id="email2" name="email2" type="email" placeholder="test@test.com" value="<?php getInputValue('email2') ?>" required>
      </p>
      <p>
        <?php echo $account->getError(Constants::$passwordsDoNomatch); ?>
        <?php echo $account->getError(Constants::$passwordsNotAlphanumeric); ?>
        <?php echo $account->getError(Constants::$passwordsCharacters); ?>
        <label for="password">パスワード</label>
        <input id="password" name="password" type="password" placeholder="パスワード" required>
      </p>
      <p>
        <label for="password2">パスワード確認用</label>
        <input id="password2" name="password2" type="password" placeholder="パスワード" required>
      </p>


      <button type="submit" name="registerButton">登録</button>
    </form>
  </div>
  
</body>
</html>
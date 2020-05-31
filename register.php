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
  <link rel="stylesheet" href="assets/css/register.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="assets/js/register.js"></script>
</head>
<body>

  <?php
  // 通常はloginForm画面を表示
  // 登録ボタンをPOSTしてエラーならregisterFormを表示
  // 登録ボタンをPOSTして成功すればログインしてindex.phpに遷移
  if(isset($_POST['registerButton'])) {
    echo '<script>
            $(document).ready(function() {
                $("#loginForm").hide();
                $("#registerForm").show();
            });
          </script>';
  } else {
    echo '<script>
            $(document).ready(function() {
                $("#loginForm").show();
                $("#registerForm").hide();
            });
          </script>';
  }
  ?>

  <div id="background">

    <div id="loginContainer">

        <div id="inputContainer">
          <form id="loginForm" action="register.php" method="POST">
            <h2>アカウントでログイン</h2>
            <p>
            <?php echo $account->getError(Constants::$loginFailed); ?>
            <label for="loginUsername">ユーザーネーム</label>
              <input id="loginUsername" name="loginUsername" type="text" placeholder="例：たっくん" value="<?php getInputValue('loginUsername') ?>"  required>
            </p>
            <p>
              <label for="loginPassword">パスワード</label>
              <input id="loginPassword" name="loginPassword" type="password" placeholder="あなたのパスワード" required>
            </p>

            <button type="submit" name="loginButton">ログイン</button>

            <div class="hasAccountText">
              <span class="hideLogin">アカウント登録はこちら</span>
            </div>

          </form>

          <form id="registerForm" action="register.php" method="POST">
            <h2>アカウント新規登録</h2>
            <p>
            <!-- エラーがあればAccountクラスのエラーメッセージを表示する -->
            <?php echo $account->getError(Constants::$userNameCharacters); ?>
            <?php echo $account->getError(Constants::$usernameTaken); ?>
            <label for="username">ユーザーネーム</label>
              <input id="username" name="username" type="text" placeholder="例：たっくん" value="<?php getInputValue('username') ?>" required>
            </p>
            <p>
            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
            <label for="firstName">名前</label>
              <input id="firstName" name="firstName" type="text" placeholder="例：太郎" value="<?php getInputValue('firstName') ?>" required>
            </p>
            <p>
            <?php echo $account->getError(Constants::$lastNameCharacters); ?>
            <label for="lastName">性別</label>
              <input id="lastName" name="lastName" type="text" placeholder="例：田中" value="<?php getInputValue('lastName') ?>" required>
            </p>
            <p>
            <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
            <?php echo $account->getError(Constants::$emailInvalid); ?>
            <?php echo $account->getError(Constants::$emailTaken); ?>
            <label for="email">メールアドレス</label>
              <input id="email" name="email" type="email" placeholder="例：test@gmail.com" value="<?php getInputValue('email') ?>" required>
            </p>
            <p>
            <label for="email2">メールアドレス確認</label>
              <input id="email2" name="email2" type="email" placeholder="例：test@gmail.com" value="<?php getInputValue('email2') ?>" required>
            </p>
            <p>
              <?php echo $account->getError(Constants::$passwordsDoNomatch); ?>
              <?php echo $account->getError(Constants::$passwordsNotAlphanumeric); ?>
              <?php echo $account->getError(Constants::$passwordsCharacters); ?>
              <label for="password">パスワード</label>
              <input id="password" name="password" type="password" placeholder="あなたのパスワード" required>
            </p>
            <p>
              <label for="password2">パスワード確認</label>
              <input id="password2" name="password2" type="password" placeholder="あなたのパスワード" required>
            </p>

            <button type="submit" name="registerButton">登録</button>

            <div class="hasAccountText">
              <span class="hideRegister">ログインはこちら</span>
            </div>

          </form>

        </div><!-- id="inputContainer" -->

        <div id="loginText">
            <h1>Get great music, right now</h1>
            <h2>Listen to loads of songs for free.</h2>
            <ul>
              <li>Discover music you'll fall in love with</li>
              <li>Create your own playlists</li>
              <li>Follow artists to keep up to date</li>
            </ul>
        </div>

    </div><!-- id="loginContainer" -->
  </div><!-- id="background" -->

</body>
</html>
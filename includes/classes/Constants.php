<?php
Class Constants {
    
    // アカウント登録
    public static $passwordsDoNomatch = "パスワードが一致していません。";
    public static $passwordsNotAlphanumeric = "パスワードは文字や数字で入力してください。";
    public static $passwordsCharacters = "パスワードは5〜30文字の間で設定してください。";
    public static $emailInvalid = "メールアドレスの形式が間違っています。";
    public static $emailsDoNotMatch = "メールアドレスが一致していません。";
    public static $emailTaken = "このメールアドレスはすでに使用されています。";
    public static $lastNameCharacters = "名字は2〜5文字の間で設定してください。";
    public static $firstNameCharacters = "名前は2〜5文字の間で設定してください。";
    public static $userNameCharacters = "ユーザーネームは5〜25文字の間で設定してください。";
    public static $usernameTaken = "このユーザーネームはすでに使用されています。";

    // ログイン
    public static $loginFailed = "ユーザーネームかパスワードが間違っています。";
    
}
?>

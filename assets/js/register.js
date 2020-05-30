$(document).ready(function() {

    // クリックでログインとアカウント登録フォームを開閉
    $(".hideLogin").click(function() {
        $("#loginForm").hide();
        $("#registerForm").show();
    });

    $(".hideRegister").click(function() {
      $("#loginForm").show();
      $("#registerForm").hide();
    });
});
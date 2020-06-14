<?php
include("includes/includedFiles.php");
?>

<div class="userDetails">

    <div class="container border-bottom">
        <h2>EMAIL</h2>
        <input type="text" class="email" name="email" placeholder="Email address..." value="<?php echo $userLoggedIn->getEmail(); ?>">
        <span class="message"></span>
        <button class="button" onclick="">SAVE</button>
    </div>

    <div class="container">
        <h2>PASSOWORD</h2>
        <input type="text" class="oldPassword" name="oldPassword" placeholder="Current password">
        <input type="text" class="newPassword1" name="newPassword1" placeholder="New password">
        <input type="text" class="newPassword2" name="newPassword2" placeholder="Confirm password">
        <span class="message"></span>
        <button class="button" onclick="">SAVE</button>
    </div>

</div> 
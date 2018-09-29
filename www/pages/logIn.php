<?php require_once('../includes/common.php'); ?>
<?php /* require_once('../controllers/member.php'); */ ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<div id="login_page" data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <form id="login_form" style="width: 300px; margin: 30px auto 0;">
            <input type="text" id="login_username" name="Username" placeholder="username">
            <input type="password" id="login_password" name="Password" placeholder="password">
            <button id="login_button" class="ui-btn ui-btn-b ui-corner-all">Log In</button>
            <div id="login_error" class="errormessage" style="margin: 6px 4px;"></div>

            <label><input type="checkbox" id="login_persist" name="Persist">Keep me signed in</label>
            <a href="#" id="login_forgotlink" style="display: block; width: 100%; text-align: center;">Forgot password?</a>
        </form>

        <form id="login_forgotform" style="display: none; max-width: 430px; margin: 30px auto 0;">
            <label for="email">Provide the email address associated with your login and we will send a link to reset your password.</label>
            <input type="text" id="login_forgotemail" name="Email" placeholder="email">
            <div id="login_forgoterror" class="errormessage" style="margin: 6px 4px;"></div>
            <button type="submit" id="login_forgotbutton" class="ui-btn ui-btn-b ui-corner-all">Send Reset Email</button>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>
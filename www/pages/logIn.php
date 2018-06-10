<?php require_once('../includes/common.php'); ?>
<?php /* require_once('../controllers/member.php'); */ ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
    <script src="<?= $top ?>scripts/common.js"></script>
</head>
<body>

<div data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <form id="login_form" style="width: 300px; margin: 30px auto 0;">
            <input type="text" id="login_username" name="Username" placeholder="username">
            <input type="password" id="login_password" name="Password" placeholder="password">
            <button id="login_button" class="ui-btn ui-btn-b ui-corner-all">Log In</button>
            <div id="login_error" class="error" style="margin: 6px 4px;"></div>

            <label><input type="checkbox" id="login_persist" name="Persist">Keep me signed in</label>
            <a href="#">Forgot password?</a>
        </form>

        <?php /* MemberController::logIn('', '') */ ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>
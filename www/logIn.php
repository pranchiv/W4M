<?php require_once('includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
    <script src="<?= $top ?>scripts/sample.js"></script>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <form id="sample_formLogin" style="width: 300px; margin: 30px auto 0;">
            <input type="text" id="sample_username" name="Username" placeholder="username">
            <input type="password" id="sample_password" name="Password" placeholder="password">

            <button id="loginButton" class="ui-btn ui-btn-b ui-corner-all">Log In</button>

            <div id="loginError" class="error" style="margin: 6px 4px;"></div>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>

</div><!-- /page -->


</body>
</html>
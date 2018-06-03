<?php require_once('includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include("../includes/head.php"); ?>
    <script src="scripts/sample.js"></script>
</head>
<body>

<?php require_once('connection.php'); ?>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include("includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <form id="sample_formLogin">
            <input type="text" id="sample_username" name="Username" placeholder="username">
            <input type="password" id="sample_password" name="Password" placeholder="password">

            <button id="loginButton" class="ui-btn ui-btn-b ui-corner-all">Log In</button>

            <div id="loginError" class="error" style="margin: 6px 4px;"></div>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("includes/footer.php"); ?>
    </div>

</div><!-- /page -->


</body>
</html>